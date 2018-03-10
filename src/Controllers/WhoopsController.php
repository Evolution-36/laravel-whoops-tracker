<?php

namespace Evolution36\WhoopsTracker\Controllers;

use Evolution36\WhoopsTracker\Models\LwtWhoops;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WhoopsController extends Controller
{
    public function index()
    {
        return view('whoops-tracker::index')->with([
            'whoopses' => LwtWhoops::with('lwtOccurrences')->get()
        ]);
    }
}
