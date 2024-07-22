<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category_list = Category::all();
        return view('category.category_list', compact('category_list'));
    }

    public function create()
    {
        $data = new Category();
        return view('category.category_form', compact('data'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_code' => 'required',
            'numeric_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

      Category::create($request->all()); 
     return redirect()->route('category.index')->with('toast_success', 'Category Created Successfully!');
    }

    public function show($id)
    {
        // Your show logic here
    }

    public function edit($id)
    {
        $data = Category::findOrFail($id);
        return view('category.category_form', compact('data'));
    }

    public function update(Request $request, Category $category)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required',
            'category_code' => 'required',
            'numeric_code' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $category->update($request->all());
     return redirect()->route('category.index')->with('toast_success', 'Category Updated Successfully!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('category.index')->with('toast_success', 'Category Deleted Successfully!');
    }
}
