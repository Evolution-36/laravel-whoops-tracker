<?php

namespace Evolution36\WhoopsTracker\Models;

use Illuminate\Database\Eloquent\Model;

class LwtOccurrence extends Model
{
    protected $table = 'lwt_occurrences';

    protected $dates = [
        'created_at',
        'updated_at',
        'occurred_at',
    ];

    public function lwtWhoops()
    {
        return $this->belongsTo('Evolution36\WhoopsTracker\Models\LwtWhoops');
    }

    public function getLogFileAttribute()
    {
        $fileSystem = app()['files'];
        $file = json_decode($fileSystem->get($this->log_location));

        return $file;
    }
}
