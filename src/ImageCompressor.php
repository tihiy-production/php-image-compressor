<?php

namespace tihiy\Compressor;

use ErrorException;
use tihiy\Compressor\Assertions\FileAssertions;
use tihiy\Compressor\Compressor\AbstractCompressor;
use tihiy\Compressor\Object\File;
use tihiy\Compressor\Compressor\Jpegoptim;
use tihiy\Compressor\Compressor\Pngquant;

/**
 * Class ImageCompressor.
 */
class ImageCompressor
{
    /**
     * MIME-types
     */
    private const MIME_TYPE_PNG = 'image/png';

    private const MIME_TYPE_JPEG = 'image/jpeg';

    /**
     * Compress a local file
     *
     * @param string $path Path to the local file to be compressed
     *
     * @return AbstractCompressor
     *
     * @throws ErrorException
     */
    public static function sourceFile(string $path): AbstractCompressor
    {
        FileAssertions::assertExist($path);
        FileAssertions::assertSize($path);

        return self::create(File::createFromFile($path));
    }

    /**
     * Compress a string with binary
     *
     * @param string $content File content in string
     *
     * @return AbstractCompressor
     *
     * @throws ErrorException
     */
    public static function sourceContent(string $content): AbstractCompressor
    {
        return self::create(File::createFromContent($content));
    }

    /**
     * Compress file by URL instead of having to upload it
     *
     * @param string $url Absolute URL to file
     *
     * @return AbstractCompressor
     *
     * @throws ErrorException
     */
    public static function sourceUrl(string $url): AbstractCompressor
    {
        FileAssertions::assertUrl($url);
        FileAssertions::assertImage($url);

        return self::create(File::createFromUrl($url));
    }

    /**
     * @param File $file
     *
     * @return AbstractCompressor
     *
     * @throws ErrorException
     */
    private static function create(File $file): AbstractCompressor
    {
        $mimeType = $file->getMimeType();

        switch ($mimeType) {
            case self::MIME_TYPE_PNG:
                return new Pngquant($file);
            case self::MIME_TYPE_JPEG:
                return new Jpegoptim($file);
        }

        throw new ErrorException("Compression is not available for '$mimeType' MIME-type");
    }
}
