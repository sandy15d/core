<?php

namespace App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class Country extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'country';

    
    protected $fillable = ['global_region', 'country_name', 'country_code', 'is_active'];


    protected $dates = ['created_at','updated_at','deleted_at'];

    

    public function globalregion() {
        
    return $this->belongsTo("App\Models\GlobalRegion\GlobalRegion", "global_region");
    }



    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('country_sort_by') && $request->country_sort_by) {
        if ($request->country_direction == "desc"){
            $query->orderByDesc($request->country_sort_by);
            } else {
            $query->orderBy($request->country_sort_by);
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
