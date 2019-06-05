<?php


namespace App\Console\Commands\Traits;


trait ConsoleOutputLog
{

    protected $debugMode = false;

    /**
     * @param $message
     */
    protected function logMessage($message)
    {
        if ($this->debugMode) {
            $date = date('Y-m-d H:i:s');

            $this->line($date.' | '.$message);
        }
    }
}