<?php

use App\Services\DbfImport\LegacyCrypt;

it('is symmetric with the legacy cipher', function (): void {
    $crypt = new LegacyCrypt('test-key');
    $encrypted = $crypt->decrypt('123.45');

    expect(bin2hex($encrypted))->toBe('7577af14f0a7')
        ->and($crypt->decrypt($encrypted))->toBe('123.45');
});
