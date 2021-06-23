<?php

namespace tihiy\Compressor;

use ErrorException;
use tihiy\Compressor\compressors\BaseCompressor;
use tihiy\Compressor\compressors\components\FileConfigurator;
use tihiy\Compressor\compressors\Jpegoptim;
use tihiy\Compressor\compressors\Pngquant;

/**
 * Class ImageCompressor.
 *
 * @author  Nikita Vorushilo <young95strong@gmail.com>
 *
 * @link    https://github.com/tihiy-production/php-image-compressor
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
     * @return BaseCompressor
     *
     * @throws ErrorException
     */
    public static function sourceFile(string $path): BaseCompressor
    {
        if (!file_exists($path)) {
            throw new ErrorException('The file does not exist');
        }

        if (!FileConfigurator::getFileSize($path)) {
            throw new ErrorException('File content is not available');
        }

        $sourceFileContent = FileConfigurator::getFileContent($path);

        return self::getResponse($sourceFileContent);
    }

    /**
     * Compress a string with binary
     *
     * @param string $content File content in string
     *
     * @return BaseCompressor
     *
     * @throws ErrorException
     */
    public static function sourceContent(string $content): BaseCompressor
    {
        return self::getResponse($content);
    }

    /**
     * Compress file by URL instead of having to upload it
     *
     * @param string $url Absolute URL to file
     *
     * @return BaseCompressor
     *
     * @throws ErrorException
     */
    public static function sourceUrl(string $url): BaseCompressor
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new ErrorException('URL is not valid');
        }

        if (!getimagesize($url)) {
            throw new ErrorException('URL is not an image');
        }

        $sourceFileContent = FileConfigurator::getFileContentByUrl($url);

        return self::getResponse($sourceFileContent);
    }

    /**
     * Get a compressor object
     *
     * @param string $sourceFileContent File content in string to compress
     *
     * @return BaseCompressor
     *
     * @throws ErrorException
     */
    private static function getResponse(string $sourceFileContent): BaseCompressor
    {
        $fileConfigurator = new FileConfigurator();

        $mimeType = mime_content_type($fileConfigurator->createTemporaryFile($sourceFileContent));

        switch ($mimeType) {
            case self::MIME_TYPE_PNG:
                return new Pngquant($sourceFileContent, $fileConfigurator);
            case self::MIME_TYPE_JPEG:
                return new Jpegoptim($sourceFileContent, $fileConfigurator);
            default:
                throw new ErrorException("Compression is not available for '{$mimeType}' MIME-type");
        }
    }
}
