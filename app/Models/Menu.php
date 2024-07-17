<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'menu_name',
        'menu_url',
        'parent_id',
        'menu_position',
        'menu_type',
    ];
}
