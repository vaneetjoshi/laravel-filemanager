<?php

namespace Vaneetjoshi\LaravelFilemanager\Events;

class FileIsUploading
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
