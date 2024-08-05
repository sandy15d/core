<?php

namespace App\Http\Controllers;

use App\Models\FormBuilder;
use App\Models\MappingBuilder;
use App\Models\PageBuilder;
use App\Rules\ReservedKeyword;
use App\Traits\GenerateMappingControllerTrait;
use App\Traits\GenerateViewForMappingTrait;
use App\Traits\MappingDatabaseTrait;
use App\Traits\MappingModelGenerateTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MappingBuilderController extends Controller
{
    use MappingDatabaseTrait;
    use MappingModelGenerateTrait;
    use GenerateMappingControllerTrait;
    use GenerateViewForMappingTrait;

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
        $mappingBuilder->delete();

        return redirect()->route('mapping-builder.index')->with('toast_success', 'Mapping Deleted Successfully!');
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

}
