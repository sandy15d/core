<?php

namespace App\Http\Controllers;

use App\Models\MappingBuilder;
use App\Models\PageBuilder;
use App\Models\FormBuilder;
use App\Models\User;
use App\Rules\ReservedKeyword;
use App\Traits\GenerateMappingControllerTrait;
use App\Traits\GenerateViewForMappingTrait;
use App\Traits\MappingDatabaseTrait;
use App\Traits\MappingModelGenerateTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Services\PermissionService;

class MappingBuilderController extends Controller
{
    use MappingDatabaseTrait;
    use MappingModelGenerateTrait;
    use GenerateMappingControllerTrait;
    use GenerateViewForMappingTrait;

    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index()
    {
        $list = MappingBuilder::all();
        return view('mapping_builder.index', compact('list'));
    }

    public function create()
    {
        $table_list = $this->getTableList();
        $data = new MappingBuilder();
        return view('mapping_builder.mapping_create', compact('table_list', 'data'));
    }

    public function store(Request $request)
    {
        $data = $this->validateMappingRequest($request);
        $data = $this->generateMappingAttributes($data, $request);

        MappingBuilder::create($data);

        return redirect(route('mapping-builder.index'))->with('toast_success', 'Mapping created successfully');
    }

    public function edit(MappingBuilder $mappingBuilder)
    {
        $table_list = $this->getTableList();
        $data = $mappingBuilder;
        return view('mapping_builder.mapping_create', compact('data', 'table_list'));
    }

    public function update(Request $request, MappingBuilder $mappingBuilder)
    {
        $data = $this->validateMappingRequest($request, $mappingBuilder->id);
        $validatedData = $this->generateMappingAttributes($data, $request);

        $mappingBuilder->update($validatedData);

        return redirect()->route('mapping-builder.index')->with('toast_success', 'Mapping Updated Successfully!');
    }

    public function destroy(MappingBuilder $mappingBuilder)
    {
        $this->deleteMappingResources($mappingBuilder);

        return redirect()->route('mapping-builder.index')->with('toast_success', 'Mapping Deleted Successfully!');
    }

    public function formGenerate(Request $request)
    {
        $page_id = base64_decode($request->page);
        $page = MappingBuilder::findOrFail($page_id)->toArray();
        $parent_table_columns = $this->getFormBuilderColumns($page['parent']);
        $child_table_columns = $this->getFormBuilderColumns($page['child']);
        $data = MappingBuilder::find($page_id);

        return view('mapping_builder.mapping_generate_form', compact('page', 'page_id', 'parent_table_columns', 'child_table_columns', 'data'));
    }

    public function generateMappingBuilder(Request $request)
    {
        $data = $this->validateGenerateMappingRequest($request);
        $this->updateMappingForm($data);
        $this->performMappingGeneration($data);
        return redirect()->route('mapping-builder.index')->with('toast_success', 'Mapping Generated Successfully!');
    }

    private function getTableList()
    {
        $tableList1 = PageBuilder::pluck('snake_case', 'page_name')->toArray();
        $tableList2 = MappingBuilder::pluck('mapping_table_name', 'mapping_name')->toArray();
        return array_merge($tableList1, $tableList2);
    }

    private function validateMappingRequest(Request $request, $ignoreId = null)
    {
        $rules = [
            'mapping_name' => ['required', 'max:255', new ReservedKeyword()],
            'parent' => 'required|max:200',
            'child' => 'required|max:200',
            'relationship_type' => 'required|max:200',
        ];

        if ($ignoreId) {
            $rules['mapping_name'] = ['required', 'unique:mapping_builders,mapping_name,' . $ignoreId, 'max:255', new ReservedKeyword()];
        }

        return $request->validate($rules);
    }

