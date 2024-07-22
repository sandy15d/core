<?php

namespace App\Http\Controllers\Variety;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Variety\Variety;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VarietyController extends Controller
{
    public function index()
    {
        $variety_list = Variety::all();
        return view('variety.variety_list', compact('variety_list'));
    }

    public function create()
    {
        $data = new Variety();
        $crop_list = DB::table('crop')->get();
        return view('variety.variety_form', compact('data', 'crop_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'crop_id' => 'required',
            'variety_name' => 'required',
            'numeric_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Variety::create($request->all()); 
     return redirect()->route('variety.index')->with('toast_success', 'Variety Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Variety::findOrFail($id);
        $crop_list = DB::table('crop')->get();
        return view('variety.variety_form', compact('data', 'crop_list'));
    }

    public function update(Request $request, Variety $variety)
    {
        $validator = Validator::make($request->all(), [
            'crop_id' => 'required',
            'variety_name' => 'required',
            'numeric_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $variety->update($request->all());
     return redirect()->route('variety.index')->with('toast_success', 'Variety Updated Successfully!');
    }

    public function destroy(Variety $variety)
    {
        $variety->delete();
        return redirect()->route('variety.index')->with('toast_success', 'Variety Deleted Successfully!');
    }
}
