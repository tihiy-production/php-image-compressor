<?php

namespace tihiy\Compressor\Assertions;

use ErrorException;

class FileAssertions
{
    protected function __construct()
    {
    }

    /**
     * Asserts that a file exists at the given path.
     *
     * @param string $path
     *
     * @return void
     *
     * @throws ErrorException
     */
    public static function assertExist(string $path): void
    {
        if (!file_exists($path)) {
            throw new ErrorException("The file does not exist at: $path");
        }
    }

    /**
     * Asserts that the file at the given path has content.
     *
     * @param string $path
     *
     * @return void
     *
     * @throws ErrorException
     */
    public static function assertSize(string $path): void
    {
        if (!filesize($path)) {
            throw new ErrorException("File content is not available at: $path");
        }
    }

    /**
     * Asserts that the provided URL is valid.
     *
     * @param string $url
     *
     * @return void
     *
     * @throws ErrorException
     */
    public static function assertUrl(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new ErrorException("Invalid URL: $url");
        }
    }

    /**
     * Asserts that the URL points to a valid image.
     *
     * @param string $url
     *
     * @return void
     *
     * @throws ErrorException
     */
    public static function assertImage(string $url): void
    {
        if (!getimagesize($url)) {
            throw new ErrorException("The URL is not an image: $url");
        }
    }
}