    private function generateMappingAttributes(array $data, Request $request)
    {
        $data['mapping_table_name'] = \Str::snake($data['mapping_name']) . "_mapping";
        $data['parent_table_name'] = Str::snake($request->parent);
        $data['child_table_name'] = Str::snake($request->child);
        $data['parent_mapping_name'] = Str::snake($request->parent) . '_id';
        $data['child_mapping_name'] = Str::snake($request->child) . '_id';
        $data['relationship_type'] = $request->relationship_type;
        return $data;
    }

    private function validateGenerateMappingRequest(Request $request)
    {
        return $request->validate([
            'parent_column' => 'required|string',
            'child_column' => 'required|array',
            'mapping_type' => 'required|string',
            'mapping_id' => 'required|exists:mapping_builders,id',
        ]);
    }

    private function updateMappingForm(array $data)
    {
        $mappingId = $data['mapping_id'];
        unset($data['mapping_id']); // Remove mapping_id from data array to avoid updating it
        $data['child_column'] = implode(',', $data['child_column']);
        MappingBuilder::where('id', $mappingId)->update($data);
    }
    private function performMappingGeneration(array $data)
    {
        $mappingDetails = MappingBuilder::findOrFail($data['mapping_id']);

        $tableData = [
            'table_name' => $mappingDetails->mapping_table_name,
            'parent_mapping_name' => $mappingDetails->parent_mapping_name,
            'child_mapping_name' => $mappingDetails->child_mapping_name,
            'parent' => $mappingDetails->parent,
            'child' => $mappingDetails->child,
            'parent_column' => $data['parent_column'],
            'child_column' => implode(',', $data['child_column']),
            'relationship_type' => $mappingDetails->relationship_type,
        ];

        $this->mappingDatabaseSetup($tableData);
        $this->generateMappingModel($tableData);
        $this->generateMappingController($tableData);
        $this->generateMappingRoutes($tableData);
        $this->generateIndexView($tableData);
        $this->generateListView($tableData);
    }

    private function deleteMappingResources(MappingBuilder $mappingBuilder)
    {
        $tableName = $mappingBuilder->mapping_table_name;
        $modelName = Str::studly(Str::singular($tableName));

        $this->deletePermissions($modelName);

        $mappingBuilder->delete();

        $this->deleteFile(app_path("Http/Controllers/Mapping/{$modelName}Controller.php"));
        $this->deleteFile(app_path("Models/Mapping/{$modelName}.php"));
        $this->deleteDirectory(resource_path("views/Mapping/{$modelName}"));
        $this->removeRouteEntries($modelName);

        if (Schema::hasTable($tableName)) {
            Schema::drop($tableName);
        }

        $menu = DB::table('menus')->where('permissions', $modelName);
        if ($menu->exists()) {
            $menu->delete();
        }
    }

    private function getFormBuilderColumns($tableName)
    {
        return FormBuilder::where('page_name', $tableName)->select('column_name', 'column_title')->get();
    }

    private function deletePermissions($permissionName)
    {
        try {
            $permission = Permission::findByName($permissionName, 'web');
        } catch (\Spatie\Permission\Exceptions\PermissionDoesNotExist $e) {
            logger()->error("Permission '{$permissionName}' does not exist for guard 'web'.");
            return;
        }

        Role::all()->each(function ($role) use ($permissionName) {
            $role->revokePermissionTo($permissionName);
        });

        User::permission($permissionName)->get()->each(function ($user) use ($permissionName) {
            $user->revokePermissionTo($permissionName);
        });

        $permission->delete();
    }

    private function deleteFile(string $path): void
    {
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    private function deleteDirectory(string $directory): void
    {
        if (File::exists($directory)) {
            File::deleteDirectory($directory);
        }
    }

    private function removeRouteEntries(string $modelName): void
    {
        $routeFilePath = base_path('routes/web.php');
        $routeFileContent = file_get_contents($routeFilePath);

        $pattern = "/.*{$modelName}Controller.*\n/";
        $routeFileContent = preg_replace($pattern, '', $routeFileContent);

        file_put_contents($routeFilePath, $routeFileContent);
    }
}
