<?php

namespace App\Http\Controllers\Level;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Level\Level;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LevelController extends Controller
{
    public function index()
    {
        $level_list = Level::all();
        return view('level.level_list', compact('level_list'));
    }

    public function create()
    {
        $data = new Level();
        return view('level.level_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level_name' => 'required',
            'level_code' => 'required',
            'effective_date' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Level::create($request->all()); 
     return redirect()->route('level.index')->with('toast_success', 'Level Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Level::findOrFail($id);
        return view('level.level_form', compact('data'));
    }

    public function update(Request $request, Level $level)
    {
        $validator = Validator::make($request->all(), [
            'level_name' => 'required',
            'level_code' => 'required',
            'effective_date' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $level->update($request->all());
     return redirect()->route('level.index')->with('toast_success', 'Level Updated Successfully!');
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return redirect()->route('level.index')->with('toast_success', 'Level Deleted Successfully!');
    }
}
