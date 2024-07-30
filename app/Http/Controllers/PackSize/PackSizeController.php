<?php

namespace App\Http\Controllers\PackSize;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PackSize\PackSize;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PackSizeController extends Controller
{
    public function index()
    {
        $pack_size_list = PackSize::all();
        return view('pack_size.pack_size_list', compact('pack_size_list'));
    }

    public function create()
    {
        $data = new PackSize();
        return view('pack_size.pack_size_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pack_size' => 'required',
            'pack_size_code' => 'required',
            'effective_date' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      PackSize::create($request->all()); 
     return redirect()->route('pack_size.index')->with('toast_success', 'PackSize Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = PackSize::findOrFail($id);
        return view('pack_size.pack_size_form', compact('data'));
    }

    public function update(Request $request, PackSize $pack_size)
    {
        $validator = Validator::make($request->all(), [
            'pack_size' => 'required',
            'pack_size_code' => 'required',
            'effective_date' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $pack_size->update($request->all());
     return redirect()->route('pack_size.index')->with('toast_success', 'PackSize Updated Successfully!');
    }

    public function destroy(PackSize $pack_size)
    {
        $pack_size->delete();
        return redirect()->route('pack_size.index')->with('toast_success', 'PackSize Deleted Successfully!');
    }
}
