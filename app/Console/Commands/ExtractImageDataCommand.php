<?php

namespace App\Console\Commands;

use App\Lendesk\ImageTool\DirectoryReader;
use App\Lendesk\ImageTool\ExifExtractor;
use App\Lendesk\ImageTool\ImageDataWriterFactory;
use Illuminate\Console\Command;
use Symfony\Component\Console\Output\OutputInterface;

final class ExtractImageDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'image:extract_exif 
                            {directory? : The directory of images. Defaults to current working directory.}
                            {--output-dir= : The directory to write the resulting file. Defaults to current working directory.}
                            {--output-as-html : Change output type as HTML.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Extracts EXIF data from a directory of images recursively and outputs to CSV (or HTML)';

    /** @var DirectoryReader $directoryReader */
    private $directoryReader;

    /** @var ExifExtractor $extractor */
    private $extractor;

    /** @var ImageDataWriterFactory $dataWriterFactory */
    private $dataWriterFactory;

    /**
     * Create a new command instance.
     *
     * @param DirectoryReader        $directoryReader
     * @param ExifExtractor          $extractor
     * @param ImageDataWriterFactory $dataWriterFactory
     */
    public function __construct(
        DirectoryReader $directoryReader,
        ExifExtractor $extractor,
        ImageDataWriterFactory $dataWriterFactory
    ) {
        parent::__construct();

        $this->directoryReader   = $directoryReader;
        $this->extractor         = $extractor;
        $this->dataWriterFactory = $dataWriterFactory;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $directory = $this->argument('directory');

        if (!$directory) {
            $directory = getcwd();
        }

        $outputDirectory = getcwd();
        if ($this->option('output-dir')) {
            $outputDirectory = $this->option('output-dir');
        }

        if (!file_exists($outputDirectory)) {
            mkdir($outputDirectory, 0777, true);
        }

        $exifData = [];
        $images   = $this->directoryReader->scanForImagesInDirectory($directory);

        $this->comment("The following images are about to be processed: ", OutputInterface::VERBOSITY_VERBOSE);
        $this->comment(implode("\n", $images), OutputInterface::VERBOSITY_VERBOSE);

        $totalImagesToProcess = count($images);
        $progressBar          = $this->output->createProgressBar($totalImagesToProcess);
        $this->info("Extracting EXIF data from {$totalImagesToProcess} images...");

        $progressBar->start();
        foreach ($images as $image) {
            $exifData[] = $this->extractor->extract($image);
            $progressBar->advance();
        }
        $progressBar->finish();

        $writerType = ImageDataWriterFactory::CSV;
        if ($this->option('output-as-html')) {
            $writerType = ImageDataWriterFactory::HTML;
        }

        $this->line("\n");
        $this->info("Writing EXIF output as {$writerType}...");
        $this->dataWriterFactory->create($writerType)->output($exifData, $outputDirectory);
        $this->info("Finished writing to {$outputDirectory}");
    }
}
