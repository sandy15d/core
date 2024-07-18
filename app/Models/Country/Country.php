<?php

namespace App\Models\Country;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;

class Country extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'country';

    
    protected $fillable = ['country_name', 'country_code', 'is_active'];


    protected $dates = ['created_at','updated_at','deleted_at'];

    

    

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
}
