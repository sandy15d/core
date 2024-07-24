<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiBuilderController extends Controller
{
   public function index()
   {
       return view("api_builder.api_list");
   }
}
