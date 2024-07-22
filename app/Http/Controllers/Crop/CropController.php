<?php

namespace App\Http\Controllers\Crop;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Crop\Crop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CropController extends Controller
{
    public function index()
    {
        $crop_list = Crop::all();
        return view('crop.crop_list', compact('crop_list'));
    }

    public function create()
    {
        $data = new Crop();
        $vertical_list = DB::table('vertical')->get();
        return view('crop.crop_form', compact('data', 'vertical_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vertical_id' => 'required',
            'crop_name' => 'required',
            'numeric_code' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Crop::create($request->all()); 
     return redirect()->route('crop.index')->with('toast_success', 'Crop Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Crop::findOrFail($id);
        $vertical_list = DB::table('vertical')->get();
        return view('crop.crop_form', compact('data', 'vertical_list'));
    }

    public function update(Request $request, Crop $crop)
    {
        $validator = Validator::make($request->all(), [
            'vertical_id' => 'required',
            'crop_name' => 'required',
            'numeric_code' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $crop->update($request->all());
     return redirect()->route('crop.index')->with('toast_success', 'Crop Updated Successfully!');
    }

    public function destroy(Crop $crop)
    {
        $crop->delete();
        return redirect()->route('crop.index')->with('toast_success', 'Crop Deleted Successfully!');
    }
}
