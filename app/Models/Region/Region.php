<?php

namespace App\Models\Region;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class Region extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'region';

    
    protected $fillable = ['region_name', 'region_code', 'numeric_code', 'effective_date', 'is_active', 'vertical_id'];

    protected $dates = ['created_at','updated_at','deleted_at'];
    
    public function vertical() {
    return $this->belongsTo("App\Models\Vertical\Vertical", "vertical_id");
}


    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('region_sort_by') && $request->region_sort_by) {
        if ($request->region_direction == "desc"){
            $query->orderByDesc($request->region_sort_by);
            } else {
            $query->orderBy($request->region_sort_by);
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
