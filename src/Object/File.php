<?php

namespace tihiy\Compressor\Object;

use ErrorException;
use tihiy\Compressor\Service\FileConfigurator;

class File
{
    /**
     * Binary string of file
     *
     * @var string
     */
    protected $content;

    /**
     * Path to the temporary file.
     *
     * @var string
     */
    protected $tempPath;

    /**
     * @param string $content
     *
     * @throws ErrorException
     */
    protected function __construct(string $content)
    {
        $this->content = $content;
        $this->tempPath = FileConfigurator::createTemporaryFile($content);
    }

    /**
     * Create a File instance from a file path.
     *
     * @param string $path
     *
     * @return static
     *
     * @throws ErrorException
     */
    public static function createFromFile(string $path): self
    {
        $content = file_get_contents($path);
        if (false === $content) {
            throw new ErrorException("Unable to read file: $path");
        }

        return self::createFromContent($content);
    }

    /**
     * Create a File instance from a URL.
     *
     * @param string $url
     *
     * @return static
     *
     * @throws ErrorException
     */
    public static function createFromUrl(string $url): self
    {
        $options = [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ];

        $content = file_get_contents($url, false, stream_context_create($options));
        if (false === $content) {
            throw new ErrorException("Unable to fetch URL: $url");
        }

        return self::createFromContent($content);
    }

    /**
     * Create a File instance from content.
     *
     * @param string $content
     *
     * @return static
     *
     * @throws ErrorException
     */
    public static function createFromContent(string $content): self
    {
        return new self($content);
    }

    /**
     * Get the content of the file.
     *
     * @return string
     */
    public function getContent(): string
    {
        return $this->content ?: (string)file_get_contents($this->getTempPath());
    }

    /**
     * Get the path to the temporary file.
     *
     * @return string
     */
    public function getTempPath(): string
    {
        return $this->tempPath;
    }

    /**
     * Get the MIME type of the file.
     *
     * @return string
     */
    public function getMimeType(): string
    {
        return (string)mime_content_type($this->getTempPath());
    }

    /**
     * Get the size of the file.
     *
     * @return int
     */
    public function getSize(): int
    {
        return (int)filesize($this->getTempPath());
    }
}
