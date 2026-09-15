<?php

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Detail;
use App\Models\Order;
use App\Models\User;
use App\Services\DbfImport\DbfImporter;
use App\Services\Product\ProductImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

function createCompactAltDbf(string $path, string $tmp = 'TMP-1'): void
{
    $fields = [['TMP', 'C', 20, 0], ['HCPARTS', 'C', 20, 0], ['BRAND', 'C', 20, 0], ['AWIR', 'C', 20, 0]];
    $headerLength = 32 + (32 * count($fields)) + 1;
    $recordLength = 1 + array_sum(array_column($fields, 2));
    $header = chr(0x03).pack('CCC', 126, 8, 30).pack('Vvv', 1, $headerLength, $recordLength).str_repeat("\0", 20);

    foreach ($fields as [$fieldName, $type, $length, $decimals]) {
        $header .= str_pad($fieldName, 11, "\0").$type.str_repeat("\0", 4).chr($length).chr($decimals).str_repeat("\0", 14);
    }

    $record = ' '.str_pad($tmp, 20).str_pad('HC-1', 20).str_pad('BOSCH', 20).str_pad('12345', 20);
    file_put_contents($path, $header."\x0D".$record."\x1A");
}

function createOemDbf(string $path, string $parent = 'ROOT'): void
{
    $fields = [
        ['INVOICE', 'C', 20, 0],
        ['PARENT', 'C', 80, 0],
        ['OEM', 'C', 20, 0],
        ['BRAND', 'C', 20, 0],
        ['TYPE_RUS', 'C', 40, 0],
    ];
    $headerLength = 32 + (32 * count($fields)) + 1;
    $recordLength = 1 + array_sum(array_column($fields, 2));
    $header = chr(0x03).pack('CCC', 126, 8, 30).pack('Vvv', 1, $headerLength, $recordLength).str_repeat("\0", 20);

    foreach ($fields as [$fieldName, $type, $length, $decimals]) {
        $header .= str_pad($fieldName, 11, "\0").$type.str_repeat("\0", 4).chr($length).chr($decimals).str_repeat("\0", 14);
    }

    $record = ' '.str_pad('INV-1', 20).str_pad($parent, 80).str_pad('OEM-1', 20).str_pad('BOSCH', 20).str_pad('Generator', 40);
    file_put_contents($path, $header."\x0D".$record."\x1A");
}

function createDetailDbf(string $path, string $photo): void
{
    $fields = [
        ['CODE', 'N', 10, 0],
        ['TYPE', 'C', 40, 0],
        ['FOTO', 'C', 100, 0],
        ['INVOICE', 'C', 20, 0],
        ['TYPEC', 'C', 40, 0],
        ['FIRMS', 'C', 20, 0],
        ['ACODE', 'N', 10, 0],
    ];
    $headerLength = 32 + (32 * count($fields)) + 1;
    $recordLength = 1 + array_sum(array_column($fields, 2));
    $header = chr(0x03).pack('CCC', 126, 8, 30).pack('Vvv', 1, $headerLength, $recordLength).str_repeat("\0", 20);

    foreach ($fields as [$fieldName, $type, $length, $decimals]) {
        $header .= str_pad($fieldName, 11, "\0").$type.str_repeat("\0", 4).chr($length).chr($decimals).str_repeat("\0", 14);
    }

    $record = ' '
        .str_pad('131586', 10, ' ', STR_PAD_LEFT)
        .str_pad('Relay', 40)
        .str_pad($photo, 100)
        .str_pad('131586', 20)
        .str_pad('Starter relay', 40)
        .str_pad('CARGO', 20)
        .str_pad('1', 10, ' ', STR_PAD_LEFT);
    file_put_contents($path, $header."\x0D".$record."\x1A");
}

/** @param list<string> $brands */
function createBrandAssDbf(string $path, array $brands): void
{
    $fields = [['FIRMS', 'C', 40, 0]];
    $headerLength = 32 + (32 * count($fields)) + 1;
    $recordLength = 41;
    $header = chr(0x03).pack('CCC', 126, 8, 30).pack('Vvv', count($brands), $headerLength, $recordLength).str_repeat("\0", 20);
    $header .= str_pad('FIRMS', 11, "\0").'C'.str_repeat("\0", 4).chr(40).chr(0).str_repeat("\0", 14);
    $records = implode('', array_map(static fn (string $brand): string => ' '.str_pad($brand, 40), $brands));

    file_put_contents($path, $header."\x0D".$records."\x1A");
}

