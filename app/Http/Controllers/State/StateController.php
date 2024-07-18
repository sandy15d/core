<?php

namespace App\Http\Controllers\State;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\State\State;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StateController extends Controller
{
    public function index()
    {
        $state_list = State::all();
        return view('state.state_list', compact('state_list'));
    }

    public function create()
    {
        $data = new State();
        $Country_list = DB::table('country')->get();
        return view('state.state_form', compact('data', 'Country_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'state_name' => 'required',
            'state_code' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      State::create($request->all()); 
     return redirect()->route('state.index')->with('toast_success', 'State Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = State::findOrFail($id);
        $Country_list = DB::table('Country')->get();
        return view('state.state_form', compact('data', 'Country_list'));
    }

    public function update(Request $request, State $state)
    {
        $validator = Validator::make($request->all(), [
            'country_id' => 'required',
            'state_name' => 'required',
            'state_code' => 'required',
            'is_active' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $state->update($request->all());
     return redirect()->route('state.index')->with('toast_success', 'State Updated Successfully!');
    }

    public function destroy(State $state)
    {
        $state->delete();
        return redirect()->route('state.index')->with('toast_success', 'State Deleted Successfully!');
    }
}
