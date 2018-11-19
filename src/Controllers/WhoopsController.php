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
        return response()->json(LwtWhoops::get());
    }

    public function show($whoopsId)
    {
        $whoops = LwtWhoops::with('lwtOccurrences')->find($whoopsId);

        if ($whoops) {
            return response()->json($whoops);
        } else {
            abort(404);
        }
    }
}
