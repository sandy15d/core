<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class MappingBuilder extends Model
{
    use SoftDeletes;

    protected $table = 'mapping_builders';
    public $timestamps = false;
    protected $fillable = ['mapping_name','mapping_table_name', 'parent', 'child','mapping_type','parent_column','child_column'];


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
        static::deleting(function ($model) {
            if (Auth::check() && !$model->isForceDeleting()) {
                $model->deleted_by = Auth::id();
                $model->save();
            }
        });
    }

}
