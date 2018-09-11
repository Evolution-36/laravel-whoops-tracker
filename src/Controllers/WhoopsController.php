<?php

namespace Evolution36\WhoopsTracker\Controllers;

use Evolution36\WhoopsTracker\Models\LwtWhoops;
use Illuminate\Routing\Controller;

class WhoopsController extends Controller
{
    public function viewer()
    {
        return view('lwt::index')->with([
            'whoopses' => LwtWhoops::get(),
        ]);
    }

    public function index()
    {
        return response()->json(LwtWhoops::all());
    }

    public function show($whoopsId)
    {
        $whoops = LwtWhoops::with('lwtOccurrences')->find($whoopsId);
        debug($whoops);
        if ($whoops) {
            return response()->json($whoops);
        } else {
            abort(404);
        }
    }
}
