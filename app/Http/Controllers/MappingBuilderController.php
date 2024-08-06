<?php

namespace App\Http\Controllers;

use App\Models\FormBuilder;
use App\Models\MappingBuilder;
use App\Models\PageBuilder;
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
use Illuminate\Support\Facades\Validator;
use App\Services\PermissionService;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
        $table_list = PageBuilder::pluck('snake_case', 'page_name')->toArray();
        $data = new MappingBuilder();
        return view('mapping_builder.mapping_create', compact('table_list', 'data'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mapping_name' => ['required', 'unique:mapping_builders', 'max:255', new ReservedKeyword],
            'parent' => 'required|max:200',
            'child' => 'required|max:200',
        ]);
        $data['mapping_table_name'] = \Str::snake($request->mapping_name) . "_mapping";
        MappingBuilder::create($data);
        return redirect(route("mapping-builder.index"))->with("toast_success", 'Mapping created successfully');
    }

    public function edit(MappingBuilder $mappingBuilder)
    {
        $data = $mappingBuilder;
        $table_list = PageBuilder::pluck('snake_case', 'page_name')->toArray();
        return view('mapping_builder.mapping_create', compact('data', 'table_list'));
    }

    public function update(Request $request, MappingBuilder $mappingBuilder)
    {
        $data = Validator::make($request->all(), [
            'mapping_name' => 'required|unique:mapping_builders,mapping_name,' . $mappingBuilder->id,
            'parent' => 'required|max:200',
            'child' => 'required|max:200',
        ]);

        if ($data->fails()) {
            return redirect()->back()->withErrors($data)->withInput();
        }

        $validatedData = $data->validated();
        $validatedData['mapping_table_name'] = \Str::snake($validatedData['mapping_name']) . "_mapping";

        $mappingBuilder->update($validatedData);

        return redirect()->route('mapping-builder.index')->with('toast_success', 'Mapping Updated Successfully!');
    }

    public function destroy(MappingBuilder $mappingBuilder)
    {
        $table_name = $mappingBuilder->mapping_table_name;
        $modelName = Str::studly(Str::singular($table_name));

        // Delete permissions related to the model
        $this->deletePermissions($modelName);

        // Delete the mapping builder record
        $mappingBuilder->delete();

        // Delete related files and directories
        $this->deleteFile(app_path("Http/Controllers/Mapping/{$modelName}Controller.php"));
        $this->deleteFile(app_path("Models/Mapping/{$modelName}.php"));
        $this->deleteDirectory(resource_path("views/Mapping/{$modelName}"));

        // Remove the route entries from web.php
        $this->removeRouteEntries($modelName);

        // Drop the database table if it exists
        if (Schema::hasTable($table_name)) {
            Schema::drop($table_name);
        }

        // Delete the menu entry
        DB::table('menus')->where('permissions', $modelName)->delete();

        return redirect()->route('mapping-builder.index')->with('toast_success', 'Mapping Deleted Successfully!');
    }

    /**
     * Delete a file if it exists
     *
     * @param string $path
     * @return void
     */
    private function deleteFile(string $path): void
    {
        if (File::exists($path)) {
            File::delete($path);
        }
    }

    /**
     * Delete a directory if it exists
     *
     * @param string $directory
     * @return void
     */
    private function deleteDirectory(string $directory): void
    {
        if (File::exists($directory)) {
            File::deleteDirectory($directory);
        }
    }

    /**
     * Remove all route entries that contain the modelNameController from the web.php file
     *
     * @param string $modelName
     * @return void
     */
    private function removeRouteEntries(string $modelName): void
    {
        $routeFilePath = base_path('routes/web.php');
        $routeFileContent = file_get_contents($routeFilePath);

        // Pattern to match any line containing the modelNameController
        $pattern = "/.*{$modelName}Controller.*\n/";
        $routeFileContent = preg_replace($pattern, '', $routeFileContent);

        file_put_contents($routeFilePath, $routeFileContent);
    }


    public function formGenerate(Request $request)
    {
        $page_id = base64_decode($request->page);
        $page = MappingBuilder::where('id', $page_id)->first()->toArray();
        $parent_table = MappingBuilder::where('id', $page_id)->value('parent');
        $child_table = MappingBuilder::where('id', $page_id)->value('child');
        $parent_table_columns = FormBuilder::where('page_name', $parent_table)->select('column_name', 'column_title')->get();
        $child_table_columns = FormBuilder::where('page_name', $child_table)->select('column_name', 'column_title')->get();
        $data = MappingBuilder::find($page_id)->first();
        return view('mapping_builder.mapping_generate_form', compact('page', 'page_id', 'parent_table_columns', 'child_table_columns', 'data'));
    }

    public function generateMappingBuilder(Request $request)
    {

        // Validate incoming request data
        $data = $request->validate([
            'parent_column' => 'required|string',
            'child_column' => 'required|array',
            'mapping_type' => 'required|string',
            'mapping_id' => 'required|exists:mapping_builders,id' // Validate that mapping_id exists in mapping_builders table
        ]);

        // Retrieve the mapping_id from validated data
        $mappingId = $data['mapping_id'];
        unset($data['mapping_id']); // Remove mapping_id from data array to avoid updating it
        $data['child_column'] = implode(',', $data['child_column']);
        // Update the MappingBuilder with the validated data
        MappingBuilder::where('id', $mappingId)->update($data);

        //Create Database for Mapping
        $mapping_details = MappingBuilder::where('id', $mappingId)->first();
        $tableData['table_name'] = $mapping_details->mapping_table_name;
        $tableData['parent_column_name'] = \Str::snake($mapping_details->parent) . "_id";
        $tableData['parent_column'] = \Str::snake($mapping_details->parent_column);
        $tableData['child_column_name'] = \Str::snake($mapping_details->child) . "_id";
        $tableData['child_column'] = \Str::snake($mapping_details->child_column);
        $tableData['parent'] = $mapping_details->parent;
        $tableData['child'] = $mapping_details->child;
        $this->mappingDatabaseSetup($tableData);
        //Generate Model for Mapping
        $this->GenerateMappingModel($tableData);
        //Generate Controller for Mapping
        $this->GenerateMappingController($tableData);
        $this->GenerateMappingRoutes($tableData);
        $this->GenerateIndexView($tableData);
        $this->GenerateListView($tableData);
        // Redirect with success message
        return redirect()->route('mapping-builder.index')->with('toast_success', 'Mapping Generated Successfully!');
    }

    function deletePermissions($permissionName)
    {

        $permission = Permission::findByName($permissionName);

        // Remove the permission from all roles
        $roles = Role::all();
        foreach ($roles as $role) {
            $role->revokePermissionTo($permissionName);
        }

        // Remove the permission from all users (or other models)
        $users = User::permission($permissionName)->get();
        foreach ($users as $user) {
            $user->revokePermissionTo($permissionName);
        }

        // Delete the permission from the database
        $permission->delete();
    }
}
