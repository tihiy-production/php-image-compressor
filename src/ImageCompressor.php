<?php

namespace tihiy\Compressor;

use ErrorException;
use tihiy\Compressor\compressors\BaseCompressor;
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
     * Get a compressor object
     *
     * @param string $path Path to the file to be compressed
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

        $mimeType = mime_content_type($path);

        if (!self::allowCompression($mimeType)) {
            throw new ErrorException("Compression is not available for '{$mimeType}' MIME-type");
        }

        $sourceFileData = file_get_contents($path);

        if (!$sourceFileData) {
            throw new ErrorException('File content is not available');
        }

        switch ($mimeType) {
            case self::MIME_TYPE_PNG:
                return new Pngquant($path, $sourceFileData);
            default:
                return new Jpegoptim($path, $sourceFileData);
        }
    }

    /**
     * Checking for compression availability
     *
     * @param string $mimeType
     *
     * @return bool
     */
    private static function allowCompression(string $mimeType): bool
    {
        return in_array($mimeType, self::getAllowedMimeTypes(), true);
    }

    /**
     * List of available file MIME-types
     *
     * @return array
     */
    private static function getAllowedMimeTypes(): array
    {
        return [
            self::MIME_TYPE_PNG,
            self::MIME_TYPE_JPEG,
        ];
    }
}
