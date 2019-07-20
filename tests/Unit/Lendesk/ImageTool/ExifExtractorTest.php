<?php

namespace Tests\Unit\Lendesk\ImageTool;

use App\Lendesk\ImageTool\ExifData;
use App\Lendesk\ImageTool\ExifExtractor;
use PHPUnit\Framework\TestCase;
use Tests\Helpers\InteractsWithFixtures;

class ExifExtractorTest extends TestCase
{
    use InteractsWithFixtures;

    /**
     * @test
     */
    public function extractFromEmptyList()
    {
        $expected = [];
        $actual   = (new ExifExtractor())->extractFromList([]);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function extractFromList()
    {
        $expected = [
            new ExifData('image_a.jpg', [
                'GPS' => [
                    "GPSVersion"      => "\x02\x02\0\0",
                    "GPSLatitudeRef"  => "N",
                    "GPSLatitude"     => [
                        "50/1",
                        "548/100",
                        "0/1",
                    ],
                    "GPSLongitudeRef" => "W",
                    "GPSLongitude"    => [
                        "122/1",
                        "5674/100",
                        "0/1",
                    ],
                    "GPSTimeStamp"    => [
                        "13/1",
                        "31/1",
                        "4400/1",
                    ],
                ],
            ]),
            new ExifData('image_b.jpg', [
                [
                    "FILE"     => [
                        "FileName"      => "image_b.jpg",
                        "FileDateTime"  => 1421436418,
                        "FileSize"      => 97113,
                        "FileType"      => 2,
                        "MimeType"      => "image/jpeg",
                        "SectionsFound" => "",
                    ],
                    "COMPUTED" => [
                        "html"    => 'width="1024" height="683"',
                        "Height"  => 683,
                        "Width"   => 1024,
                        "IsColor" => 1,
                    ],
                ],
            ]),
            new ExifData('image_c.jpg', [
                'GPS' => [
                    "GPSLatitudeRef"  => "N",
                    "GPSLatitude"     => [
                        "38/1",
                        "2400/100",
                        "0/1",
                    ],
                    "GPSLongitudeRef" => "W",
                    "GPSLongitude"    => [
                        "122/1",
                        "4972/100",
                        "0/1",
                    ],
                ],
            ]),
            new ExifData('image_d.jpg', []),
        ];

        $actual = (new ExifExtractor())->extractFromList([
            self::getFixturesDirectory() . '/images/image_a.jpg',
            self::getFixturesDirectory() . '/images/image_b.jpg',
            self::getFixturesDirectory() . '/images/image_c.jpg',
            self::getFixturesDirectory() . '/images/image_d.jpg',
        ]);

        //TODO maybe add more of hte EXIF data in $expected

        foreach ($actual as $exifData) {
            $this->assertInstanceOf(ExifData::class, $exifData);
        }
    }
}
