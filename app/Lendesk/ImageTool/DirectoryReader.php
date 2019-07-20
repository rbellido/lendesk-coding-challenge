<?php


namespace App\Lendesk\ImageTool;


/**
 * Class responsible for scanning images in a directory
 *
 * @package App\Lendesk\ImageTool
 */
final class DirectoryReader
{
    private const IMAGE_FILE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif', 'ico', 'svg', 'tiff', 'tif'];

    /** @var LocalFilesystemFactory $filesystemFactory */
    private $filesystemFactory;

    /**
     * DirectoryReader constructor.
     *
     * @param LocalFilesystemFactory $filesystemFactory
     */
    public function __construct(LocalFilesystemFactory $filesystemFactory)
    {
        $this->filesystemFactory = $filesystemFactory;
    }

    /**
     * Recursively scans for any image files in the given directory.
     *
     * @param string $dirPath
     *
     * @return string[]
     */
    public function scanForImagesInDirectory(string $dirPath): array
    {
        $imagesInDirectory = [];
        $filesystem        = $this->filesystemFactory->createFilesystemFromDirectory($dirPath);

        foreach ($filesystem->listContents('.', true) as $content) {
            if (!array_key_exists('extension', $content)) {
                continue;
            }

            if (!in_array($content['extension'], self::IMAGE_FILE_EXTENSIONS)) {
                continue;
            }

            $imagesInDirectory[] = sprintf("%s/%s", $dirPath, $content['path']);
        }

        return $imagesInDirectory;
    }
}