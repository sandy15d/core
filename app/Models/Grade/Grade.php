<?php

namespace App\Models\Grade;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class Grade extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'grade';

    
    protected $fillable = ['level_id', 'grade_name', 'effective_date', 'is_active'];

    protected $dates = ['created_at','updated_at','deleted_at'];
    
    public function level() {
    return $this->belongsTo("App\Models\Level\Level", "level_id");
}


    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('grade_sort_by') && $request->grade_sort_by) {
        if ($request->grade_direction == "desc"){
            $query->orderByDesc($request->grade_sort_by);
            } else {
            $query->orderBy($request->grade_sort_by);
            }
        } else {
             $query->orderByDesc("id");
         }
    }
    public function scopeStartSearch($query, $search): void
    {
        if ($search) {
            $query->where("id","like","%".$search."%");
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
            if (Auth::check() && ! $model->isForceDeleting()) {
                $model->deleted_by = Auth::id();
                $model->save();
            }
        });
    }
}
