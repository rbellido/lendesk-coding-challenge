<?php


namespace Tests\Helpers;


trait InteractsWithFixtures
{
    private static function getFixturesDirectory(): string
    {
        return __DIR__ . '/../fixtures';
    }
}