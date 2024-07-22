<?php

namespace App\Models\GlobalRegion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;

class GlobalRegion extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'global_region';

    
    protected $fillable = ['global_region_name', 'global_region_code'];


    protected $dates = ['created_at','updated_at','deleted_at'];

    

    

    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('global_region_sort_by') && $request->global_region_sort_by) {
        if ($request->global_region_direction == "desc"){
            $query->orderByDesc($request->global_region_sort_by);
            } else {
            $query->orderBy($request->global_region_sort_by);
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
