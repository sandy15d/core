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
        return view('segment.segment_form', compact('data'));
    }

    public function store(Request $request)
    {
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
        return view('segment.segment_form', compact('data'));
    }

    public function update(Request $request, Segment $segment)
    {
        $segment->update($request->all());
     return redirect()->route('segment.index')->with('toast_success', 'Segment Updated Successfully!');
    }

    public function destroy(Segment $segment)
    {
        $segment->delete();
        return redirect()->route('segment.index')->with('toast_success', 'Segment Deleted Successfully!');
    }
}
