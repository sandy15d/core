<?php

namespace App\Models\Mapping;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FunVerticalDeptMapping extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'fun_vertical_dept_mapping';
    protected $fillable = ['function_vertical_id', 'department_id','name'];

    /**
     * Get the parent model relationship.
     */
    public function functionVertical(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo("App\Models\FunctionVertical\FunctionVertical", 'function_vertical_id', 'id');
    }

    /**
     * Get the child model relationship.
     */
    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo("App\Models\Department\Department", 'department_id', 'id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
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