<?php

namespace Evolution36\WhoopsTracker;

use Carbon\Carbon;
use Evolution36\WhoopsTracker\Models\LwtOccurrence;
use Evolution36\WhoopsTracker\Models\LwtWhoops;
use Illuminate\Contracts\Foundation\Application;

class WhoopsTracker
{
    protected $app;

    /**
     * @param Application $app
     */
    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();   //Fallback when $app is not given
        }
        $this->app = $app;
    }

    public function report(\Exception $exception)
    {
        $dateTime = Carbon::now();
        $data = collect()
            ->put('datetime', $dateTime)
            ->put('request', $this->requestToArray(request()))
            ->put('trace', $this->exceptionToArray($exception))
            ->put('config', config()->all())
            ->put('environment', $_ENV)
            ->put('fileContext', $this->fileContext($exception->getFile(), $exception->getLine()));

        $fileSystem = $this->app['files'];
        if (!$fileSystem->isDirectory(storage_path('whoops-tracker'))) {
            if ($fileSystem->makeDirectory(storage_path('whoops-tracker'), 0777, true)) {
                $fileSystem->put(storage_path('whoops-tracker').'/.gitignore', "*\n!.gitignore\n");
            } else {
                throw new \Exception("Cannot create directory '$this->dirname'..");
            }
        }

        $logLocation = storage_path('whoops-tracker').'/'.Carbon::now()
                ->format('d_m_Y_His').'-'.uniqid().'.json';

        try {
            $fileSystem->put($logLocation, json_encode($data));
        } catch (\Exception $e) {
            //TODO; error handling
        }

        $this->saveWhoops($exception->getFile(), $exception->getLine(), $dateTime, $exception->getMessage(), $exception->getCode(), get_class($exception), $logLocation);
    }

    protected function exceptionToArray(\Exception $exception)
    {
        $trace = $exception->getTrace();
        $traceWithContext = [];
        $firstTraceItem = ['file' => $exception->getFile(), 'line' => $exception->getLine()];
        array_unshift($trace, $firstTraceItem);
        foreach ($trace as $traceItem) {
            if (array_key_exists('file', $traceItem)) {
                $traceItem['context'] = $this->fileContext($traceItem['file'], $traceItem['line']);
                $traceItem['inApp'] = strpos($traceItem['file'], base_path().'/vendor') === false;
            }
            $traceWithContext[] = $traceItem;
        }

        return $traceWithContext;
    }

    protected function requestToArray($request)
    {
        return collect(get_object_vars($request))->map(function ($item, $key) {
            return $item->all();
        });
    }

    protected function fileContext($fileName, $lineNumber)
    {
        $context = collect();
        $context->put('pre', collect());
        $context->put('post', collect());
        $contextLines = 5;
        if (file_exists($fileName)) {
            try {
                $file = new \SplFileObject($fileName);
                $target = max(0, ($lineNumber - ($contextLines + 1)));
                $file->seek($target);
                $currentLineNumber = $target + 1;

                while (!$file->eof()) {
                    $currentLine = rtrim($file->current(), "\r\n");
                    if ($currentLineNumber == $lineNumber) {
                        $context->put('self', $currentLine);
                    } else {
                        if ($currentLineNumber < $lineNumber) {
                            $context->get('pre')->push($currentLine);
                        } else {
                            if ($currentLineNumber > $lineNumber) {
                                $context->get('post')->push($currentLine);
                            }
                        }
                    }
                    $currentLineNumber++;
                    if ($currentLineNumber > $lineNumber + $contextLines) {
                        break;
                    }
                    $file->next();
                }
            } catch (\RuntimeException $exc) {
                return $context;
            }
        }

        return $context;
    }

    protected function saveWhoops($file, $line, $dateTime, $message, $logLevel, $exceptionClass, $logLocation)
    {
        $hash = LwtWhoops::generateHash($file, $line, $message, $logLevel, $exceptionClass);
        $whoops = LwtWhoops::firstOrCreate([
            'hash'            => $hash,
            'file'            => $file,
            'line'            => $line,
            'message'         => $message,
            'log_level'       => $logLevel,
            'exception_class' => $exceptionClass,
            'status'          => LwtWhoops::OPEN,
        ]);

        $occurrence = new LwtOccurrence();
        $occurrence->occurred_at = $dateTime;
        $occurrence->log_location = $logLocation;
        $whoops->lwtOccurrences()->save($occurrence);
    }
}