it('inserts, updates, and skips unchanged DBF records', function (): void {
    $directory = sys_get_temp_dir().'/ami_dbf_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/OEMS_OUT.DBF';

    try {
        createOemDbf($path, 'Original');

        $this->artisan('dbf:sync', ['--file' => ['OEMS_OUT.DBF'], '--source' => $directory])
            ->assertSuccessful();
        expect(DB::table('oems')->where('dt_invoice', 'INV-1')->value('dt_parent'))->toBe('Original')
            ->and(DB::table('oems')->where('dt_invoice', 'INV-1')->count())->toBe(1);

        createOemDbf($path, 'Changed');

        $this->artisan('dbf:sync', ['--file' => ['OEMS_OUT.DBF'], '--source' => $directory])
            ->assertSuccessful();
        expect(DB::table('oems')->where('dt_invoice', 'INV-1')->value('dt_parent'))->toBe('Changed')
            ->and(DB::table('oems')->where('dt_invoice', 'INV-1')->count())->toBe(1);

        $this->artisan('dbf:sync', ['--file' => ['OEMS_OUT.DBF'], '--source' => $directory])
            ->expectsOutputToContain('unchanged, skipped')
            ->assertSuccessful();
        expect(DB::table('dbf_import_runs')->where('status', 'skipped')->count())->toBe(1);
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});

it('rebuilds a unique brand list from the FIRMS column in ASS DBF', function (): void {
    $directory = sys_get_temp_dir().'/ami_brand_sync_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/ASS.DBF';
    $legacyBrand = iconv('UTF-8', 'CP866', 'БАТЭ');
    expect($legacyBrand)->not->toBeFalse();

    try {
        createBrandAssDbf($path, ['CARGO', 'BOSCH', 'cargo', '', $legacyBrand]);
        DB::table('firm')->insert(['fr_name' => 'OLD BRAND', 'created_at' => now(), 'updated_at' => now()]);

        $this->artisan('dbf:sync-brands', ['--source' => $directory])
            ->expectsOutputToContain('3 unique values')
            ->assertSuccessful();

        expect(DB::table('firm')->count())->toBe(3)
            ->and(DB::table('firm')->where('fr_name', 'CARGO')->count())->toBe(1)
            ->and(DB::table('firm')->where('fr_name', 'BOSCH')->count())->toBe(1)
            ->and(DB::table('firm')->where('fr_name', 'БАТЭ')->count())->toBe(1)
            ->and(DB::table('firm')->where('fr_name', 'OLD BRAND')->count())->toBe(0);

        $this->artisan('dbf:sync-brands', ['--source' => $directory])->assertSuccessful();
        expect(DB::table('firm')->count())->toBe(3);
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});

it('does not clear brands when ASS DBF has no FIRMS column', function (): void {
    $directory = sys_get_temp_dir().'/ami_invalid_brand_sync_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/ASS.DBF';

    try {
        createOemDbf($path);
        DB::table('firm')->insert(['fr_name' => 'EXISTING', 'created_at' => now(), 'updated_at' => now()]);

        $this->artisan('dbf:sync-brands', ['--source' => $directory])
            ->expectsOutputToContain('FIRMS column was not found')
            ->assertFailed();

        $this->assertDatabaseHas('firm', ['fr_name' => 'EXISTING']);
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});

it('does not include FIRMS DBF in the regular importer', function (): void {
    expect(DbfImporter::FILES)->not->toContain('FIRMS.DBF');
});

it('updates the product photo reference when ASS DBF changes', function (): void {
    $directory = sys_get_temp_dir().'/ami_detail_photo_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/ASS.DBF';

    try {
        createDetailDbf($path, 'old-product-photo');
        $this->artisan('dbf:sync', ['--file' => ['ASS.DBF'], '--source' => $directory])->assertSuccessful();
        $this->assertDatabaseHas('detail', ['dt_code' => 131586, 'dt_foto' => 'old-product-photo']);

        createDetailDbf($path, 'new-product-photo');
        $this->artisan('dbf:sync', ['--file' => ['ASS.DBF'], '--source' => $directory])->assertSuccessful();

        expect(DB::table('detail')->where('dt_code', 131586)->count())->toBe(1);
        $this->assertDatabaseHas('detail', ['dt_code' => 131586, 'dt_foto' => 'new-product-photo']);
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});

it('makes an ASS DBF product searchable without a matching OEM import', function (): void {
    $directory = sys_get_temp_dir().'/ami_detail_search_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/ASS.DBF';

    try {
        createDetailDbf($path, 'product-photo');
        $this->artisan('dbf:sync', ['--file' => ['ASS.DBF'], '--source' => $directory])->assertSuccessful();

        $this->assertDatabaseHas('detail', ['dt_invoice' => '131586']);
        $this->assertDatabaseMissing('oems', ['dt_invoice' => '131586']);

        $this->getJson('/api/v1/catalog/search?searchQ=131586')
            ->assertOk()
            ->assertJsonPath('data.details.total', 1)
            ->assertJsonPath('data.details.data.0.dt_code', '131586')
            ->assertJsonPath('data.details.data.0.dt_firm', 'CARGO');
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});

it('stores a human-readable quality snapshot for incomplete product fields', function (): void {
    $directory = sys_get_temp_dir().'/ami_detail_quality_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/ASS.DBF';

    try {
        createDetailDbf($path, 'product-photo');

        $this->artisan('dbf:sync', ['--file' => ['ASS.DBF'], '--source' => $directory])->assertSuccessful();

        $run = DB::table('dbf_import_runs')->where('filename', 'ASS.DBF')->latest('id')->first();
        expect($run)->not->toBeNull()
            ->and($run->status)->toBe('completed')
            ->and($run->issues_count)->toBe(1);

        $this->assertDatabaseHas('dbf_import_issues', [
            'run_id' => $run->id,
            'detail_code' => 131586,
            'invoice' => '131586',
            'missing_internal_code' => false,
            'missing_invoice' => false,
            'missing_cargo' => true,
            'missing_oem' => true,
            'missing_photo' => false,
        ]);
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});

it('shows import history and incomplete positions to administrators', function (): void {
    $directory = sys_get_temp_dir().'/ami_detail_admin_report_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/ASS.DBF';

    try {
        createDetailDbf($path, '');
        $this->artisan('dbf:sync', ['--file' => ['ASS.DBF'], '--source' => $directory])->assertSuccessful();
        $admin = User::factory()->create(['isAdmin' => true, 'approved' => true]);

        $this->actingAs($admin)
            ->getJson('/api/v1/admin/imports')
            ->assertOk()
            ->assertJsonPath('data.runs.data.0.filename', 'ASS.DBF')
            ->assertJsonPath('data.runs.data.0.status', 'completed')
            ->assertJsonPath('data.runs.path', '/admin/resource/imports')
            ->assertJsonPath('data.summary.positions', 1)
            ->assertJsonPath('data.summary.cargo', 1)
            ->assertJsonPath('data.summary.oem', 1)
            ->assertJsonPath('data.summary.photo', 1)
            ->assertJsonPath('data.issues.data.0.detail_code', 131586)
            ->assertJsonPath('data.issues.path', '/admin/resource/imports')
            ->assertJsonPath('data.issues.data.0.missing_fields.0', 'Код CARGO');
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});

it('records a failed import when its source file cannot be found', function (): void {
    $directory = sys_get_temp_dir().'/ami_missing_dbf_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);

    try {
        $this->artisan('dbf:sync', ['--file' => ['ALT_CZ.DBF'], '--source' => $directory])->assertFailed();

        $run = DB::table('dbf_import_runs')->where('filename', 'ALT_CZ.DBF')->latest('id')->first();
        expect($run)->not->toBeNull()
            ->and($run->status)->toBe('failed')
            ->and($run->finished_at)->not->toBeNull()
            ->and($run->error)->not->toBeEmpty();
    } finally {
        @rmdir($directory);
    }
});

it('synchronizes product images even when ASS DBF is unchanged', function (): void {
    Storage::fake('images');
    $directory = sys_get_temp_dir().'/ami_detail_image_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $dbfPath = $directory.'/ASS.DBF';
    $imagePath = $directory.'/NEW-PRODUCT-PHOTO.JPG';

    try {
        createDetailDbf($dbfPath, 'NEW-PRODUCT-PHOTO');
        $this->artisan('dbf:sync', ['--file' => ['ASS.DBF'], '--source' => $directory])->assertSuccessful();
        Storage::disk('images')->assertMissing('new-product-photo.jpg');

        file_put_contents($imagePath, 'image bytes');
        $this->artisan('dbf:sync', ['--file' => ['ASS.DBF'], '--source' => $directory])
            ->expectsOutputToContain('unchanged, skipped; 1 images synchronized')
            ->assertSuccessful();

        Storage::disk('images')->assertExists('new-product-photo.jpg');
    } finally {
        @unlink($dbfPath);
        @unlink($imagePath);
        @rmdir($directory);
    }
});

it('converts legacy CP866 product image filenames to safe UTF-8 paths', function (): void {
    Storage::fake('images');
    $directory = sys_get_temp_dir().'/ami_legacy_image_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $dbfPath = $directory.'/ASS.DBF';
    $utf8Photo = '2Я-3708150';
    $legacyPhoto = iconv('UTF-8', 'CP866', $utf8Photo);
    expect($legacyPhoto)->not->toBeFalse();
    $legacyImagePath = $directory.'/'.$legacyPhoto.'.JPG';

    try {
        createDetailDbf($dbfPath, $legacyPhoto);
        file_put_contents($legacyImagePath, 'image bytes');

        $this->artisan('dbf:sync', ['--file' => ['ASS.DBF'], '--source' => $directory])
            ->assertSuccessful();

        $safeImagePath = '2я-3708150.jpg';
        $this->assertDatabaseHas('detail', ['dt_code' => 131586, 'dt_foto' => $utf8Photo]);
        Storage::disk('images')->assertExists($safeImagePath);
        expect(app(ProductImageService::class)->getImageUrl($utf8Photo))
            ->toBe(url('/storage/images/'.$safeImagePath));
    } finally {
        @unlink($dbfPath);
        @unlink($legacyImagePath);
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

it('converts non UTF-8 values in compatibility code columns', function (): void {
    $directory = sys_get_temp_dir().'/ami_alt_encoding_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/ALT_CZ.DBF';

    try {
        $cp866 = iconv('UTF-8', 'CP866', 'МУФТА');
        expect($cp866)->not->toBeFalse();
        createCompactAltDbf($path, $cp866);

        $this->artisan('dbf:sync', ['--file' => ['ALT_CZ.DBF'], '--source' => $directory])->assertSuccessful();
        $this->assertDatabaseHas('alt_cz', ['tmp' => 'МУФТА', 'dt_code' => '12345']);
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});

it('discovers a DBF inside one of several source ZIP archives', function (): void {
    $directory = sys_get_temp_dir().'/ami_zip_dbf_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $dbfPath = $directory.'/OEMS_OUT.DBF';
    $zipPath = $directory.'/oems.zip';

    try {
        createOemDbf($dbfPath, 'From archive');
        $archive = new ZipArchive;
        expect($archive->open($zipPath, ZipArchive::CREATE))->toBeTrue();
        $archive->addFile($dbfPath, 'nested/OEMS_OUT.DBF');
        $archive->close();
        unlink($dbfPath);

        $this->artisan('dbf:sync', ['--file' => ['OEMS_OUT.DBF'], '--source' => $directory])->assertSuccessful();
        $this->assertDatabaseHas('oems', ['dt_invoice' => 'INV-1', 'dt_parent' => 'From archive']);
    } finally {
        @unlink($dbfPath);
        @unlink($zipPath);
        @rmdir($directory);
    }
});

it('imports the OEM source from its OEMS_OUT filename case-insensitively', function (): void {
    $directory = sys_get_temp_dir().'/ami_oem_dbf_test_'.bin2hex(random_bytes(6));
    mkdir($directory, 0755, true);
    $path = $directory.'/oems_out.dbf';

    try {
        $parent = 'A manufacturer name longer than fifteen characters';
        createOemDbf($path, $parent);

        $this->artisan('dbf:sync', ['--file' => ['OEMS.DBF'], '--source' => $directory])->assertSuccessful();

        $this->assertDatabaseHas('oems', [
            'dt_invoice' => 'INV-1',
            'dt_parent' => $parent,
            'dt_oem' => 'OEM-1',
            'fr_code' => 'BOSCH',
            'dt_typec' => 'Generator',
        ]);
        $this->assertDatabaseHas('dbf_import_files', ['filename' => 'OEMS_OUT.DBF']);
    } finally {
        @unlink($path);
        @rmdir($directory);
    }
});
