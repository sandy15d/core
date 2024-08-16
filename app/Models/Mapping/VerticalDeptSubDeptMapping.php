<?php

namespace App\Models\Mapping;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VerticalDeptSubDeptMapping extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'vertical_dept_sub_dept_mapping';
    protected $fillable = ['fun_vertical_dept_id', 'sub_department_id','name'];

    /**
     * Get the parent model relationship.
     */
    public function funVerticalDept(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo("App\Models\FunVerticalDept\FunVerticalDept", 'fun_vertical_dept_id', 'id');
    }

    /**
     * Get the child model relationship.
     */
    public function subDepartment(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo("App\Models\SubDepartment\SubDepartment", 'sub_department_id', 'id');
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