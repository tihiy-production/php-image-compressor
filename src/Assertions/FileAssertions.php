<?php

namespace tihiy\Compressor\Assertions;

use ErrorException;

class FileAssertions
{
    /**
     * @param string $path
     *
     * @return void
     *
     * @throws ErrorException
     */
    public static function assertExist(string $path): void
    {
        if (!file_exists($path)) {
            throw new ErrorException('The file does not exist.');
        }
    }

    /**
     * @param string $path
     *
     * @return void
     *
     * @throws ErrorException
     */
    public static function assertSize(string $path): void
    {
        if (!filesize($path)) {
            throw new ErrorException('File content is not available.');
        }
    }

    /**
     * @param string $url
     *
     * @return void
     *
     * @throws ErrorException
     */
    public static function assertUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new ErrorException('URL is not valid.');
        }
    }

    /**
     * @param string $url
     *
     * @return void
     *
     * @throws ErrorException
     */
    public static function assertImage(string $url): void
    {
        if (!getimagesize($url)) {
            throw new ErrorException('URL is not an image.');
        }
    }
}
