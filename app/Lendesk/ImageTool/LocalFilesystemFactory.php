<?php


namespace App\Lendesk\ImageTool;


use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;

class LocalFilesystemFactory
{
    public function createFilesystemFromDirectory(string $directory): FilesystemInterface
    {
        return new Filesystem(new Local($directory, LOCK_EX, Local::SKIP_LINKS));
    }
}