<?php


namespace App\Lendesk\ImageTool;


final class ExifData
{
    /** @var string $name */
    private $name;

    /** @var array $rawExifData */
    private $rawExifData;

    /**
     * ExifData constructor.
     *
     * @param string $name
     * @param array  $rawExifData
     */
    public function __construct(string $name, array $rawExifData)
    {
        $this->name        = $name;
        $this->rawExifData = $rawExifData;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Returns the latitude of this EXIF data as a float. If GPS coordinates are not available, return null.
     *
     * @return float|null
     */
    public function getLatitude(): ?float
    {
        if (!array_key_exists('GPS', $this->rawExifData)) {
            return null;
        }

        return self::calculateCoordinate(
            $this->rawExifData['GPS']['GPSLatitude'],
            $this->rawExifData['GPS']['GPSLatitudeRef']
        );
    }

    /**
     * Returns the longitude of this EXIF data as a float. If GPS coordinates are not available, return null.
     *
     * @return float|null
     */
    public function getLongitude(): ?float
    {
        if (!array_key_exists('GPS', $this->rawExifData)) {
            return null;
        }

        return self::calculateCoordinate(
            $this->rawExifData['GPS']['GPSLongitude'],
            $this->rawExifData['GPS']['GPSLongitudeRef']
        );
    }

    private static function calculateCoordinate(array $gpsCoords, string $direction): float
    {
        list($degrees, $minutes, $seconds) = $gpsCoords;

        $sign = ($direction === 'W' || $direction === 'S') ? -1 : 1;

        $degreeParts  = explode('/', $degrees);
        $minutesParts = explode('/', $minutes);
        $secondsParts = explode('/', $seconds);

        $degrees = floatval($degreeParts[0]) / floatval($degreeParts[1]);
        $minutes = floatval($minutesParts[0]) / floatval($minutesParts[1]);
        $seconds = floatval($secondsParts[0]) / floatval($secondsParts[1]);

        return floatval($sign * ($degrees + (($minutes / 60) + ($seconds / 3600))));
    }
}