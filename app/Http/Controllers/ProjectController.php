<?php

namespace App\Http\Controllers;

use App\Models\ApiBuilder;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProjectController extends Controller
{

    public function index()
    {
        $projects = Project::all();
        return view('project.index', compact('projects'));
    }

    public function create()
    {
        $data = new Project();
        return view("project.project_form", compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|unique:project',
            'platform' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data['project_name'] = $request->project_name;
        $data['platform'] = $request->platform;
        $data['is_active'] = $request->is_active;
        $data['project_key'] = Str::random(32);
        Project::create($data);
        return redirect()->route('project.index')->with('toast_success', 'Project Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Project::findOrFail($id);
        return view('project.project_form', compact('data'));
    }

    public function update(Request $request, Project $project)
    {
        $validator = Validator::make($request->all(), [
            'project_name' => 'required|unique:project,project_name,' . $project->id,
            'platform' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $project->update($request->all());
        return redirect()->route('project.index')->with('toast_success', 'Project Updated Successfully!');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('project.index')->with('toast_success', 'Project Deleted Successfully!');
    }

    public function getProjectApi(Request $request)
    {
        // Decode the project ID from the route
        $projectId = base64_decode($request->route('project_id'));

        // Fetch all APIs and group them by the 'model' attribute
        $apiList = ApiBuilder::all()->groupBy('model');

        // Get the API IDs associated with the project
        $projectApis = DB::table('project_api_builder')
            ->where('project_id', $projectId)
            ->pluck('api_builder_id')
            ->toArray(); // Convert to array for easy use in Blade

        // Pass the necessary data to the view
        return view('project.project_api', compact('projectApis', 'apiList', 'projectId'));
    }


    public function setApi(Request $request)
    {
        $projectId = $request->input('project_id');
        $apis = $request->input('apis');

        // First, validate the input
        $request->validate([
            'project_id' => 'required|integer|exists:project,id',
            'apis' => 'required|array', // Assuming `apis` is an array of API IDs or names
        ]);

        // Check for existing records
        $existingApis = DB::table('project_api_builder')
            ->where('project_id', $projectId)
            ->pluck('api_builder_id') // Assuming the API column is 'api_id'
            ->toArray();

        // Find the APIs to add and remove
        $apisToAdd = array_diff($apis, $existingApis);
        $apisToRemove = array_diff($existingApis, $apis);

        // Insert new APIs
        foreach ($apisToAdd as $apiId) {
            DB::table('project_api_builder')->insert([
                'project_id' => $projectId,
                'api_builder_id' => $apiId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Remove APIs that are no longer needed
        if (!empty($apisToRemove)) {
            DB::table('project_api_builder')
                ->where('project_id', $projectId)
                ->whereIn('api_builder_id', $apisToRemove)
                ->delete();
        }

        return response()->json(['status' => 200, 'message' => 'APIs successfully updated']);
    }

}
