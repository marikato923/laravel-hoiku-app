<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kindergarten;

class KindergartenController extends Controller
{
    public function show()
    {
        $kindergarten = Kindergarten::first();

        return view('kindergarten.show', compact('kindergarten'));
    }
}
