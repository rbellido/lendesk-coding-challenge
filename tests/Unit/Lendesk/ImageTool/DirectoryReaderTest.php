<?php

namespace Tests\Unit\Lendesk\ImageTool;

use App\Lendesk\ImageTool\DirectoryReader;
use App\Lendesk\ImageTool\LocalFilesystemFactory;
use League\Flysystem\Adapter\NullAdapter;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\TestCase;

class DirectoryReaderTest extends TestCase
{

    /**
     * @test
     */
    public function scanForImagesInDirectory()
    {
        $mockFilesystem = $this->getFakeFilesystem();

        $mockFilesystemFactory = $this->getMockBuilder(LocalFilesystemFactory::class)
            ->setMethods(['createFilesystemFromDirectory'])
            ->getMock();

        $mockFilesystemFactory->expects($this->once())
            ->method('createFilesystemFromDirectory')
            ->willReturn($mockFilesystem);

        $reader = new DirectoryReader($mockFilesystemFactory);
        $actual = $reader->scanForImagesInDirectory('/some/path/gps_images');

        $expected = [
            '/some/path/gps_images/cats/image_e.jpg',
            '/some/path/gps_images/image_a.jpg',
            '/some/path/gps_images/image_b.jpg',
            '/some/path/gps_images/image_c.jpg',
            '/some/path/gps_images/image_d.jpg',
        ];

        $this->assertEquals($expected, $actual);
    }

    private function getFakeFilesystem(): Filesystem
    {
        return new Filesystem(new class extends NullAdapter
        {
            public function listContents($directory = '', $recursive = false)
            {
                return [
                    [
                        "type"      => "file",
                        "path"      => ".DS_Store",
                        "timestamp" => 1490834825,
                        "size"      => 6148,
                        "dirname"   => "",
                        "basename"  => ".DS_Store",
                        "extension" => "DS_Store",
                        "filename"  => "",
                    ],
                    [
                        "type"      => "dir",
                        "path"      => "cats",
                        "timestamp" => 1563392888,
                        "dirname"   => "",
                        "basename"  => "cats",
                        "filename"  => "cats",
                    ],
                    [
                        "type"      => "file",
                        "path"      => "cats/.DS_Store",
                        "timestamp" => 1421436690,
                        "size"      => 6148,
                        "dirname"   => "cats",
                        "basename"  => ".DS_Store",
                        "extension" => "DS_Store",
                        "filename"  => "",
                    ],
                    [
                        "type"      => "file",
                        "path"      => "cats/image_e.jpg",
                        "timestamp" => 1421436408,
                        "size"      => 446759,
                        "dirname"   => "cats",
                        "basename"  => "image_e.jpg",
                        "extension" => "jpg",
                        "filename"  => "image_e",
                    ],
                    [
                        "type"      => "file",
                        "path"      => "image_a.jpg",
                        "timestamp" => 1303677104,
                        "size"      => 1313003,
                        "dirname"   => "",
                        "basename"  => "image_a.jpg",
                        "extension" => "jpg",
                        "filename"  => "image_a",
                    ],
                    [
                        "type"      => "file",
                        "path"      => "image_b.jpg",
                        "timestamp" => 1421436418,
                        "size"      => 97113,
                        "dirname"   => "",
                        "basename"  => "image_b.jpg",
                        "extension" => "jpg",
                        "filename"  => "image_b",
                    ],
                    [
                        "type"      => "file",
                        "path"      => "image_c.jpg",
                        "timestamp" => 1421436446,
                        "size"      => 1222207,
                        "dirname"   => "",
                        "basename"  => "image_c.jpg",
                        "extension" => "jpg",
                        "filename"  => "image_c",
                    ],
                    [
                        "type"      => "file",
                        "path"      => "image_d.jpg",
                        "timestamp" => 1421436470,
                        "size"      => 126502,
                        "dirname"   => "",
                        "basename"  => "image_d.jpg",
                        "extension" => "jpg",
                        "filename"  => "image_d",
                    ],
                ];
            }
        });
    }
}
