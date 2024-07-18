<?php

namespace App\Models\District;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;

class District extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'district';

    
    protected $fillable = ['state_id', 'district_name', 'district_code', 'is_active'];


    protected $dates = ['created_at','updated_at','deleted_at'];

    

    public function state() {
    return $this->belongsTo("App\Models\State\State", "state_id");
}



    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('district_sort_by') && $request->district_sort_by) {
        if ($request->district_direction == "desc"){
            $query->orderByDesc($request->district_sort_by);
            } else {
            $query->orderBy($request->district_sort_by);
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
