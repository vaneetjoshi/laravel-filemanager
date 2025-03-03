<?php

use Mockery as m;
use Illuminate\Foundation\Testing\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vaneetjoshi\LaravelFilemanager\Exceptions\DuplicateFileNameException;
use Vaneetjoshi\LaravelFilemanager\Exceptions\EmptyFileException;
use Vaneetjoshi\LaravelFilemanager\Exceptions\ExcutableFileException;
use Vaneetjoshi\LaravelFilemanager\Exceptions\FileFailedToUploadException;
use Vaneetjoshi\LaravelFilemanager\Exceptions\FileSizeExceedConfigurationMaximumException;
use Vaneetjoshi\LaravelFilemanager\Exceptions\FileSizeExceedIniMaximumException;
use Vaneetjoshi\LaravelFilemanager\Exceptions\InvalidExtensionException;
use Vaneetjoshi\LaravelFilemanager\Exceptions\InvalidMimeTypeException;
use Vaneetjoshi\LaravelFilemanager\LfmPath;
use Vaneetjoshi\LaravelFilemanager\LfmUploadValidator;

class LfmUploadValidatorTest extends TestCase
{
    public function testPassesSizeLowerThanIniMaximum()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getError')->andReturn(UPLOAD_ERR_OK);

        $validator = new LfmUploadValidator($uploaded_file);

        $this->assertEquals($validator->sizeLowerThanIniMaximum(), $validator);
    }

    public function testFailsSizeLowerThanIniMaximum()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getError')->andReturn(UPLOAD_ERR_INI_SIZE);

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(FileSizeExceedIniMaximumException::class);

        $validator->sizeLowerThanIniMaximum();
    }

    public function testPassesUploadWasSuccessful()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getError')->andReturn(UPLOAD_ERR_OK);

        $validator = new LfmUploadValidator($uploaded_file);

        $this->assertEquals($validator->uploadWasSuccessful(), $validator);
    }

    public function testFailsUploadWasSuccessful()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getError')->andReturn(UPLOAD_ERR_PARTIAL);

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(FileFailedToUploadException::class);

        $validator->uploadWasSuccessful();
    }

    public function testPassesNameIsNotDuplicate()
    {
        $uploaded_file = m::mock(UploadedFile::class);

        $lfm_path = m::mock(LfmPath::class);
        $lfm_path->shouldReceive('setName')->andReturn($lfm_path);
        $lfm_path->shouldReceive('exists')->andReturn(false);

        $validator = new LfmUploadValidator($uploaded_file);

        $this->assertEquals($validator->nameIsNotDuplicate('new_file_name', $lfm_path), $validator);
    }

    public function testFailsNameIsNotDuplicate()
    {
        $uploaded_file = m::mock(UploadedFile::class);

        $lfm_path = m::mock(LfmPath::class);
        $lfm_path->shouldReceive('setName')->andReturn($lfm_path);
        $lfm_path->shouldReceive('exists')->andReturn(true);

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(DuplicateFileNameException::class);

        $validator->nameIsNotDuplicate('new_file_name', $lfm_path);
    }

    public function testPassesMimetypeIsNotExcutable()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getMimeType')->andReturn('image/jpeg');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->assertEquals($validator->mimetypeIsNotExcutable(['text/x-php']), $validator);
    }

    public function testFailsMimetypeIsNotExcutable()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getMimeType')->andReturn('text/x-php');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(ExcutableFileException::class);

        $validator->mimetypeIsNotExcutable(['text/x-php']);
    }

    public function testPassesMimeTypeIsValid()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getMimeType')->andReturn('image/jpeg');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->assertEquals($validator->mimeTypeIsValid(['image/jpeg']), $validator);
    }

    public function testFailsMimeTypeIsValid()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getMimeType')->andReturn('image/jpeg');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(InvalidMimeTypeException::class);

        $validator->mimeTypeIsValid(['image/png']);
    }

    public function testPassesExtensionIsNotExcutable()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getClientOriginalExtension')->andReturn('jpeg');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectNotToPerformAssertions();

        $validator->extensionIsNotExcutable();
    }

    public function testFailsExtensionIsNotExcutableWithPhp()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getClientOriginalExtension')->andReturn('php');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(ExcutableFileException::class);

        $validator->extensionIsNotExcutable();
    }

    public function testFailsExtensionIsNotExcutableWithHtml()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getClientOriginalExtension')->andReturn('html');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(ExcutableFileException::class);

        $validator->extensionIsNotExcutable();
    }

    public function testFailsExtensionIsNotExcutableWithExtensionNotLowerCase()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getClientOriginalExtension')->andReturn('Html');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(ExcutableFileException::class);

        $validator->extensionIsNotExcutable();
    }

    public function testFailsExtensionIsNotExcutableWithExtensionsStartsWithPhp()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getClientOriginalExtension')->andReturn('php8');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(ExcutableFileException::class);

        $validator->extensionIsNotExcutable();
    }

    public function testFailsExtensionIsNotExcutableWithExtensionsEndsWithHtml()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getClientOriginalExtension')->andReturn('dhtml');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(ExcutableFileException::class);

        $validator->extensionIsNotExcutable();
    }

    public function testFailsExtensionIsValidWithSpecialCharacters()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getClientOriginalExtension')->andReturn('html@');

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(InvalidExtensionException::class);

        $validator->extensionIsValid([]);
    }

    public function testPassesSizeIsLowerThanConfiguredMaximum()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getSize')->andReturn(500 * 1000);

        $validator = new LfmUploadValidator($uploaded_file);

        $this->assertEquals($validator->sizeIsLowerThanConfiguredMaximum(1000), $validator);
    }

    public function testFailsSizeIsLowerThanConfiguredMaximum()
    {
        $uploaded_file = m::mock(UploadedFile::class);
        $uploaded_file->shouldReceive('getSize')->andReturn(2000 * 1000);

        $validator = new LfmUploadValidator($uploaded_file);

        $this->expectException(FileSizeExceedConfigurationMaximumException::class);

        $validator->sizeIsLowerThanConfiguredMaximum(1000);
    }
}
