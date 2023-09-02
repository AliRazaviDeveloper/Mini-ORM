<?php

namespace App\Helper;

use App\Exception\ConfigNotFoundException;

class Config
{
    /**
     * @throws ConfigNotFoundException
     */
    public static function getFileContents(string $filename)
    {
        $filePath = realpath(__DIR__ . "/../Config/" . $filename . ".php");
        if (!$filePath) throw new  ConfigNotFoundException();
        $fileContents = require $filePath;

        return $fileContents;
    }

    /**
     * @throws ConfigNotFoundException
     */
    public static function get(string $filename, string $key=null)
    {
        $getFileName=self::getFileContents($filename);
        if(is_null($key)) return $getFileName;
        return  $getFileName[$key] ?? null;
    }
}