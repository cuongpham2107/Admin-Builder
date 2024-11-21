<?php

namespace CuongPham2107\AdminBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Schema;
use Sushi\Sushi;

class DataTable extends Model
{
    use Sushi;
    protected $fillable = ['name', 'table_column'];
    /**
     * Model Rows
     *
     * @return void
     */
    public function getRows(): array
    {
         
//         $databaseTableAnalyzer = app(DatabaseServiceInterface::class);

//         $excludeTables = ['cache', 'cache_locks', 'failed_jobs', 'job_batches', 'jobs', 'migrations', 'password_reset_tokens', 'sessions'];
//         $tables = Arr::where(Schema::getTables(), function ($item, $index) use ($excludeTables) {
//             return !in_array($item['name'], $excludeTables);
//         });
//         $tables = array_values($tables);
//         $tables = array_map(
//   function ($item) use($databaseTableAnalyzer): mixed {
//                 $columns = $databaseTableAnalyzer->getColumns($item['name']);
//                 $item['table_column'] =  json_encode($columns);
//                 return $item;
//             }, $tables);
//             // dd(json_decode($tables[1]['table_column']));
//         return $tables;
        return [];

    }

    protected $casts = [
        'table_column' => 'array',
    ];
}
