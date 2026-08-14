<?php

namespace App\Support;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SchemaSqlExporter
{
    public static bool $running = false;

    private const EXCLUDED_TABLES = ['migrations', 'sqlite_sequence'];

    private string $connection = 'schema_export';

    public function export(string $outputPath): string
    {
        self::$running = true;

        try {
            return $this->writeExport($outputPath);
        } finally {
            self::$running = false;
        }
    }

    private function writeExport(string $outputPath): string
    {
        $sqlitePath = database_path('schema/.export.sqlite');

        if (is_file($sqlitePath)) {
            @unlink($sqlitePath);
        }

        config([
            "database.connections.{$this->connection}" => [
                'driver' => 'sqlite',
                'database' => $sqlitePath,
                'prefix' => '',
                'foreign_key_constraints' => true,
            ],
        ]);

        DB::purge($this->connection);

        Artisan::call('migrate:fresh', [
            '--database' => $this->connection,
            '--force' => true,
        ]);

        $schema = Schema::connection($this->connection);
        $tables = collect($schema->getTables())
            ->pluck('name')
            ->reject(fn (string $name) => in_array($name, self::EXCLUDED_TABLES, true))
            ->sort()
            ->values()
            ->all();

        $tableDefinitions = [];
        $columnSync = [];
        $indexSync = [];
        $foreignKeySync = [];

        foreach ($tables as $table) {
            $columns = $schema->getColumns($table);
            $indexes = $schema->getIndexes($table);
            $foreignKeys = $schema->getForeignKeys($table);

            $tableDefinitions[] = $this->buildCreateTable($table, $columns, $indexes, $foreignKeys);

            foreach ($columns as $column) {
                if (($column['auto_increment'] ?? false) === true) {
                    continue;
                }

                $columnSync[] = $this->buildColumnSync($table, $column);
            }

            foreach ($indexes as $index) {
                if (($index['primary'] ?? false) === true) {
                    continue;
                }

                $indexSync[] = $this->buildIndexSync($table, $index);
            }

            foreach ($foreignKeys as $foreignKey) {
                $foreignKeySync[] = $this->buildForeignKeySync($table, $foreignKey);
            }
        }

        $sql = implode("\n", [
            $this->header(),
            $this->helperProcedures(),
            '-- ============================================================',
            '-- CREATE TABLES (skipped when table already exists)',
            '-- ============================================================',
            '',
            implode("\n\n", $tableDefinitions),
            '',
            '-- ============================================================',
            '-- SYNC MISSING COLUMNS ON EXISTING TABLES',
            '-- ============================================================',
            '',
            implode("\n", $columnSync),
            '',
            '-- ============================================================',
            '-- SYNC MISSING INDEXES',
            '-- ============================================================',
            '',
            implode("\n", $indexSync),
            '',
            '-- ============================================================',
            '-- SYNC MISSING FOREIGN KEYS',
            '-- ============================================================',
            '',
            implode("\n", $foreignKeySync),
            '',
            'DROP PROCEDURE IF EXISTS sync_column;',
            'DROP PROCEDURE IF EXISTS sync_index;',
            'DROP PROCEDURE IF EXISTS sync_foreign_key;',
            '',
            'SET FOREIGN_KEY_CHECKS = 1;',
            '',
        ]);

        if (! is_dir(dirname($outputPath))) {
            mkdir(dirname($outputPath), 0755, true);
        }

        file_put_contents($outputPath, $sql);

        if (is_file($sqlitePath)) {
            @unlink($sqlitePath);
        }

        return $outputPath;
    }

    private function header(): string
    {
        $generated = now()->toDateTimeString();

        return <<<SQL
-- Leyla Safari Tours — full MySQL schema (idempotent)
-- Generated: {$generated}
-- Source: database/migrations (via php artisan schema:export-sql)
--
-- Safe to re-run on production:
--   • Creates tables that do not exist
--   • Adds missing columns, indexes, and foreign keys
--   • Does NOT drop columns or data
--
-- Import in phpMyAdmin or: mysql -u USER -p DATABASE < database/schema/full-schema.sql

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

SQL;
    }

