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
        $childColumnNames = explode(',', $tableData['child_column']);
        // Prepare child columns selection string
        $childColumnSelect = implode("', '", array_map('trim', $childColumnNames));
        $storeMapping = Str::snake(Str::pluralStudly($tableData['table_name'])) . '_data';
        $mappingList = Str::snake(Str::pluralStudly($tableData['table_name'])) . '_list';
        $parent_id = Str::lower(Str::studly($tableData['parent'])) . '_id';
        $child_id = Str::lower(Str::studly($tableData['child'])) . '_ids';
        $child_id_singular = Str::lower(Str::studly($tableData['child'])) . '_id';
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

        public function $storeMapping(Request \$request){
            \$effective_from =Carbon::parse(\$request->effective_from);
            \$$parent_id = \$request->$parent_id;
            \$$child_id = \$request->$child_id;
            \$currentTimestamp = Carbon::now();
            \$userId = Auth::user()->id;
            // Prepare the data for batch insert
        \$insertData = array_map(function(\$$child_id_singular) use ( \$$parent_id, \$effective_from, \$userId, \$currentTimestamp) {
            return [
                'zone_id' =>  \$$parent_id,
                'region_id' => \$$child_id_singular,
                'effective_from' => \$effective_from,
                'created_by' => \$userId,
                'created_at' => \$currentTimestamp,
            ];
        }, \$$child_id);

        try {
            // Use a transaction to ensure data integrity
            DB::transaction(function() use (\$$parent_id, \$$child_id, \$effective_from, \$insertData) {
                foreach (\$$child_id as \$$child_id_singular) {
                    // Find any existing mapping with the same zone_id and region_id
                    \$existingMapping = $modelName::where('region_id', \$$child_id_singular)
                        ->whereNull('effective_to')
                        ->orderBy('effective_from', 'desc')
                        ->first();

                    if (\$existingMapping && \$existingMapping->effective_from < \$effective_from) {
                        // Update the effective_to date of the existing mapping
                        \$existingMapping->effective_to = \$effective_from->copy()->subDay();
                        \$existingMapping->updated_at = Carbon::now();
                        \$existingMapping->save();
                    }
                }

                // Insert the new mappings
                $modelName::insert(\$insertData);
            });
                return response()->json(['status' => '200']);
            } catch (\Exception \$e) {
                // Handle the exception and return an appropriate response
                return response()->json(['status' => '500', 'message' => 'An error occurred while processing your request.'], 500);
            }
        }

        public function $mappingList(){
            \$list = $modelName::all();
            return view("Mapping.{$modelName}.list",compact('list'));
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
            $this->permissionService->createAndAssignPermission($modelName,$tableData['table_name']);
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
