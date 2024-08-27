<?php

namespace App\Models\CompanyAddress;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class CompanyAddress extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'company_address';

    
    protected $fillable = ['company_id', 'address_type', 'pin_code', 'country_id', 'state_id', 'district_id', 'city_id', 'address'];

    protected $dates = ['created_at','updated_at','deleted_at'];
    
    public function company() {
    return $this->belongsTo("App\Models\Company\Company", "company_id");
}

public function country() {
    return $this->belongsTo("App\Models\Country\Country", "country_id");
}

public function state() {
    return $this->belongsTo("App\Models\State\State", "state_id");
}

public function district() {
    return $this->belongsTo("App\Models\District\District", "district_id");
}

public function cityvillage() {
    return $this->belongsTo("App\Models\CityVillage\CityVillage", "city_id");
}


    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('company_address_sort_by') && $request->company_address_sort_by) {
        if ($request->company_address_direction == "desc"){
            $query->orderByDesc($request->company_address_sort_by);
            } else {
            $query->orderBy($request->company_address_sort_by);
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
