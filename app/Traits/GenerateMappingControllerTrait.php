<?php

namespace App\Traits;

use App\Models\Menu;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Services\PermissionService;

trait GenerateMappingControllerTrait
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    function GenerateMappingController($tableData): void
    {
        $modelName = Str::studly(Str::singular($tableData['table_name']));
        $controllerName = $modelName . 'Controller';
        $controllerPath = app_path("Http/Controllers/Mapping/$controllerName.php");

        // Create controller directory if it doesn't exist
        if (!File::exists(app_path('Http/Controllers/Mapping'))) {
            File::makeDirectory(app_path('Http/Controllers/Mapping'), 0755, true);
        }

        // Generate controller content
        $controllerContent = $this->getControllerContent($modelName, $controllerName, $tableData);

        // Write controller file
        File::put($controllerPath, $controllerContent);
    }

    protected function getControllerContent($modelName, $controllerName, $tableData): string
    {
        $parentModel = Str::studly($tableData['parent']);
        $childModel = Str::studly($tableData['child']);

        $parentColumnName = $tableData['parent_column'];
        $childColumnNames = array_map('trim', explode(',', $tableData['child_column']));
        $childColumnSelect = implode("', '", $childColumnNames);

        $storeMapping = Str::snake(Str::pluralStudly($tableData['table_name'])) . '_data';
        $mappingList = Str::snake(Str::pluralStudly($tableData['table_name'])) . '_list';

        $parentId = $tableData['parent_mapping_name'];
        $childIdPlural = Str::plural($tableData['child_mapping_name']);
        $childIdSingular = $tableData['child_mapping_name'];
        $parent_table_name = $tableData['parent_table_name'];
        $child_table_name = $tableData['child_table_name'];
        $childFirstColumn = explode(',',$tableData['child_column'])[0];
        // Generate the one_to_many specific code if applicable
        $oneToManyHandling = '';
        if ($tableData['relationship_type'] === 'one_to_many') {
            $oneToManyHandling = <<<EOT
            foreach (\$$childIdPlural as \$$childIdSingular) {
                \$existingMapping = $modelName::where('$childIdSingular', \$$childIdSingular)
                    ->whereNull('effective_to')
                    ->orderBy('effective_from', 'desc')
                    ->first();

                if (\$existingMapping && \$existingMapping->effective_from < \$effectiveFrom) {
                    \$existingMapping->effective_to = \$effectiveFrom->copy()->subDay();
                    \$existingMapping->updated_at = Carbon::now();
                    \$existingMapping->save();
                }
            }
EOT;
        }

        return <<<EOT
<?php

namespace App\Http\Controllers\Mapping;

use App\Http\Controllers\Controller;
use App\Models\Mapping\\$modelName;
use App\Models\\$parentModel\\$parentModel;
use App\Models\\$childModel\\$childModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Http\Request;

class $controllerName extends Controller
{
    public function index()
    {
        \${$parentModel}_list = $parentModel::pluck('$parentColumnName', 'id');
        \${$childModel}_list = $childModel::select(['$childColumnSelect', 'id'])->get();
        return view("Mapping.{$modelName}.index", compact('{$parentModel}_list', '{$childModel}_list'));
    }

    public function $storeMapping(Request \$request)
    {
        \$effectiveFrom = Carbon::parse(\$request->effective_from);
        \$$parentId = \$request->$parentId;
        \$$childIdPlural = \$request->$childIdPlural;
        \$currentTimestamp = Carbon::now();
        \$userId = Auth::user()->id;

        // Prepare the data for batch insert
        \$insertData = array_map(function(\$$childIdSingular) use (\$$parentId, \$effectiveFrom, \$userId, \$currentTimestamp) {
            return [
                '$parentId' => \$$parentId,
                '$childIdSingular' => \$$childIdSingular,
                'name'=> getTableColumnValue('$parent_table_name','$parentColumnName',\$$parentId).' - '.getTableColumnValue('$child_table_name','$childFirstColumn',\$$childIdSingular),
                'effective_from' => \$effectiveFrom,
                'created_by' => \$userId,
                'created_at' => \$currentTimestamp,
            ];
        }, \$$childIdPlural);

        try {
            // Use a transaction to ensure data integrity
            DB::transaction(function() use (\$$parentId, \$$childIdPlural, \$effectiveFrom, \$insertData) {
                $oneToManyHandling

                // Insert the new mappings
                $modelName::insert(\$insertData);
            });

            return response()->json(['status' => '200']);
        } catch (\Exception \$e) {
            // Handle the exception and return an appropriate response
            return response()->json(['status' => '500', 'message' => 'An error occurred while processing your request.'], 500);
        }
    }

    public function $mappingList()
    {
        \$list = $modelName::all();
        return view("Mapping.{$modelName}.list", compact('list'));
    }
}
EOT;
    }

    function GenerateMappingRoutes($tableData): void
    {
        $modelName = Str::studly(Str::singular($tableData['table_name']));
        $controllerName = "{$modelName}Controller";
        $routeName = Str::snake(Str::pluralStudly($tableData['table_name']));
        $routesPath = base_path('routes/web.php');

        // Check if the route already exists
        // Check if the route already exists
        if (!$this->routeExists($routeName, $routesPath)) {
            // Generate routes content
            $routeContent = "\nRoute::resource('$routeName', \\App\\Http\\Controllers\\Mapping\\{$controllerName}::class);";
            $routeContent .= "\nRoute::post('{$routeName}_data', [\\App\\Http\\Controllers\\Mapping\\{$controllerName}::class, '{$routeName}_data'])->name('{$routeName}_data');";
            $routeContent .= "\nRoute::get('{$routeName}_list', [\\App\\Http\\Controllers\\Mapping\\{$controllerName}::class, '{$routeName}_list'])->name('{$routeName}_list');";
            // Append routes to the web.php file
            File::append($routesPath, $routeContent);
        }


        // Check if menu already exists
        if (!$this->menuExists($routeName)) {
            $this->permissionService->createAndAssignPermission($modelName, $tableData['table_name']);
            $menu = new Menu();
            $menu->menu_name = formatStringForDisplay($routeName);
            $menu->menu_url = $routeName;
            $menu->parent_id = 0;
            $menu->menu_position = 1;
            $menu->permissions = $modelName;
            $menu->status = 'A';
            $menu->save();


        }


    }

    /**
     * Check if a route already exists in the web.php file.
     *
     * @param string $routeName
     * @param string $routesPath
     * @return bool
     */
    function routeExists(string $routeName, string $routesPath): bool
    {
        $routesContent = File::get($routesPath);
        return strpos($routesContent, "Route::resource('$routeName'") !== false;
    }

    /**
     * Check if a menu item already exists in the database.
     *
     * @param string $routeName
     * @return bool
     */
    function menuExists(string $routeName): bool
    {
        return Menu::where('menu_url', $routeName)->exists();
    }
}
