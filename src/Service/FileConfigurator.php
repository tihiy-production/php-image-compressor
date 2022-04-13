<?php

namespace tihiy\Compressor\Service;

use ErrorException;

/**
 * Class FileConfigurator.
 */
class FileConfigurator
{
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
            static::registerTemporaryFile($filePath);

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
        if (static::$temporaryFileList && is_array(static::$temporaryFileList)) {
            foreach (static::$temporaryFileList as $index => $pathToFile) {
                if (is_file($pathToFile)) {
                    unlink($pathToFile);
                }

                unset(static::$temporaryFileList[$index]);
            }
        }
    }

    /**
     * @param string $pathToFile
     */
    private static function registerTemporaryFile(string $pathToFile): void
    {
        static::$temporaryFileList[] = $pathToFile;
    }
}
