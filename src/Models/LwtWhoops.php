<?php

namespace Evolution36\WhoopsTracker\Models;

use Illuminate\Database\Eloquent\Model;

class LwtWhoops extends Model
{
    protected $table = 'lwt_whoopses';

    protected $fillable = [
        'hash',
        'file',
        'line',
        'message',
        'log_level',
        'exception_class',
        'status',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $appends = [
        'last_occurred_at',
        'occurrences_count',
    ];

    const OPEN = 0;
    const CLOSED = 1;

    const STATUS = [
        self::OPEN   => 'Open',
        self::CLOSED => 'Closed',
    ];

    public function lwtOccurrences()
    {
        return $this->hasMany('Evolution36\WhoopsTracker\Models\LwtOccurrence');
    }

    public static function generateHash($file, $line, $message, $logLevel, $exceptionClass)
    {
        return hash('sha256', $file.$line.$message.$logLevel.$exceptionClass);
    }

    public function isOpen()
    {
        return $this->status == $this::OPEN;
    }

    public function isClosed()
    {
        return $this->status == $this::CLOSED;
    }

    public function getLastOccurredAtAttribute()
    {
        return $this->lwtOccurrences()->orderBy('occurred_at', 'DESC')->first()->occurred_at->format('c');
    }

    public function getOccurrencesCountAttribute()
    {
        return $this->lwtOccurrences()->count();
    }
}
