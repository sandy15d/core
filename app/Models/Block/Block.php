<?php

namespace App\Models\Block;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class Block extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'block';

    
    protected $fillable = ['district_id', 'block_name', 'block_code', 'effective_date', 'is_active', 'numeric_code'];

    protected $dates = ['created_at','updated_at','deleted_at'];
    
    public function district() {
    return $this->belongsTo("App\Models\District\District", "district_id");
}


    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('block_sort_by') && $request->block_sort_by) {
        if ($request->block_direction == "desc"){
            $query->orderByDesc($request->block_sort_by);
            } else {
            $query->orderBy($request->block_sort_by);
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
