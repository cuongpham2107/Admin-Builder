<?php

namespace CuongPham2107\AdminBuilder\Services\Interface;


interface DatabaseServiceInterface
{
    public function createTable(string $tableName, array $columns): array;
    public function getColumns(string $tableName): array;
    public function addColumn(string $tableName, array $column): array;
    public function updateColumn(string $tableName, string $oldColumnName, array $newColumn): array;
    public function dropColumn(string $tableName, string $columnName): array;

}
