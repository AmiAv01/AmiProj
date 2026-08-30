<?php

namespace App\Services\DbfImport;

use InvalidArgumentException;

final class LegacyCrypt
{
    public function __construct(private readonly string $key)
    {
        if (strlen($key) < 2) {
            throw new InvalidArgumentException('DBF_ENCRYPTION_KEY must contain at least two bytes.');
        }
    }

    public function decrypt(string $value): string
    {
        $keyBytes = array_values(unpack('C*', $this->key));
        $valueBytes = array_values(unpack('C*', $value));
        $keyLength = count($keyBytes);
        $keyPosition = 0;
        $count2 = ((ord($this->key[0]) + (ord($this->key[1]) * 256)) & 0xFFFF) ^ ($keyLength & 0xFFFF);
        $count1 = 0xAAAA;
        $result = '';

        foreach ($valueBytes as $valueByte) {
            $temporary1 = $count1;
            $temporary2 = $count2;
            $byte = $valueByte ^ $keyBytes[$keyPosition++];

            $low = $temporary2 & 0xFF;
            $high = ($temporary2 >> 8) & 0xFF;
            $temporary2 = (($low ^ $high) & 0xFF) | ($high << 8);

            for ($rotation = $temporary2 & 0xFF; $rotation > 0; $rotation--) {
                $temporary2 = ($temporary2 >> 1) | (($temporary2 & 1) << 15);
            }

            $temporary2 = (($temporary2 ^ $temporary1) + 16) & 0xFFFF;
            $count2 = $temporary2;
            $temporary2 = ($temporary2 & 0x1E) + 2;

            do {
                $temporary2--;

                for ($rotation = $temporary2 & 0xFF; $rotation > 0; $rotation--) {
                    $temporary1 = ($temporary1 >> 1) | (($temporary1 & 1) << 15);
                }

                $low = $temporary1 & 0xFF;
                $high = ($temporary1 >> 8) & 0xFF;
                $temporary1 = ($high & 0xFF) | ($low << 8);
                $temporary1 = ((($temporary1 & 0xFF) ^ 0xFF) & 0xFF) | (($temporary1 >> 8) << 8);
                $temporary1 = (($temporary1 << 1) | (($temporary1 & 0x8000) >> 15)) & 0xFFFF;
                $temporary1 ^= 0xAAAA;

                $temporaryByte = $temporary1 & 0xFF;
                $temporaryByte = (($temporaryByte << 1) | (($temporaryByte & 0x80) >> 7)) & 0xFF;
                $temporary1 = $temporaryByte | ((($temporary1 >> 8) & 0xFF) << 8);
            } while (--$temporary2 > 0);

            $count1 = $temporary1;
            $result .= chr($byte ^ ($temporary1 & 0xFF));

            if ($keyPosition === $keyLength) {
                $keyPosition = 0;
            }
        }

        return $result;
    }
}
