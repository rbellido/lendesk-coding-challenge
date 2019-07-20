# Lendesk Coding Challenge

## Requirements
See [Laravel 5.8 Requirements](https://laravel.com/docs/5.8/installation#server-requirements](Laravel requirements))

## Getting Started
1. Install composer:
```bash
composer install
```
2. Run the command:
```bash
php artisan image:extract_exif {directory_of_images}
```

See the command's help info for more details:
```bash
php artisan image:extract_exif --help
```

## Design Process
The coding challenge itself didn't require for the Laravel framework. And in my opinion Laravel is probably overkill for it. But given that this role requires knowledge of Laravel, I decided to showcase what I know about it by using it's built-in CLI features.
Of course, using Laravel saved me time completing this project too.

I began by outlining the key features that need to be done to complete the requirements of the project.
1. Read images from given directory
2. Extract EXIF GPS data from the images
3. Output: CSV, HTML, etc.

Afterwards, now I'm thinking about the code architecture. It's a step more detailed from the outline above, but it's not quite at the implementation detail.
I'll get to that soon.

The following is a rough design notes of the main classes. This is by no means a formal documentation itself. 
This is only an outline of the classes I want to create -- such as the dependencies, concrete classes, data objects, interfaces, etc.
I do this to help me plan how each classes interact with one another. It also helps me clarify my thinking on the requirements. 
Are there any unclear requirements? Can I make some reasonable assumptions? (See Questions & Assumptions section below).
I also do research in to libraries or any built-in PHP functions that may help me.

```
app/
    Console/
        ExtractImageDataCommand
            __construct(DirectoryReader, ExifExtractor, ImageDataWriter)
    Lendesk/ImageTool/
        ExifData
            +__construct(string $name, array $rawExifData)
            +getName(): string
            +getLatitude(): ?float
            +getLongitude(): ?float
        DirectoryReader
            +__construct(FileSystemFactory)
            +scanForImagesInDirectory(string $path): string[]
        ExifExtractor
            +extractFromList(array $listOfImages): ExifData[]
        interface ImageDataWriter
            +output(ExifData[], string $pathToWrite): void
        CSVImageDataWriter implements ImageDataWriter
        HTMLImageDataWriter implements ImageDataWriter
        ImageDataWriterFactory
            +create(string $outputType): ImageDataWriter
```

Then, I create a skeleton of the classes I outlined above. For example:
```php
<?php

namespace App\Lendesk\ImageTool;


/**
 * Class responsible for scanning images in a directory
 *
 * @package App\Lendesk\ImageTool
 */
final class DirectoryReader
{
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
        throw new \Exception("not yet implemented");
    }
}
```

Then I write some unit tests for the classes and their methods. 

Finally, write the implementation to pass the tests until the project is done.

### Tests
Run the unit tests with the following command:
```bash
./vendor/bin/phpunit
```

## Questions & Assumptions
* What should it do with images that don't have lat & long?
    - include in output. Leave lat and long empty.
* Where should it write the output? CSV, HTML file
    - I'll assume current working directory for now. But I'll also give an option to write to a directory.
