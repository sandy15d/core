<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ApiBuilder extends Model
{

    protected $table = 'api_builder';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['route_name', 'model', 'parameters', 'predefined_conditions'];
    protected $casts = [
        'predefined_conditions' => 'array',  // Cast predefined_conditions to an array
    ];

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

    public function project()
    {
        return $this->belongsTo(Project::class);
    }


}