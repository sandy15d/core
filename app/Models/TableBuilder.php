<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TableBuilder extends Model
{
    public $table = 'table_builder';
    protected $fillable = [
        'table_name',
        'column_name',
        'column_type',
        'column_length',
        'is_nullable',
        'is_unique',
        'is_unsigned',
        'column_default',
        'column_after',
    ];
}
