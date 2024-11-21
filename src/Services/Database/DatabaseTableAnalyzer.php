<?php

namespace CuongPham2107\AdminBuilder\Services\Database;

use CuongPham2107\AdminBuilder\Services\Interface\DatabaseServiceInterface;

class DatabaseTableAnalyzer
{
    public function __construct(private DatabaseServiceInterface $dbService) {}

    public function createTable(string $tableName, array $columns): array
    {
        return $this->dbService->createTable($tableName, $columns);
    }

    public function getColumns(string $tableName): array
    {
        return $this->dbService->getColumns($tableName);
    }

    public function dropColumn(string $tableName, string $columnName): array
    {
        return $this->dbService->dropColumn($tableName, $columnName);
    }

    public function addColumn(string $tableName, array $column): array
    {
        return $this->dbService->addColumn($tableName, $column);
    }

    public function updateColumn(string $tableName, string $oldColumnName, array $newColumn): array
    {
        return $this->dbService->updateColumn($tableName, $oldColumnName, $newColumn);
    }
}
