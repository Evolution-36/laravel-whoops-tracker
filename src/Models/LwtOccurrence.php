<?php

namespace Evolution36\WhoopsTracker\Models;

use Illuminate\Database\Eloquent\Model;

class LwtOccurrence extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'occurred_at'
    ];

    protected $casts = [
        'occurred_at' => 'datetime:Y-m-d'
    ];

    public function lwtWhoops()
    {
        return $this->belongsTo('Evolution36\WhoopsTracker\Models\LwtWhoops');
    }
}
