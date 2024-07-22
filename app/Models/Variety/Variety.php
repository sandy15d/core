<?php

namespace App\Models\Variety;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class Variety extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'variety';

    
    protected $fillable = ['crop_id', 'variety_name', 'variety_code', 'numeric_code', 'effective_date', 'is_active'];

    protected $dates = ['created_at','updated_at','deleted_at'];
    
    public function crop() {
    return $this->belongsTo("App\Models\Crop\Crop", "crop_id");
}


    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('variety_sort_by') && $request->variety_sort_by) {
        if ($request->variety_direction == "desc"){
            $query->orderByDesc($request->variety_sort_by);
            } else {
            $query->orderBy($request->variety_sort_by);
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
