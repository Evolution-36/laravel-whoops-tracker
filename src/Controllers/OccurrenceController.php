<?php

namespace Evolution36\WhoopsTracker\Controllers;

use Evolution36\WhoopsTracker\Models\LwtOccurrence;
use Illuminate\Routing\Controller;

class OccurrenceController extends Controller
{
    public function show($occurrenceId)
    {
        $occurrence = LwtOccurrence::findOrFail($occurrenceId);
        $occurrence->log = $occurrence->log_file;
        return response()->json($occurrence);
    }
}
