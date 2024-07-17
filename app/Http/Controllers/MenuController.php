<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        return view('menu_builder.menu_builder');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'menu_name' => ['required'],
            'menu_url' => ['nullable'],
            'parent_id' => ['required', 'integer'],
            'menu_position' => ['required', 'integer'],
            'menu_type' => ['required'],
        ]);

        return Menu::create($data);

    }

    public function show(Menu $menu)
    {
        return $menu;
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'menu_name' => ['required'],
            'menu_url' => ['nullable'],
            'parent_id' => ['required', 'integer'],
            'menu_position' => ['required', 'integer'],
            'menu_type' => ['required'],
        ]);

        $menu->update($data);

        return $menu;
    }

    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json();
    }
}
