<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
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
            'project_name' => 'required|unique:project' . $project,
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
}
