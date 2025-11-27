<?php

namespace App\Services\Integration;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;

class RobotsGenerator
{
    public static function defaultRules(): string
    {
        $lines = [
            'User-agent: *',
            'Disallow: /admin',
            'Disallow: /dashboard',
            'Allow: /',
            '',
            'Sitemap: ' . URL::to('/sitemap.xml'),
        ];

        return implode(PHP_EOL, $lines) . PHP_EOL;
    }

    public static function read(): ?string
    {
        $path = static::path();

        if (!File::exists($path)) {
            return null;
        }

        return File::get($path);
    }

    public static function write(string $content): string
    {
        $normalized = rtrim($content);
        $normalized = $normalized === '' ? static::defaultRules() : $normalized . PHP_EOL;

        File::put(static::path(), $normalized);

        return static::path();
    }

    public static function path(): string
    {
        return public_path('robots.txt');
    }
}

