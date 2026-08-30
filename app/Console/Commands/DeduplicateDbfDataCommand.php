<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeduplicateDbfDataCommand extends Command
{
    protected $signature = 'dbf:deduplicate {--apply : Delete duplicates after showing the report}';

    protected $description = 'Report or safely remove duplicates created by the legacy DBF importer';

    public function handle(): int
    {
        $definitions = [
            'detail' => ['dt_code'],
            'oems' => ['dt_invoice', 'dt_oem'],
            'alt_cz' => ['tmp', 'hcparts', 'brand', 'typec', 'dt_brand', 'dt_code'],
            'roz_cz' => ['tmp', 'hcparts', 'brand', 'typec', 'dt_brand', 'dt_code'],
        ];
        $report = [];

        foreach ($definitions as $table => $columns) {
            $groups = DB::query()->fromSub(
                DB::table($table)->select($columns)->groupBy($columns)->havingRaw('COUNT(*) > 1'),
                'duplicates',
            )->count();
            $report[] = [$table, $groups];
        }

        $this->table(['Table', 'Duplicate groups'], $report);
        if (! $this->option('apply')) {
            $this->comment('Dry run only. Back up the database, then pass --apply to remove the reported duplicates.');

            return self::SUCCESS;
        }

        $removed = 0;
        foreach ($definitions as $table => $columns) {
            $groups = DB::table($table)->select($columns)->groupBy($columns)->havingRaw('COUNT(*) > 1')->cursor();
            foreach ($groups as $group) {
                $removed += $table === 'detail'
                    ? $this->mergeDetailGroup((array) $group)
                    : $this->removeSimpleGroup($table, $columns, (array) $group);
            }
        }

        $this->info("Removed {$removed} duplicate rows.");

        return self::SUCCESS;
    }

    private function mergeDetailGroup(array $identity): int
    {
        return DB::transaction(function () use ($identity): int {
            $ids = DB::table('detail')->where($identity)
                ->orderByRaw('dbf_source_key IS NULL')
                ->orderBy('dt_id')
                ->pluck('dt_id');
            $canonicalId = (int) $ids->shift();
            $removed = 0;

            foreach ($ids as $duplicateId) {
                foreach (DB::table('cart_item')->where('dt_id', $duplicateId)->get() as $duplicateCartItem) {
                    $canonicalCartItem = DB::table('cart_item')
                        ->where('cart_id', $duplicateCartItem->cart_id)
                        ->where('dt_id', $canonicalId)
                        ->first();

                    if ($canonicalCartItem !== null) {
                        DB::table('cart_item')->where('id', $canonicalCartItem->id)
                            ->increment('quantity', $duplicateCartItem->quantity);
                        DB::table('cart_item')->where('id', $duplicateCartItem->id)->delete();
                    } else {
                        DB::table('cart_item')->where('id', $duplicateCartItem->id)->update(['dt_id' => $canonicalId]);
                    }
                }

                DB::table('order_item')->where('detail_id', $duplicateId)->update(['detail_id' => $canonicalId]);
                $removed += DB::table('detail')->where('dt_id', $duplicateId)->delete();
            }

            return $removed;
        });
    }

    private function removeSimpleGroup(string $table, array $columns, array $identity): int
    {
        return DB::transaction(function () use ($table, $columns, $identity): int {
            $key = $table === 'oems' ? 'id' : 'id';
            $ids = DB::table($table)->where(array_intersect_key($identity, array_flip($columns)))
                ->orderByRaw('dbf_source_key IS NULL')
                ->orderBy($key)
                ->pluck($key);
            $ids->shift();

            return $ids->isEmpty() ? 0 : DB::table($table)->whereIn($key, $ids)->delete();
        });
    }
}
