<?php

namespace Tests\Unit\Lendesk\ImageTool;

use App\Lendesk\ImageTool\ExifData;
use PHPUnit\Framework\TestCase;

class ExifDataTest extends TestCase
{

    public function getLatitudeDataProvider(): array
    {
        return [
            'test case with GPS'    => [
                [
                    "FILE"     => [
                        "FileName"      => "image_e.jpg",
                        "FileDateTime"  => 1421436408,
                        "FileSize"      => 446759,
                        "FileType"      => 2,
                        "MimeType"      => "image/jpeg",
                        "SectionsFound" => "ANY_TAG, IFD0, EXIF, GPS",
                    ],
                    "COMPUTED" => [
                        "html"              => 'width="1280" height="853"',
                        "Height"            => 853,
                        "Width"             => 1280,
                        "IsColor"           => 1,
                        "ByteOrderMotorola" => 0,
                        "ApertureFNumber"   => "f/2.2",
                        "Copyright"         => "Eirik Solheim - www.eirikso.com",
                    ],
                    "IFD0"     => [
                        "Make"             => "Canon",
                        "Model"            => "Canon EOS 400D DIGITAL",
                        "XResolution"      => "300/1",
                        "YResolution"      => "300/1",
                        "ResolutionUnit"   => 2,
                        "DateTime"         => "2008:08:05 23:48:04",
                        "Artist"           => "unknown",
                        "Copyright"        => "Eirik Solheim - www.eirikso.com",
                        "Exif_IFD_Pointer" => 240,
                        "GPS_IFD_Pointer"  => 610,
                    ],
                    "EXIF"     => [
                        "ExposureTime"             => "1/100",
                        "FNumber"                  => "22/10",
                        "ExposureProgram"          => 2,
                        "ISOSpeedRatings"          => 400,
                        "ExifVersion"              => "0221",
                        "DateTimeOriginal"         => "2008:08:05 20:59:32",
                        "DateTimeDigitized"        => "2008:08:05 20:59:32",
                        "ShutterSpeedValue"        => "6643856/1000000",
                        "ApertureValue"            => "2275007/1000000",
                        "ExposureBiasValue"        => "0/3",
                        "MaxApertureValue"         => "96875/100000",
                        "MeteringMode"             => 1,
                        "Flash"                    => 16,
                        "FocalLength"              => "50/1",
                        "FocalPlaneXResolution"    => "3888000/877",
                        "FocalPlaneYResolution"    => "2592000/582",
                        "FocalPlaneResolutionUnit" => 2,
                        "CustomRendered"           => 0,
                        "ExposureMode"             => 0,
                        "WhiteBalance"             => 0,
                        "SceneCaptureType"         => 0,
                    ],
                    "GPS"      => [
                        "GPSVersion"      => "\x02\x02\0\0",
                        "GPSLatitudeRef"  => "N",
                        "GPSLatitude"     => [
                            "59/1",
                            "55/1",
                            "37417/1285",
                        ],
                        "GPSLongitudeRef" => "E",
                        "GPSLongitude"    => [
                            "10/1",
                            "41/1",
                            "55324/1253",
                        ],
                        "GPSAltitudeRef"  => "\0",
                        "GPSAltitude"     => "81/1",
                    ],
                ],
                (59 + ((55 / 60) + ((37417 / 1285) / 3600))),

            ],
            'test case without GPS' => [
                [
                    "FILE"     => [
                        "FileName"      => "image_e.jpg",
                        "FileDateTime"  => 1421436408,
                        "FileSize"      => 446759,
                        "FileType"      => 2,
                        "MimeType"      => "image/jpeg",
                        "SectionsFound" => "ANY_TAG, IFD0, EXIF, GPS",
                    ],
                    "COMPUTED" => [
                        "html"              => 'width="1280" height="853"',
                        "Height"            => 853,
                        "Width"             => 1280,
                        "IsColor"           => 1,
                        "ByteOrderMotorola" => 0,
                        "ApertureFNumber"   => "f/2.2",
                        "Copyright"         => "Eirik Solheim - www.eirikso.com",
                    ],
                    "IFD0"     => [
                        "Make"             => "Canon",
                        "Model"            => "Canon EOS 400D DIGITAL",
                        "XResolution"      => "300/1",
                        "YResolution"      => "300/1",
                        "ResolutionUnit"   => 2,
                        "DateTime"         => "2008:08:05 23:48:04",
                        "Artist"           => "unknown",
                        "Copyright"        => "Eirik Solheim - www.eirikso.com",
                        "Exif_IFD_Pointer" => 240,
                        "GPS_IFD_Pointer"  => 610,
                    ],
                    "EXIF"     => [
                        "ExposureTime"             => "1/100",
                        "FNumber"                  => "22/10",
                        "ExposureProgram"          => 2,
                        "ISOSpeedRatings"          => 400,
                        "ExifVersion"              => "0221",
                        "DateTimeOriginal"         => "2008:08:05 20:59:32",
                        "DateTimeDigitized"        => "2008:08:05 20:59:32",
                        "ShutterSpeedValue"        => "6643856/1000000",
                        "ApertureValue"            => "2275007/1000000",
                        "ExposureBiasValue"        => "0/3",
                        "MaxApertureValue"         => "96875/100000",
                        "MeteringMode"             => 1,
                        "Flash"                    => 16,
                        "FocalLength"              => "50/1",
                        "FocalPlaneXResolution"    => "3888000/877",
                        "FocalPlaneYResolution"    => "2592000/582",
                        "FocalPlaneResolutionUnit" => 2,
                        "CustomRendered"           => 0,
                        "ExposureMode"             => 0,
                        "WhiteBalance"             => 0,
                        "SceneCaptureType"         => 0,
                    ],
                ],
                null,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getLatitudeDataProvider
     *
     * @param array      $rawExifInput
     * @param float|null $expected
     */
    public function getLatitude(array $rawExifInput, ?float $expected)
    {
        $actual = (new ExifData('does-not-matter.jpg', $rawExifInput))->getLatitude();
        $this->assertEquals($expected, $actual);
    }

    public function getLongitudeDataProvider(): array
    {
        return [
            'test case with GPS'    => [
                [
                    "FILE"     => [
                        "FileName"      => "image_e.jpg",
                        "FileDateTime"  => 1421436408,
                        "FileSize"      => 446759,
                        "FileType"      => 2,
                        "MimeType"      => "image/jpeg",
                        "SectionsFound" => "ANY_TAG, IFD0, EXIF, GPS",
                    ],
                    "COMPUTED" => [
                        "html"              => 'width="1280" height="853"',
                        "Height"            => 853,
                        "Width"             => 1280,
                        "IsColor"           => 1,
                        "ByteOrderMotorola" => 0,
                        "ApertureFNumber"   => "f/2.2",
                        "Copyright"         => "Eirik Solheim - www.eirikso.com",
                    ],
                    "IFD0"     => [
                        "Make"             => "Canon",
                        "Model"            => "Canon EOS 400D DIGITAL",
                        "XResolution"      => "300/1",
                        "YResolution"      => "300/1",
                        "ResolutionUnit"   => 2,
                        "DateTime"         => "2008:08:05 23:48:04",
                        "Artist"           => "unknown",
                        "Copyright"        => "Eirik Solheim - www.eirikso.com",
                        "Exif_IFD_Pointer" => 240,
                        "GPS_IFD_Pointer"  => 610,
                    ],
                    "EXIF"     => [
                        "ExposureTime"             => "1/100",
                        "FNumber"                  => "22/10",
                        "ExposureProgram"          => 2,
                        "ISOSpeedRatings"          => 400,
                        "ExifVersion"              => "0221",
                        "DateTimeOriginal"         => "2008:08:05 20:59:32",
                        "DateTimeDigitized"        => "2008:08:05 20:59:32",
                        "ShutterSpeedValue"        => "6643856/1000000",
                        "ApertureValue"            => "2275007/1000000",
                        "ExposureBiasValue"        => "0/3",
                        "MaxApertureValue"         => "96875/100000",
                        "MeteringMode"             => 1,
                        "Flash"                    => 16,
                        "FocalLength"              => "50/1",
                        "FocalPlaneXResolution"    => "3888000/877",
                        "FocalPlaneYResolution"    => "2592000/582",
                        "FocalPlaneResolutionUnit" => 2,
                        "CustomRendered"           => 0,
                        "ExposureMode"             => 0,
                        "WhiteBalance"             => 0,
                        "SceneCaptureType"         => 0,
                    ],
                    "GPS"      => [
                        "GPSVersion"      => "\x02\x02\0\0",
                        "GPSLatitudeRef"  => "N",
                        "GPSLatitude"     => [
                            "59/1",
                            "55/1",
                            "37417/1285",
                        ],
                        "GPSLongitudeRef" => "E",
                        "GPSLongitude"    => [
                            "10/1",
                            "41/1",
                            "55324/1253",
                        ],
                        "GPSAltitudeRef"  => "\0",
                        "GPSAltitude"     => "81/1",
                    ],
                ],
                (10 + ((41 / 60) + ((55324 / 1253) / 3600))),
            ],
            'test case without GPS' => [
                [
                    "FILE"     => [
                        "FileName"      => "image_e.jpg",
                        "FileDateTime"  => 1421436408,
                        "FileSize"      => 446759,
                        "FileType"      => 2,
                        "MimeType"      => "image/jpeg",
                        "SectionsFound" => "ANY_TAG, IFD0, EXIF, GPS",
                    ],
                    "COMPUTED" => [
                        "html"              => 'width="1280" height="853"',
                        "Height"            => 853,
                        "Width"             => 1280,
                        "IsColor"           => 1,
                        "ByteOrderMotorola" => 0,
                        "ApertureFNumber"   => "f/2.2",
                        "Copyright"         => "Eirik Solheim - www.eirikso.com",
                    ],
                    "IFD0"     => [
                        "Make"             => "Canon",
                        "Model"            => "Canon EOS 400D DIGITAL",
                        "XResolution"      => "300/1",
                        "YResolution"      => "300/1",
                        "ResolutionUnit"   => 2,
                        "DateTime"         => "2008:08:05 23:48:04",
                        "Artist"           => "unknown",
                        "Copyright"        => "Eirik Solheim - www.eirikso.com",
                        "Exif_IFD_Pointer" => 240,
                        "GPS_IFD_Pointer"  => 610,
                    ],
                    "EXIF"     => [
                        "ExposureTime"             => "1/100",
                        "FNumber"                  => "22/10",
                        "ExposureProgram"          => 2,
                        "ISOSpeedRatings"          => 400,
                        "ExifVersion"              => "0221",
                        "DateTimeOriginal"         => "2008:08:05 20:59:32",
                        "DateTimeDigitized"        => "2008:08:05 20:59:32",
                        "ShutterSpeedValue"        => "6643856/1000000",
                        "ApertureValue"            => "2275007/1000000",
                        "ExposureBiasValue"        => "0/3",
                        "MaxApertureValue"         => "96875/100000",
                        "MeteringMode"             => 1,
                        "Flash"                    => 16,
                        "FocalLength"              => "50/1",
                        "FocalPlaneXResolution"    => "3888000/877",
                        "FocalPlaneYResolution"    => "2592000/582",
                        "FocalPlaneResolutionUnit" => 2,
                        "CustomRendered"           => 0,
                        "ExposureMode"             => 0,
                        "WhiteBalance"             => 0,
                        "SceneCaptureType"         => 0,
                    ],
                ],
                null,
            ],
        ];
    }

    /**
     * @test
     * @dataProvider getLongitudeDataProvider
     *
     * @param array      $rawExifInput
     * @param float|null $expected
     */
    public function getLongitude(array $rawExifInput, ?float $expected)
    {
        $actual = (new ExifData('does-not-matter.jpg', $rawExifInput))->getLongitude();
        $this->assertEquals($expected, $actual);
    }
}
