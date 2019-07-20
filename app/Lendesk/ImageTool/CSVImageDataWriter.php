<?php


namespace App\Lendesk\ImageTool;


final class CSVImageDataWriter implements ImageDataWriter
{
    /**
     * @inheritdoc
     */
    public function output(array $listOfData, string $pathToWrite): void
    {
        $outputStructure = [
            ['name', 'latitude', 'longitude'],
        ];

        /** @var ExifData $data */
        foreach ($listOfData as $data) {
            $latitude  = ($data->getLatitude()) ?? '';
            $longitude = ($data->getLongitude()) ?? '';

            array_push($outputStructure, [$data->getName(), $latitude, $longitude]);
        }

        if (!is_writable($pathToWrite)) {
            throw new \InvalidArgumentException("Output path given is not writable {$pathToWrite}");
        }

        $fp = fopen($pathToWrite . '/exif_data.csv', 'c');
        foreach ($outputStructure as $row) {
            fputcsv($fp, $row);
        }
        fclose($fp);
    }
}