    private function helperProcedures(): string
    {
        return <<<'SQL'
DROP PROCEDURE IF EXISTS sync_column;
DROP PROCEDURE IF EXISTS sync_index;
DROP PROCEDURE IF EXISTS sync_foreign_key;

DELIMITER $$

CREATE PROCEDURE sync_column(
    IN p_table VARCHAR(64),
    IN p_column VARCHAR(64),
    IN p_definition TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_table
          AND COLUMN_NAME = p_column
    ) THEN
        SET @sql = CONCAT(
            'ALTER TABLE `', REPLACE(p_table, '`', '``'),
            '` ADD COLUMN `', REPLACE(p_column, '`', '``'),
            '` ', p_definition
        );
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

CREATE PROCEDURE sync_index(
    IN p_table VARCHAR(64),
    IN p_index VARCHAR(64),
    IN p_definition TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_table
          AND INDEX_NAME = p_index
    ) THEN
        SET @sql = CONCAT(
            'ALTER TABLE `', REPLACE(p_table, '`', '``'),
            '` ADD ', p_definition
        );
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

CREATE PROCEDURE sync_foreign_key(
    IN p_table VARCHAR(64),
    IN p_constraint VARCHAR(64),
    IN p_column VARCHAR(64),
    IN p_ref_table VARCHAR(64),
    IN p_ref_column VARCHAR(64),
    IN p_on_delete VARCHAR(32)
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM information_schema.TABLE_CONSTRAINTS tc
        INNER JOIN information_schema.KEY_COLUMN_USAGE kcu
            ON tc.CONSTRAINT_SCHEMA = kcu.CONSTRAINT_SCHEMA
           AND tc.CONSTRAINT_NAME = kcu.CONSTRAINT_NAME
           AND tc.TABLE_NAME = kcu.TABLE_NAME
        WHERE tc.CONSTRAINT_TYPE = 'FOREIGN KEY'
          AND tc.TABLE_SCHEMA = DATABASE()
          AND tc.TABLE_NAME = p_table
          AND kcu.COLUMN_NAME = p_column
          AND kcu.REFERENCED_TABLE_NAME = p_ref_table
          AND kcu.REFERENCED_COLUMN_NAME = p_ref_column
    ) THEN
        SET @sql = CONCAT(
            'ALTER TABLE `', REPLACE(p_table, '`', '``'),
            '` ADD CONSTRAINT `', REPLACE(p_constraint, '`', '``'),
            '` FOREIGN KEY (`', REPLACE(p_column, '`', '``'),
            '`) REFERENCES `', REPLACE(p_ref_table, '`', '``'),
            '` (`', REPLACE(p_ref_column, '`', '``'),
            '`) ON DELETE ', p_on_delete
        );
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;

SQL;
    }

    /**
     * @param  array<int, array<string, mixed>>  $columns
     * @param  array<int, array<string, mixed>>  $indexes
     * @param  array<int, array<string, mixed>>  $foreignKeys
     */
    private function buildCreateTable(string $table, array $columns, array $indexes, array $foreignKeys): string
    {
        $lines = [];

        foreach ($columns as $column) {
            $lines[] = '  '.$this->formatColumnDefinition($column);
        }

        foreach ($indexes as $index) {
            if (($index['primary'] ?? false) === true) {
                $lines[] = '  PRIMARY KEY (`'.implode('`, `', $index['columns']).'`)';
                continue;
            }

            if (($index['unique'] ?? false) === true) {
                $lines[] = '  UNIQUE KEY `'.$index['name'].'` (`'.implode('`, `', $index['columns']).'`)';
                continue;
            }

            $lines[] = '  KEY `'.$index['name'].'` (`'.implode('`, `', $index['columns']).'`)';
        }

        foreach ($foreignKeys as $foreignKey) {
            $column = $foreignKey['columns'][0];
            $refTable = $foreignKey['foreign_table'];
            $refColumn = $foreignKey['foreign_columns'][0];
            $onDelete = strtoupper(str_replace('_', ' ', $foreignKey['on_delete'] ?? 'restrict'));
            $constraintName = $foreignKey['name'] ?: "{$table}_{$column}_foreign";

            $lines[] = '  CONSTRAINT `'.$constraintName.'` FOREIGN KEY (`'.$column.'`)'
                .' REFERENCES `'.$refTable.'` (`'.$refColumn.'`)'
                .' ON DELETE '.$onDelete;
        }

        return 'CREATE TABLE IF NOT EXISTS `'.$table."` (\n"
            .implode(",\n", $lines)
            ."\n) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
    }

    private function buildColumnSync(string $table, array $column): string
    {
        $definition = $this->formatColumnDefinition($column, forAlter: true);

        return "CALL sync_column('{$table}', '{$column['name']}', '".$this->escapeSqlString($definition)."');";
    }

    private function buildIndexSync(string $table, array $index): string
    {
        $type = ($index['unique'] ?? false) ? 'UNIQUE INDEX' : 'INDEX';
        $columns = '`'.implode('`, `', $index['columns']).'`';
        $definition = "{$type} `{$index['name']}` ({$columns})";

        return "CALL sync_index('{$table}', '{$index['name']}', '".$this->escapeSqlString($definition)."');";
    }

    private function buildForeignKeySync(string $table, array $foreignKey): string
    {
        $column = $foreignKey['columns'][0];
        $refTable = $foreignKey['foreign_table'];
        $refColumn = $foreignKey['foreign_columns'][0];
        $onDelete = strtoupper(str_replace('_', ' ', $foreignKey['on_delete'] ?? 'restrict'));
        $name = $foreignKey['name'] ?: "{$table}_{$column}_foreign";

        return "CALL sync_foreign_key('{$table}', '{$name}', '{$column}', '{$refTable}', '{$refColumn}', '{$onDelete}');";
    }

