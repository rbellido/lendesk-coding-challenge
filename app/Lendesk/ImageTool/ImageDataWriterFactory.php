<?php


namespace App\Lendesk\ImageTool;


final class ImageDataWriterFactory
{
    public const HTML = 'html';
    public const CSV  = 'csv';

    /**
     * Create an image EXIF data writer
     *
     * @param string $outputType
     *
     * @return ImageDataWriter
     * @throws \Exception
     */
    public function create(string $outputType): ImageDataWriter
    {
        switch (strtolower($outputType)) {
            case self::HTML:
                return new HTMLImageDataWriter();
            case self::CSV:
                return new CSVImageDataWriter();
            default:
                throw new \Exception("Unknown output type {$outputType}");
        }
    }
}