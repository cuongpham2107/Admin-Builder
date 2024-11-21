<?php

namespace CuongPham2107\AdminBuilder\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DataType extends Model
{
    protected $table = 'data_types';
    protected $fillable = [
        'name',
        'slug',
        'display_name_singular',
        'display_name_plural',
        'icon',
        'model_name',
        'policy_name',
        'details',
        'description',
        'filament_resource',
    ];
    protected $casts = [
        'details' => 'array',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function fields() : HasMany{
        return $this->hasMany(DataRow::class, 'id');
    }
}
