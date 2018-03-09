<?php

namespace Evolution36\WhoopsTracker;


use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
        $data = collect()
            ->put('datetime', Carbon::now())
            ->put('request', $this->requestToArray(request()))
            ->put('exception', $this->exceptionToArray($exception))
            ->put('config', config()->all())
            ->put('queries', DB::getQueryLog())
            ->put('fileContext', $this->fileContext($exception->getFile(), $exception->getLine()));

        $fileSystem = $this->app['files'];
        if (!$fileSystem->isDirectory(storage_path('whoops-tracker'))) {
            if ($fileSystem->makeDirectory(storage_path('whoops-tracker'), 0777, true)) {
                $fileSystem->put(storage_path('whoops-tracker') . '/.gitignore', "*\n!.gitignore\n");
            } else {
                throw new \Exception("Cannot create directory '$this->dirname'..");
            }
        }

        try {
            $fileSystem->put(storage_path('whoops-tracker') . '/' . Carbon::now(), json_encode($data));
        } catch (\Exception $e) {
            //TODO; error handling
        }
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
                $traceItem['inApp'] = strpos($traceItem['file'], base_path() . '/vendor') === false;
            }
            $traceWithContext[] = $traceItem;
        }
        return [
            'message' => $exception->getMessage(),
            'class'   => get_class($exception),
            'code'    => $exception->getCode(),
            'trace'   => $traceWithContext,
        ];
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
}
