<?php

namespace App\Models\Crop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class Crop extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'crop';

    
    protected $fillable = ['vertical_id', 'crop_name', 'crop_code', 'numeric_code', 'effective_date', 'is_active'];

    protected $dates = ['created_at','updated_at','deleted_at'];
    
    public function vertical() {
    return $this->belongsTo("App\Models\Vertical\Vertical", "vertical_id");
}


    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('crop_sort_by') && $request->crop_sort_by) {
        if ($request->crop_direction == "desc"){
            $query->orderByDesc($request->crop_sort_by);
            } else {
            $query->orderBy($request->crop_sort_by);
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
