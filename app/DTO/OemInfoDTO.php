<?php

namespace App\DTO;

class OemInfoDTO
{
    public function __construct(public readonly string $code, public readonly string $firm, public readonly string $type){}

    public function toArray(): array{
        return [
            'dt_code' => $this->code,
            'dt_firm' => $this->firm,
            'dt_typec' => $this->type
        ];
    }
}
