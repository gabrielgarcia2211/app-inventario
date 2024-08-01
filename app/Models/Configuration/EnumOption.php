<?php

namespace App\Models\Configuration;

use Illuminate\Database\Eloquent\Model;

class EnumOption extends Model
{
    protected $table = 'enum_options';
    protected $primaryKey = 'id';

    protected $fillable = [
        'parent_id',
        'name',
        'description',
        'value1',
        'is_father',
        'status'
    ];

    public function parent()
    {
        return $this->belongsTo(EnumOption::class, 'parent_id');
    }
}
