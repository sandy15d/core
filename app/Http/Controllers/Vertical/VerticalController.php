<?php

namespace App\Http\Controllers\Vertical;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Vertical\Vertical;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VerticalController extends Controller
{
    public function index()
    {
        $vertical_list = Vertical::all();
        return view('vertical.vertical_list', compact('vertical_list'));
    }

    public function create()
    {
        $data = new Vertical();
        return view('vertical.vertical_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vertical_name' => 'required',
            'vertical_code' => 'required',
            'is_active' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Vertical::create($request->all()); 
     return redirect()->route('vertical.index')->with('toast_success', 'Vertical Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Vertical::findOrFail($id);
        return view('vertical.vertical_form', compact('data'));
    }

    public function update(Request $request, Vertical $vertical)
    {
        $validator = Validator::make($request->all(), [
            'vertical_name' => 'required',
            'vertical_code' => 'required',
            'is_active' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $vertical->update($request->all());
     return redirect()->route('vertical.index')->with('toast_success', 'Vertical Updated Successfully!');
    }

    public function destroy(Vertical $vertical)
    {
        $vertical->delete();
        return redirect()->route('vertical.index')->with('toast_success', 'Vertical Deleted Successfully!');
    }
}
