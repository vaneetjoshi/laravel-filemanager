<?php

namespace Vaneetjoshi\LaravelFilemanager\Exceptions;

class InvalidExtensionException extends \Exception
{
    public function __construct()
    {
        $this->message = 'File extension is not valid.';
    }
}
