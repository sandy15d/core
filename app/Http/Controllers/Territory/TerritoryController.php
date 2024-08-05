<?php

namespace App\Http\Controllers\Territory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Territory\Territory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TerritoryController extends Controller
{
    public function index()
    {
        $territory_list = Territory::all();
        return view('territory.territory_list', compact('territory_list'));
    }

    public function create()
    {
        $data = new Territory();
        return view('territory.territory_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'territory_name' => 'required',
            'territory_code' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Territory::create($request->all()); 
     return redirect()->route('territory.index')->with('toast_success', 'Territory Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Territory::findOrFail($id);
        return view('territory.territory_form', compact('data'));
    }

    public function update(Request $request, Territory $territory)
    {
        $validator = Validator::make($request->all(), [
            'territory_name' => 'required',
            'territory_code' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $territory->update($request->all());
     return redirect()->route('territory.index')->with('toast_success', 'Territory Updated Successfully!');
    }

    public function destroy(Territory $territory)
    {
        $territory->delete();
        return redirect()->route('territory.index')->with('toast_success', 'Territory Deleted Successfully!');
    }
}