    private function formatColumnDefinition(array $column, bool $forAlter = false): string
    {
        $name = $column['name'];
        $sqlType = $this->mapColumnType($column);
        $nullable = ($column['nullable'] ?? false) ? 'NULL' : 'NOT NULL';
        $default = $this->formatDefault($column, $sqlType);
        $autoIncrement = ($column['auto_increment'] ?? false) ? ' AUTO_INCREMENT' : '';

        if ($forAlter && ($column['auto_increment'] ?? false)) {
            $autoIncrement = '';
        }

        return '`'.$name.'` '.$sqlType.' '.$nullable.$default.$autoIncrement;
    }

    private function mapColumnType(array $column): string
    {
        $name = (string) $column['name'];
        $type = strtolower((string) ($column['type_name'] ?? $column['type'] ?? 'varchar'));
        $fullType = strtolower((string) ($column['type'] ?? ''));

        if (str_contains($type, 'int') || $type === 'integer') {
            if (($column['auto_increment'] ?? false) || $name === 'id') {
                return 'BIGINT UNSIGNED';
            }

            if (str_ends_with($name, '_id')) {
                return 'BIGINT UNSIGNED';
            }

            if (str_contains($fullType, 'tinyint')) {
                return 'TINYINT(1)';
            }

            if (str_contains($fullType, 'smallint') || in_array($name, ['day_number', 'group_size'], true)) {
                return 'SMALLINT UNSIGNED';
            }

            if (in_array($name, ['sort_order', 'view_count', 'last_activity', 'expiration', 'attempts', 'reserved_at', 'available_at', 'duration_hours'], true)) {
                return 'INT UNSIGNED';
            }

            return 'INT';
        }

        if ($type === 'varchar' || str_contains($fullType, 'varchar')) {
            if (preg_match('/varchar\((\d+)\)/', $fullType, $matches)) {
                return 'VARCHAR('.$matches[1].')';
            }

            return 'VARCHAR(255)';
        }

        if ($type === 'text' || str_contains($fullType, 'text')) {
            if (in_array($name, ['description', 'long_description', 'content', 'payload', 'exception', 'failed_job_ids'], true)) {
                return 'LONGTEXT';
            }

            if (str_contains($fullType, 'longtext')) {
                return 'LONGTEXT';
            }

            if (str_contains($fullType, 'mediumtext')) {
                return 'MEDIUMTEXT';
            }

            return 'TEXT';
        }

        if ($type === 'float' || str_contains($fullType, 'decimal') || $type === 'numeric') {
            if ($name === 'total_amount') {
                return 'DECIMAL(12,2)';
            }

            if (preg_match('/decimal\((\d+),\s*(\d+)\)/', $fullType, $matches)) {
                return 'DECIMAL('.$matches[1].','.$matches[2].')';
            }

            if (in_array($name, ['latitude', 'longitude'], true)) {
                return 'DECIMAL(10,7)';
            }

            if (in_array($name, ['starting_price', 'early_bird_price', 'regular_price'], true)) {
                return 'DECIMAL(10,2)';
            }

            return 'DECIMAL(10,2)';
        }

        if ($type === 'boolean' || str_contains($fullType, 'tinyint(1)')) {
            return 'TINYINT(1)';
        }

        if ($type === 'datetime' || $type === 'timestamp') {
            return 'TIMESTAMP';
        }

        if ($type === 'date') {
            return 'DATE';
        }

        if ($type === 'json') {
            return 'JSON';
        }

        return strtoupper($type);
    }

    private function normalizeDefault(mixed $default): mixed
    {
        if (! is_string($default)) {
            return $default;
        }

        if (preg_match("/^'(.*)'$/s", $default, $matches)) {
            return str_replace("''", "'", $matches[1]);
        }

        return $default;
    }

    private function formatDefault(array $column, string $sqlType): string
    {
        if (($column['auto_increment'] ?? false) === true) {
            return '';
        }

        if (! array_key_exists('default', $column) || $column['default'] === null) {
            return '';
        }

        $default = $this->normalizeDefault($column['default']);

        if ($default === null || $default === 'NULL') {
            return ' DEFAULT NULL';
        }

        if (is_bool($default)) {
            return ' DEFAULT '.($default ? '1' : '0');
        }

        if (is_numeric($default) && ! str_starts_with($sqlType, 'VARCHAR') && ! str_starts_with($sqlType, 'TEXT') && ! str_starts_with($sqlType, 'LONGTEXT')) {
            return ' DEFAULT '.$default;
        }

        if (in_array(strtoupper((string) $default), ['CURRENT_TIMESTAMP', 'CURRENT_DATE'], true)) {
            return ' DEFAULT '.strtoupper((string) $default);
        }

        return " DEFAULT '".$this->escapeSqlString((string) $default)."'";
    }

    private function escapeSqlString(string $value): string
    {
        return str_replace("'", "''", $value);
    }
}
