<?php

namespace App\Support;

use Illuminate\Support\Facades\Schema;

class DbSchema
{
    public static function hasTable(string $table): bool
    {
        try {
            return Schema::hasTable($table);
        } catch (\Throwable) {
            return false;
        }
    }
}
