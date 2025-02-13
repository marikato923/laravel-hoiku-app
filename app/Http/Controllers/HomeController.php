<?php

namespace App\Http\Controllers;

use App\Models\Child;
use App\Models\Message;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $children = Child::with('classroom')->where('user_id', auth()->id())
            ->orderBy('birthdate')
            ->get();

        return view('home', compact('children'));
    }
}
