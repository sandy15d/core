<?php

namespace App\Models\CityVillage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class CityVillage extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'city_village';

    
    protected $fillable = ['city_village_name', 'pincode', 'division_name', 'district_id', 'latitude', 'longitude', 'effective_date', 'is_active', 'state_id'];

    protected $dates = ['created_at','updated_at','deleted_at'];
    
    public function district() {
    return $this->belongsTo("App\Models\District\District", "district_id");
}

public function state() {
    return $this->belongsTo("App\Models\State\State", "state_id");
}


    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('city_village_sort_by') && $request->city_village_sort_by) {
        if ($request->city_village_direction == "desc"){
            $query->orderByDesc($request->city_village_sort_by);
            } else {
            $query->orderBy($request->city_village_sort_by);
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
