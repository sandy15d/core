<?php

namespace App\Http\Controllers\Grade;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Grade\Grade;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GradeController extends Controller
{
    public function index()
    {
        $grade_list = Grade::all();
        return view('grade.grade_list', compact('grade_list'));
    }

    public function create()
    {
        $data = new Grade();
        $level_list = DB::table('level')->get();
        return view('grade.grade_form', compact('data', 'level_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level_id' => 'required',
            'grade_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Grade::create($request->all()); 
     return redirect()->route('grade.index')->with('toast_success', 'Grade Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Grade::findOrFail($id);
        $level_list = DB::table('level')->get();
        return view('grade.grade_form', compact('data', 'level_list'));
    }

    public function update(Request $request, Grade $grade)
    {
        $validator = Validator::make($request->all(), [
            'level_id' => 'required',
            'grade_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $grade->update($request->all());
     return redirect()->route('grade.index')->with('toast_success', 'Grade Updated Successfully!');
    }

    public function destroy(Grade $grade)
    {
        $grade->delete();
        return redirect()->route('grade.index')->with('toast_success', 'Grade Deleted Successfully!');
    }
}
