<?php

namespace Evolution36\WhoopsTracker\Controllers;

use Evolution36\WhoopsTracker\Models\LwtWhoops;
use Illuminate\Routing\Controller;

class WhoopsController extends Controller
{
    public function index()
    {
        return view('lwt::index')->with([
            'whoopses' => LwtWhoops::with('lwtOccurrences')->get(),
        ]);
    }

    public function getAll()
    {
        return response()->json(LwtWhoops::all());
    }
}
