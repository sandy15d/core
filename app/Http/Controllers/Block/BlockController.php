<?php

namespace App\Http\Controllers\Block;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Block\Block;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BlockController extends Controller
{
    public function index()
    {
        $block_list = Block::all();
        return view('block.block_list', compact('block_list'));
    }

    public function create()
    {
        $data = new Block();
        $district_list = DB::table('district')->get();
        return view('block.block_form', compact('data', 'district_list'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'district_id' => 'required',
            'block_name' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Block::create($request->all()); 
     return redirect()->route('block.index')->with('toast_success', 'Block Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Block::findOrFail($id);
        $district_list = DB::table('district')->get();
        return view('block.block_form', compact('data', 'district_list'));
    }

    public function update(Request $request, Block $block)
    {
        $validator = Validator::make($request->all(), [
            'district_id' => 'required',
            'block_name' => 'required',
            'effective_date' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $block->update($request->all());
     return redirect()->route('block.index')->with('toast_success', 'Block Updated Successfully!');
    }

    public function destroy(Block $block)
    {
        $block->delete();
        return redirect()->route('block.index')->with('toast_success', 'Block Deleted Successfully!');
    }
}
