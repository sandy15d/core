<?php

namespace App\Http\Controllers\Village;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VillageController extends Controller
{

    public function index()
    {
        return view('village.village_list');
    }

    public function create()
    {
        return "Create Page...";
    }
}
