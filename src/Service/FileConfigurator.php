<?php

namespace tihiy\Compressor\Service;

use ErrorException;

class FileConfigurator
{
    protected function __construct()
    {
    }

    protected static $temporaryFileList = [];

    public static function createTemporaryFile(string $fileData = ''): string
    {
        $filePath = tempnam(sys_get_temp_dir(), 'File');
        if (false === $filePath) {
            throw new ErrorException('Unable to create temporary file.');
        }

        if (false === file_put_contents($filePath, $fileData)) {
            throw new ErrorException('Unable to write data to temporary file.');
        }

        self::$temporaryFileList[] = $filePath;

        return $filePath;
    }

    public static function removeTemporaryFiles(): void
    {
        foreach (self::$temporaryFileList as $pathToFile) {
            if (is_file($pathToFile)) {
                unlink($pathToFile);
            }
        }
        self::$temporaryFileList = [];
    }
}
