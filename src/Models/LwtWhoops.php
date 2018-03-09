<?php

namespace Evolution36\WhoopsTracker\Models;

use Illuminate\Database\Eloquent\Model;

class LwtWhoops extends Model
{
    protected $table = 'lwt_whoopses';

    protected $fillable = [
        'file',
        'line',
        'message',
        'log_level',
        'exception_class'
    ];

    protected $dates = [
        'occurred_at',
        'created_at',
        'updated_at'
    ];

    public function increaseCount()
    {
        $this->count = $this->exists ? $this->count + 1 : 0;
    }

    public static function generateHash($file, $line, $message, $logLevel, $exceptionClass)
    {
        return hash('sha256', $file.$line.$message.$logLevel.$exceptionClass);
    }
}
