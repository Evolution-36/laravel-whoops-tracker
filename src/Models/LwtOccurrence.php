<?php

namespace Evolution36\WhoopsTracker\Models;

use Illuminate\Database\Eloquent\Model;

class LwtOccurrence extends Model
{
    protected $dates = [
        'occurred_at'
    ];

    public function lwtWhoops()
    {
        return $this->belongsTo('Evolution36\WhoopsTracker\Models\LwtWhoops');
    }
}
