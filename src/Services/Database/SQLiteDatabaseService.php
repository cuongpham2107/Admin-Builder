<?php

namespace CuongPham2107\AdminBuilder\Services\Database;

use CuongPham2107\AdminBuilder\Services\Interface\DatabaseServiceInterface;

class SQLiteDatabaseService implements DatabaseServiceInterface
{
    public function createTable(string $tableName, array $columns): array
    {
        // Implement createTable logic here
        return [];
    }

    public function getColumns(string $tableName): array
    {
        // Implement getColumns logic here
        return [];
    }

    public function dropColumn(string $tableName, string $columnName): array
    {
        // Implement dropColumn logic here
        return [];
    }

    public function addColumn(string $tableName, array $column): array
    {
        // Implement addColumn logic here
        return [];
    }

    public function updateColumn(string $tableName, string $oldColumnName, array $newColumn): array
    {
        // Implement updateColumn logic here
        return [];
    }
}
