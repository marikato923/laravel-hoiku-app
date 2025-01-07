<?php

namespace App\Http\Controllers;

use App\Models\Child;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $children = Child::where('user_id', auth()->id())->get();

        return view('home', compact('children'));
    }
}
