<?php

namespace LaravelEnso\Api\Upgrades;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use LaravelEnso\Upgrade\Contracts\MigratesTable;
use LaravelEnso\Upgrade\Helpers\Table;

class Duration implements MigratesTable
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
}
