## List of events

* File
  * Vaneetjoshi\LaravelFilemanager\Events\FileIsUploading
  * Vaneetjoshi\LaravelFilemanager\Events\FileWasUploaded
  * Vaneetjoshi\LaravelFilemanager\Events\FileIsRenaming
  * Vaneetjoshi\LaravelFilemanager\Events\FileWasRenamed
  * Vaneetjoshi\LaravelFilemanager\Events\FileIsMoving
  * Vaneetjoshi\LaravelFilemanager\Events\FileWasMoving
  * Vaneetjoshi\LaravelFilemanager\Events\FileIsDeleting
  * Vaneetjoshi\LaravelFilemanager\Events\FileWasDeleted
* Image
  * Vaneetjoshi\LaravelFilemanager\Events\ImageIsUploading
  * Vaneetjoshi\LaravelFilemanager\Events\ImageWasUploaded
  * Vaneetjoshi\LaravelFilemanager\Events\ImageIsRenaming
  * Vaneetjoshi\LaravelFilemanager\Events\ImageWasRenamed
  * Vaneetjoshi\LaravelFilemanager\Events\ImageIsResizing
  * Vaneetjoshi\LaravelFilemanager\Events\ImageWasResized
  * Vaneetjoshi\LaravelFilemanager\Events\ImageIsCropping
  * Vaneetjoshi\LaravelFilemanager\Events\ImageWasCropped
  * Vaneetjoshi\LaravelFilemanager\Events\ImageIsDeleting
  * Vaneetjoshi\LaravelFilemanager\Events\ImageWasDeleted
* Folder
  * Vaneetjoshi\LaravelFilemanager\Events\FolderIsCreating
  * Vaneetjoshi\LaravelFilemanager\Events\FolderWasCreated
  * Vaneetjoshi\LaravelFilemanager\Events\FolderIsRenaming
  * Vaneetjoshi\LaravelFilemanager\Events\FolderWasRenamed
  * Vaneetjoshi\LaravelFilemanager\Events\FolderIsMoving
  * Vaneetjoshi\LaravelFilemanager\Events\FolderWasMoving
  * Vaneetjoshi\LaravelFilemanager\Events\FolderIsDeleting
  * Vaneetjoshi\LaravelFilemanager\Events\FolderWasDeleted

## How to use
 * Sample code : [laravel-filemanager-demo-events](https://github.com/UniSharp/laravel-filemanager-demo-events)
 * To use events you can add a listener to listen to the events.

    Snippet for `EventServiceProvider`

    ```php
    protected $listen = [
        ImageWasUploaded::class => [
            UploadListener::class,
        ],
    ];
    ```

    The `UploadListener` will look like:

    ```php
    class UploadListener
    {
        public function handle($event)
        {
            $method = 'on'.class_basename($event);
            if (method_exists($this, $method)) {
                call_user_func([$this, $method], $event);
            }
        }

        public function onImageWasUploaded(ImageWasUploaded $event)
        {
            $path = $event->path();
            //your code, for example resizing and cropping
        }
    }
    ```

 * Or by using Event Subscribers

    Snippet for `EventServiceProvider`

    ```php
    protected $subscribe = [
        UploadListener::class
    ];
    ```

    The `UploadListener` will look like:

    ```php
    public function subscribe($events)
    {
        $events->listen('*', UploadListener::class);
    }

    public function handle($event)
    {
        $method = 'on'.class_basename($event);
        if (method_exists($this, $method)) {
            call_user_func([$this, $method], $event);
        }
    }

    public function onImageWasUploaded(ImageWasUploaded $event)
    {
        $path = $event->path();
        // your code, for example resizing and cropping
    }

    public function onImageWasRenamed(ImageWasRenamed $event)
    {
        // image was renamed
    }

    public function onImageWasDeleted(ImageWasDeleted $event)
    {
        // image was deleted
    }

    public function onFolderWasRenamed(FolderWasRenamed $event)
    {
        // folder was renamed
    }
    ```
