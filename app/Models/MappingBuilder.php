<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class MappingBuilder extends Model
{

    protected $table = 'mapping_builders';
    public $timestamps = false;
    protected $fillable = ['mapping_name', 'mapping_table_name', 'parent', 'child', 'parent_table_name', 'child_table_name', 'mapping_type', 'parent_column', 'child_column', 'parent_mapping_name', 'child_mapping_name'];


    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('mapping_sort_by') && $request->mapping_sort_by) {
            if ($request->mapping_direction == "desc") {
                $query->orderByDesc($request->mapping_sort_by);
            } else {
                $query->orderBy($request->mapping_sort_by);
            }
        } else {
            $query->orderByDesc("id");
        }
    }

    public function scopeStartSearch($query, $search): void
    {
        if ($search) {
            $query->where("id", "like", "%" . $search . "%");
        }
    }

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });

    }

}
