<?php

namespace tihiy\Compressor;

use ErrorException;
use tihiy\Compressor\Assertions\FileAssertions;
use tihiy\Compressor\Compressor\AbstractCompressor;
use tihiy\Compressor\Object\File;
use tihiy\Compressor\Compressor\Jpegoptim;
use tihiy\Compressor\Compressor\Pngquant;
use tihiy\Compressor\Service\FileConfigurator;

/**
 * Class ImageCompressor.
 */
class ImageCompressor
{
    /**
     * Allowed MIME-types
     */
    private const MIME_TYPE_PNG = 'image/png';

    private const MIME_TYPE_JPEG = 'image/jpeg';

    /**
     * @var AbstractCompressor
     */
    private $compressor;

    /**
     * @param AbstractCompressor $compressor
     */
    public function __construct(AbstractCompressor $compressor)
    {
        $this->compressor = $compressor;
    }

    /**
     * Compress a local file
     *
     * @param string $path Path to the local file to be compressed
     *
     * @return ImageCompressor
     *
     * @throws ErrorException
     */
    public static function sourceFile(string $path): self
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
     * @return ImageCompressor
     *
     * @throws ErrorException
     */
    public static function sourceContent(string $content): self
    {
        return self::create(File::createFromContent($content));
    }

    /**
     * Compress file by URL instead of having to upload it
     *
     * @param string $url Absolute URL to file
     *
     * @return ImageCompressor
     *
     * @throws ErrorException
     */
    public static function sourceUrl(string $url): self
    {
        FileAssertions::assertUrl($url);
        FileAssertions::assertImage($url);

        return self::create(File::createFromUrl($url));
    }

    /**
     * Save compressed file
     *
     * @param string $path Path to save the compressed file
     *
     * @return bool
     */
    public function toFile(string $path): bool
    {
        return $this->compressor->toFile($path);
    }

    /**
     * Return content of the compressed file
     *
     * @return string
     */
    public function toContent(): string
    {
        return $this->compressor->toContent();
    }

    /**
     * @param File $file
     *
     * @return ImageCompressor
     *
     * @throws ErrorException
     */
    private static function create(File $file): self
    {
        $mimeType = $file->getMimeType();

        switch ($mimeType) {
            case self::MIME_TYPE_PNG:
                return new self(new Pngquant($file));
            case self::MIME_TYPE_JPEG:
                return new self(new Jpegoptim($file));
        }

        throw new ErrorException("Compression is not available for '$mimeType' MIME-type");
    }

    /**
     * AbstractCompressor destructor.
     */
    public function __destruct()
    {
        FileConfigurator::removeTemporaryFiles();
    }
}
