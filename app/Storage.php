<?php

namespace App;

class Storage
{
    public static function downloadImage($url): string
    {
        $file_contents = file_get_contents($url);

        $file_path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . basename($url);

        $image = fopen($file_path, "w+");
        fwrite($image, $file_contents);
        fclose($image);

        return $file_path;
    }

    public static function root(): string
    {
        return __DIR__ . '/../';
    }
}