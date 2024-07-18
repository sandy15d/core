<?php

namespace App\Models\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;

class State extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'state';

    
    protected $fillable = ['country_id', 'state_name', 'state_code', 'is_active'];


    protected $dates = ['created_at','updated_at','deleted_at'];

    

    public function country() {
    return $this->belongsTo("App\Models\Country\Country", "country_id");
}



    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('state_sort_by') && $request->state_sort_by) {
        if ($request->state_direction == "desc"){
            $query->orderByDesc($request->state_sort_by);
            } else {
            $query->orderBy($request->state_sort_by);
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
