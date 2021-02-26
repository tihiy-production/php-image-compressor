<?php

namespace tihiy\Compressor\compressors\components;

use ErrorException;

/**
 * Class FileConfigurator.
 *
 * @author  Nikita Vorushilo <young95strong@gmail.com>
 *
 * @link    https://github.com/tihiy-production/php-image-compressor
 */
class FileConfigurator
{
    /**
     * List of registered temporary files
     *
     * @var array
     */
    private static $temporaryFileList = [];

    /**
     * Determine the file size in bytes
     *
     * @param string $pathToFile
     *
     * @return integer
     *
     * @throws ErrorException
     */
    public static function getFileSize(string $pathToFile): int
    {
        if (is_file($pathToFile)) {
            return filesize($pathToFile);
        }

        throw new ErrorException("File '{$pathToFile}' not found.");
    }

    /**
     * Create a temporary file and save data to it
     *
     * @param string $fileData
     *
     * @return string
     *
     * @throws ErrorException
     */
    public function createTemporaryFile(string $fileData = ''): string
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

    /**
     * Delete registered temporary files
     */
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
     * Register temporary file for deletion at the end of work
     *
     * @param string $pathToFile
     */
    private static function registerTemporaryFile(string $pathToFile): void
    {
        static::$temporaryFileList[] = $pathToFile;
    }
}
