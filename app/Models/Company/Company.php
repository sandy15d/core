<?php

namespace App\Models\Company;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FileUploadTrait;
use Illuminate\Support\Facades\Auth;

class Company extends Model
{
    use SoftDeletes;
    use FileUploadTrait;

    
    protected $table = 'company';

    
    protected $fillable = ['company_name', 'company_code', 'registration_number', 'tin_number', 'gst_number', 'legal_entity_type', 'website', 'email', 'logo'];

    protected $dates = ['created_at','updated_at','deleted_at'];
    public function fileInfo($key=false)
{
    $file_info = [
        'logo' => [
            'disk' => config('admin.settings.upload_disk'),
            'quality' => config('admin.images.image_quality'),
            'webp' => ['action' => 'none', 'quality' => config('admin.images.image_quality')],
            'original' => ['action' => 'resize', 'width' => 1920, 'height' => 1080, 'folder' => '/upload/'],
        ],
    ];
    return ($key) ? $file_info[$key] : $file_info;
}

        public function setLogoAttribute()
    {
        if (request()->hasFile('logo')) {
            $this->attributes['logo'] = $this->akImageUpload(request()->file("logo"), $this->fileInfo("logo"), $this->getOriginal('logo'));
        }
    }

    public function getLogoAttribute($value)
    {
        if ($value && $this->akFileExists($this->fileInfo("logo")['disk'], $this->fileInfo("logo")['original']["folder"], $value)) {
          return asset('upload/' . $value);
        }
        return false;
    }

    public function setAkLogoDeleteAttribute($delete)
    {
        if (!request()->hasFile('logo') && $delete == 1) {
            $this->attributes['logo'] = $this->akImageUpload('', $this->fileInfo("logo"), $this->getOriginal('logo'), 1);
        }
    }

    public function scopeStartSorting($query, $request): void
    {
        if ($request->has('company_sort_by') && $request->company_sort_by) {
        if ($request->company_direction == "desc"){
            $query->orderByDesc($request->company_sort_by);
            } else {
            $query->orderBy($request->company_sort_by);
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
