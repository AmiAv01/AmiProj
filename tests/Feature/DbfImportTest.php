<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Detail;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

function createFirmDbf(string $path, string $name): void
{
    $fields = [
        ['CODE', 'N', 10, 0],
        ['TYPE', 'C', 40, 0],
    ];
    $headerLength = 32 + (32 * count($fields)) + 1;
    $recordLength = 1 + array_sum(array_column($fields, 2));
    $header = chr(0x03).pack('CCC', 126, 8, 30).pack('Vvv', 1, $headerLength, $recordLength).str_repeat("\0", 20);

    foreach ($fields as [$fieldName, $type, $length, $decimals]) {
        $header .= str_pad($fieldName, 11, "\0").$type.str_repeat("\0", 4).chr($length).chr($decimals).str_repeat("\0", 14);
    }

    $record = ' '.str_pad('7', 10, ' ', STR_PAD_LEFT).str_pad($name, 40);
    file_put_contents($path, $header."\x0D".$record."\x1A");
}

function createCompactAltDbf(string $path): void
{
    $fields = [['TMP', 'C', 20, 0], ['HCPARTS', 'C', 20, 0], ['BRAND', 'C', 20, 0], ['AWIR', 'C', 20, 0]];
    $headerLength = 32 + (32 * count($fields)) + 1;
    $recordLength = 1 + array_sum(array_column($fields, 2));
    $header = chr(0x03).pack('CCC', 126, 8, 30).pack('Vvv', 1, $headerLength, $recordLength).str_repeat("\0", 20);

    foreach ($fields as [$fieldName, $type, $length, $decimals]) {
        $header .= str_pad($fieldName, 11, "\0").$type.str_repeat("\0", 4).chr($length).chr($decimals).str_repeat("\0", 14);
    }

    $record = ' '.str_pad('TMP-1', 20).str_pad('HC-1', 20).str_pad('BOSCH', 20).str_pad('12345', 20);
    file_put_contents($path, $header."\x0D".$record."\x1A");
}

it('inserts, updates, and skips unchanged DBF records', function (): void {
    $directory = sys_get_temp_dir().'/ami_dbf_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/FIRMS.DBF';

    try {
        createFirmDbf($path, 'Original');

        $this->artisan('dbf:sync', ['--file' => ['FIRMS.DBF'], '--source' => $directory])
            ->assertSuccessful();
        expect(DB::table('firm')->where('fr_code', 7)->value('fr_name'))->toBe('Original')
            ->and(DB::table('firm')->where('fr_code', 7)->count())->toBe(1);

        createFirmDbf($path, 'Changed');

        $this->artisan('dbf:sync', ['--file' => ['FIRMS.DBF'], '--source' => $directory])
            ->assertSuccessful();
        expect(DB::table('firm')->where('fr_code', 7)->value('fr_name'))->toBe('Changed')
            ->and(DB::table('firm')->where('fr_code', 7)->count())->toBe(1);

        $this->artisan('dbf:sync', ['--file' => ['FIRMS.DBF'], '--source' => $directory])
            ->expectsOutputToContain('unchanged, skipped')
            ->assertSuccessful();
        expect(DB::table('dbf_import_runs')->where('status', 'skipped')->count())->toBe(1);
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});

it('deduplicates details without breaking cart and order references', function (): void {
    $canonical = Detail::factory()->create(['dt_id' => 100, 'dt_code' => 700, 'dt_invoice' => 'INV-700', 'deleted_at' => null]);
    $duplicate = Detail::factory()->create(['dt_id' => 101, 'dt_code' => 700, 'dt_invoice' => 'INV-700', 'deleted_at' => null]);
    $user = User::factory()->create();
    $cart = Cart::create(['user_id' => $user->id]);
    CartItem::create(['cart_id' => $cart->id, 'dt_id' => $canonical->dt_id, 'quantity' => 2, 'price' => '10.00']);
    CartItem::create(['cart_id' => $cart->id, 'dt_id' => $duplicate->dt_id, 'quantity' => 3, 'price' => '10.00']);
    $order = Order::create([
        'total_price' => '30.00', 'status' => 'new', 'created_by' => $user->id, 'updated_by' => $user->id,
    ]);
    $orderItem = $order->orderItems()->create([
        'detail_id' => $duplicate->dt_id, 'quantity' => 3, 'unit_price' => '10.00',
    ]);

    $this->artisan('dbf:deduplicate', ['--apply' => true])->assertSuccessful();

    expect(DB::table('detail')->where('dt_code', 700)->pluck('dt_id')->all())->toBe([100])
        ->and(DB::table('cart_item')->where('cart_id', $cart->id)->count())->toBe(1)
        ->and(DB::table('cart_item')->where('cart_id', $cart->id)->value('quantity'))->toBe(5)
        ->and(DB::table('order_item')->where('id', $orderItem->id)->value('detail_id'))->toBe(100);
});

it('parses compact compatibility DBFs by column name instead of position', function (): void {
    $directory = sys_get_temp_dir().'/ami_alt_dbf_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/ALT_CZ.DBF';

    try {
        createCompactAltDbf($path);
        $this->artisan('dbf:sync', ['--file' => ['ALT_CZ.DBF'], '--source' => $directory])->assertSuccessful();

        $this->assertDatabaseHas('alt_cz', [
            'tmp' => 'TMP-1', 'hcparts' => 'HC-1', 'brand' => 'BOSCH',
            'typec' => 'Якорь', 'dt_brand' => 'CARGO', 'dt_code' => '12345',
        ]);
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});

it('discovers a DBF inside one of several source ZIP archives', function (): void {
    $directory = sys_get_temp_dir().'/ami_zip_dbf_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $dbfPath = $directory.'/FIRMS.DBF';
    $zipPath = $directory.'/firms.zip';

    try {
        createFirmDbf($dbfPath, 'From archive');
        $archive = new ZipArchive;
        expect($archive->open($zipPath, ZipArchive::CREATE))->toBeTrue();
        $archive->addFile($dbfPath, 'nested/FIRMS.DBF');
        $archive->close();
        unlink($dbfPath);

        $this->artisan('dbf:sync', ['--file' => ['FIRMS.DBF'], '--source' => $directory])->assertSuccessful();
        $this->assertDatabaseHas('firm', ['fr_code' => 7, 'fr_name' => 'From archive']);
    } finally {
        @unlink($dbfPath);
        @unlink($zipPath);
        @rmdir($directory);
    }
});
