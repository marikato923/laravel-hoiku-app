<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Term;

class TermController extends Controller
{
    public function show()
    {
        $terms = Term::first();

        return view('terms.show', compact('terms'));
    }
}
