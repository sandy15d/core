<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait MappingModelGenerateTrait
{
    function GenerateMappingModel($tableData): void
    {
        $modelName = Str::studly(Str::singular($tableData['table_name']));
        $modelDirectory = app_path('Models/Mapping');
        $modelPath = $modelDirectory . "/$modelName.php";

        // Create model directory if it doesn't exist
        if (!File::exists($modelDirectory)) {
            File::makeDirectory($modelDirectory, 0755, true);
        }

        // Generate model content
        $modelContent = $this->getModelContent($modelName, $tableData);

        // Write model file
        File::put($modelPath, $modelContent);
    }

    protected function getModelContent($modelName, $tableData)
    {
        // Extracting necessary data from $tableData array
        $parent = $tableData['parent_column_name'];
        $child = $tableData['child_column_name'];
        $table = $tableData['table_name'];
        $parentModelName = Str::studly($tableData['parent']);
        $childModelName = Str::studly($tableData['child']);

        // Preparing the fillable array
        $fillable = "['$parent', '$child']";

        // Preparing the model's relationship method names
        $parentModelMethod = Str::camel($parentModelName);
        $childModelMethod = Str::camel($childModelName);

        return <<<EOT
<?php

namespace App\Models\Mapping;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class $modelName extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected \$table = '$table';
    protected \$fillable = $fillable;

    /**
     * Get the parent model relationship.
     */
    public function $parentModelMethod(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return \$this->belongsTo("App\\Models\\$parentModelName\\$parentModelName", '$parent', 'id');
    }

    /**
     * Get the child model relationship.
     */
    public function $childModelMethod(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return \$this->belongsTo("App\\Models\\$childModelName\\$childModelName", '$child', 'id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (\$model) {
            if (Auth::check()) {
                \$model->created_by = Auth::id();
            }
        });

        static::updating(function (\$model) {
            if (Auth::check()) {
                \$model->updated_by = Auth::id();
            }
        });
    }
}
EOT;
    }



}
