<?php

namespace App\Models\Segment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class Segment extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'segment';

    
    protected $fillable = ['crop_id', 'segment_name', 'is_active', 'segment_code', 'effective_date'];

    protected $dates = ['created_at','updated_at','deleted_at'];
    
    public function crop() {
    return $this->belongsTo("App\Models\Crop\Crop", "crop_id");
}


    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('segment_sort_by') && $request->segment_sort_by) {
        if ($request->segment_direction == "desc"){
            $query->orderByDesc($request->segment_sort_by);
            } else {
            $query->orderBy($request->segment_sort_by);
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
