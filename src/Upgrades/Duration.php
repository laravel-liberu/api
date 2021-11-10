<?php

namespace LaravelEnso\Api\Upgrades;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use LaravelEnso\Upgrade\Contracts\MigratesData;
use LaravelEnso\Upgrade\Contracts\MigratesPostDataMigration;
use LaravelEnso\Upgrade\Contracts\MigratesTable;
use LaravelEnso\Upgrade\Helpers\Table;

class Duration implements MigratesTable, MigratesData, MigratesPostDataMigration
{
    public function isMigrated(): bool
    {
        return Table::hasColumn('api_logs', 'duration');
    }

    public function migrateTable(): void
    {
        Schema::table('api_logs', fn (Blueprint $table) => $table
            ->decimal('duration', 5, 2)->after('type')->nullable());
    }

    public function migrateData(): void
    {
        DB::table('api_logs')->update(['duration' => 0]);
    }

    public function migratePostDataMigration(): void
    {
        Schema::table('api_logs', fn (Blueprint $table) => $table
            ->decimal('duration', 5, 2)->nullable(false)->change());
    }
}
