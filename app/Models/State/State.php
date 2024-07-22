<?php

namespace App\Models\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class State extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'state';

    
    protected $fillable = ['country_id', 'state_name', 'state_code', 'effective_date', 'is_active', 'short_code'];


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
