<?php

namespace App\Models\BusinessUnit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class BusinessUnit extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'business_unit';

    
    protected $fillable = ['business_unit_name', 'business_unit_code', 'effective_date', 'is_active', 'numeric_code', 'vertical_id'];

    protected $dates = ['created_at','updated_at','deleted_at'];
    
    public function vertical() {
    return $this->belongsTo("App\Models\Vertical\Vertical", "vertical_id");
}


    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('business_unit_sort_by') && $request->business_unit_sort_by) {
        if ($request->business_unit_direction == "desc"){
            $query->orderByDesc($request->business_unit_sort_by);
            } else {
            $query->orderBy($request->business_unit_sort_by);
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
