<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Project extends Model
{
    use SoftDeletes;

    protected $table = 'project';
    protected $fillable = ['project_name', 'platform', 'project_key', 'is_active'];
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

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

    public function apiBuilders()
    {
        return $this->belongsToMany(APIBuilder::class,'project_api_builder');
    }
}
