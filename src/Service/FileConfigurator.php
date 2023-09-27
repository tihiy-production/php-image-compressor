<?php

namespace tihiy\Compressor\Service;

use ErrorException;

/**
 * Class FileConfigurator.
 */
final class FileConfigurator
{
    private function __construct()
    {
    }
    
    /**
     * @var array
     */
    private static $temporaryFileList = [];

    /**
     * @param string $fileData
     *
     * @return string
     *
     * @throws ErrorException
     */
    public static function createTemporaryFile(string $fileData = ''): string
    {
        if ($filePath = tempnam(sys_get_temp_dir(), 'File')) {
            self::registerTemporaryFile($filePath);

            if ($handler = fopen($filePath, 'wb')) {
                fwrite($handler, $fileData);
                fclose($handler);

                return $filePath;
            }
        }

        throw new ErrorException('Unable to create temporary file.');
    }

    public static function removeTemporaryFiles(): void
    {
        if (self::$temporaryFileList && is_array(self::$temporaryFileList)) {
            foreach (self::$temporaryFileList as $index => $pathToFile) {
                if (is_file($pathToFile)) {
                    unlink($pathToFile);
                }

                unset(self::$temporaryFileList[$index]);
            }
        }
    }

    /**
     * @param string $pathToFile
     */
    private static function registerTemporaryFile(string $pathToFile): void
    {
        self::$temporaryFileList[] = $pathToFile;
    }
}
