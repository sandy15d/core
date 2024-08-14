<?php

namespace App\Models\Mapping;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FunctionVerticalMapping extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'function_vertical_mapping';
    protected $fillable = ['org_function_id', 'vertical_id','name'];

    /**
     * Get the parent model relationship.
     */
    public function orgFunction(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo("App\Models\OrgFunction\OrgFunction", 'org_function_id', 'id');
    }

    /**
     * Get the child model relationship.
     */
    public function vertical(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo("App\Models\Vertical\Vertical", 'vertical_id', 'id');
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