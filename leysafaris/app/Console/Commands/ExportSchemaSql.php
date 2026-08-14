<?php

namespace App\Console\Commands;

use App\Support\SchemaSqlExporter;
use Illuminate\Console\Command;

class ExportSchemaSql extends Command
{
    protected $signature = 'schema:export-sql
                            {--path= : Output path (default: database/schema/full-schema.sql)}';

    protected $description = 'Export idempotent MySQL schema SQL from migrations';

    public function handle(SchemaSqlExporter $exporter): int
    {
        $path = $this->option('path') ?: database_path('schema/full-schema.sql');

        try {
            $written = $exporter->export($path);
        } catch (\Throwable $exception) {
            $this->error('Schema export failed: '.$exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Schema SQL exported to: '.$written);
        $this->line('Import in phpMyAdmin or run against your production database.');

        return self::SUCCESS;
    }
}
