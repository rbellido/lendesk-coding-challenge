<?php


namespace App\Lendesk\ImageTool;


interface ImageDataWriter
{
    /**
     * Output the list of EXIF data to the file path given
     *
     * @param ExifData[]  $listOfData
     * @param string $pathToWrite
     * @throws \InvalidArgumentException if $pathToWrite is not writable
     */
    public function output(array $listOfData, string $pathToWrite): void;
}