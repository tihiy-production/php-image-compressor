<?php

namespace tihiy\Compressor;

use ErrorException;
use Exception;

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
     * Allowed image mime types
     */
    protected const MIME_TYPE_PNG = 'image/png';

    protected const MIME_TYPE_JPEG = 'image/jpeg';

    /**
     * Success code on the last line of the command output
     */
    protected const SYSTEM_SUCCESS_CODE = 0;

    /**
     * The path to the file to compress
     *
     * @var string
     */
    protected $path;

    /**
     * File MIME-type
     *
     * @var string
     */
    protected $mimeType;

    /**
     * ImageCompressor constructor.
     *
     * @param string $path
     * @param string $mimeType
     */
    public function __construct(string $path, string $mimeType)
    {
        $this->path = $path;
        $this->mimeType = $mimeType;
    }

    /**
     * @param string $path
     *
     * @return ImageCompressor
     *
     * @throws ErrorException
     */
    public static function sourceFile(string $path): ImageCompressor
    {
        if (!file_exists($path)) {
            throw new ErrorException('The specified file does not exist');
        }

        return new self($path, mime_content_type($path));
    }

    /**
     * Compress image
     *
     * @param string|null $path
     *
     * @return bool
     */
    public function compress(?string $path = null): bool
    {
        try {
            if ($path && !file_exists($path)) {
                return false;
            }

            if (!$path) {
                $path = $this->path;
            }

            if (!$this->allowCompression()) {
                return copy($this->path, $path);
            }

            $tempFilePath = tempnam(sys_get_temp_dir(), 'CompressedFile');

            $escapedInputFilePath = escapeshellarg($this->path);
            $escapedOutputFilePath = escapeshellarg($tempFilePath);

            $command = '';
            switch ($this->mimeType) {
                case self::MIME_TYPE_PNG:
                    $command = sprintf(
                        "pngquant %s --force --quality 85 --output %s",
                        $escapedInputFilePath,
                        $escapedOutputFilePath
                    );
                    break;
                case self::MIME_TYPE_JPEG:
                    $command = sprintf(
                        "convert %s -sampling-factor 4:2:0 -strip -quality 85 -interlace JPEG -colorspace sRGB %s",
                        $escapedInputFilePath,
                        $escapedOutputFilePath
                    );
                    break;
            }

            if (!$command) {
                return false;
            }

            if (!$this->executeCommand($command)) {
                return false;
            }

            return $this->saveFile($path, $tempFilePath);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @param string $command
     *
     * @return bool
     */
    protected function executeCommand(string $command): bool
    {
        $resultCode = null;
        system($command, $resultCode);

        return $resultCode === self::SYSTEM_SUCCESS_CODE;
    }

    /**
     * @param string $path
     * @param string $tempFilePath
     *
     * @return bool
     */
    protected function saveFile(string $path, string $tempFilePath): bool
    {
        return (bool)file_put_contents($path, file_get_contents($tempFilePath));
    }

    /**
     * @return bool
     */
    protected function allowCompression(): bool
    {
        return in_array($this->mimeType, $this->getAllowedMimeTypes(), true);
    }

    /**
     * Returns allowed mime types
     *
     * @return array
     */
    protected function getAllowedMimeTypes(): array
    {
        return [
            self::MIME_TYPE_PNG,
            self::MIME_TYPE_JPEG,
        ];
    }
}