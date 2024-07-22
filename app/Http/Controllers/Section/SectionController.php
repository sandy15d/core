<?php

namespace App\Http\Controllers\Section;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Section\Section;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{
    public function index()
    {
        $section_list = Section::all();
        return view('section.section_list', compact('section_list'));
    }

    public function create()
    {
        $data = new Section();
        return view('section.section_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_name' => 'required',
            'numeric_code' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Section::create($request->all()); 
     return redirect()->route('section.index')->with('toast_success', 'Section Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Section::findOrFail($id);
        return view('section.section_form', compact('data'));
    }

    public function update(Request $request, Section $section)
    {
        $validator = Validator::make($request->all(), [
            'section_name' => 'required',
            'numeric_code' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $section->update($request->all());
     return redirect()->route('section.index')->with('toast_success', 'Section Updated Successfully!');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->route('section.index')->with('toast_success', 'Section Deleted Successfully!');
    }
}
