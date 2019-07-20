<?php


namespace App\Lendesk\ImageTool;


use Illuminate\View\View;

final class HTMLImageDataWriter implements ImageDataWriter
{

    /**
     * @inheritdoc
     */
    public function output(array $listOfData, string $pathToWrite): void
    {
        /** @var View $html */
        $html   = view('image_tool.exif.htmloutput',
            [
                'headers' => ['name', 'latitude', 'longitude'],
                'exifs'   => $listOfData,
            ]);
        $output = $html->render();

        if (!is_writable($pathToWrite)) {
            throw new \InvalidArgumentException("Output path given is not writable {$pathToWrite}");
        }

        $fp = fopen($pathToWrite . '/exif_data.html', 'c');
        fwrite($fp, $output);
        fclose($fp);
    }
}