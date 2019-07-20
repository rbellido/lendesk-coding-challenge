<?php


namespace App\Lendesk\ImageTool;


final class ExifExtractor
{
    private const SUPPORTED_MIME_TYPES = ['image/jpeg', 'image/tiff'];

    /**
     * Given a list of image paths (e.g. '/some/path/cat.jpg'), extract EXIF data for each image.
     *
     * @param array $listOfImagePaths
     *
     * @return ExifData[]
     */
    public function extractFromList(array $listOfImagePaths): array
    {
        if (empty($listOfImagePaths)) {
            return [];
        }

        $exifs = [];
        foreach ($listOfImagePaths as $imagePath) {
            $exifs[] = $this->extract($imagePath);
        }

        return $exifs;
    }

    /**
     * Extract EXIF data from given image
     *
     * @param string $imagePath
     *
     * @return ExifData
     */
    public function extract(string $imagePath): ExifData
    {
        return new ExifData(basename($imagePath), $this->extractRawExifData($imagePath));
    }

    /**
     * Extract EXIF data from given image
     *
     * @param string $imagePath
     *
     * @return array
     */
    private function extractRawExifData(string $imagePath): array
    {
        if (!is_file($imagePath) || !file_exists($imagePath)) {
            throw new \InvalidArgumentException("Image given {$imagePath} is not a file or does not exist");
        }

        if (!in_array(mime_content_type($imagePath), self::SUPPORTED_MIME_TYPES)) {
            throw new \InvalidArgumentException("{$imagePath} MIME type is not supported");
        }

        $data = exif_read_data($imagePath, 0, true);

        if (!$data) {
            return [];
        }

        return $data;
    }
}