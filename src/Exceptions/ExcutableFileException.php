<?php

namespace Vaneetjoshi\LaravelFilemanager\Exceptions;

class ExcutableFileException extends \Exception
{
    public function __construct()
    {
        $this->message = 'Invalid file detected';
    }
}
