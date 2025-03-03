<?php

namespace Vaneetjoshi\LaravelFilemanager\Events;

class FileWasUploaded
{
    private $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function path()
    {
        return $this->path;
    }
}
