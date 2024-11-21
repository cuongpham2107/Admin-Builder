<?php

namespace CuongPham2107\AdminBuilder\Models;

use Illuminate\Database\Eloquent\Model;

class DataRow extends Model
{
    protected $table = 'data_rows';
    protected $fillable = [
        'data_type_id',
        'field',
        'type',
        'display_name',
        'required',
        'browse',
        'read',
        'edit',
        'add',
        'delete',
        'details',
        'order',
    ];
    protected $casts = [
        'details' => 'array',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function dataType()
    {
        return $this->belongsTo(DataType::class, 'data_type_id');
    }
}
