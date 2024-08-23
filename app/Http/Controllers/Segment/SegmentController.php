<?php

namespace App\Http\Controllers\Segment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Segment\Segment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SegmentController extends Controller
{
    public function index()
    {
        $segment_list = Segment::all();
        return view('segment.segment_list', compact('segment_list'));
    }

    public function create()
    {
        $data = new Segment();
        $crop_list = DB::table('crop')->get();
        return view('segment.segment_form', compact('data', 'crop_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'crop_id' => 'required',
            'segment_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Segment::create($request->all()); 
     return redirect()->route('segment.index')->with('toast_success', 'Segment Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Segment::findOrFail($id);
        $crop_list = DB::table('crop')->get();
        return view('segment.segment_form', compact('data', 'crop_list'));
    }

    public function update(Request $request, Segment $segment)
    {
        $validator = Validator::make($request->all(), [
            'crop_id' => 'required',
            'segment_name' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $segment->update($request->all());
     return redirect()->route('segment.index')->with('toast_success', 'Segment Updated Successfully!');
    }

    public function destroy(Segment $segment)
    {
        $segment->delete();
        return redirect()->route('segment.index')->with('toast_success', 'Segment Deleted Successfully!');
    }
}
