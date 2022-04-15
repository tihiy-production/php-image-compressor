<?php

namespace tihiy\Compressor\Object;

use ErrorException;
use tihiy\Compressor\Service\FileConfigurator;

/**
 * Class File.
 */
class File
{
    /**
     * Binary string of file
     *
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    private $tempPath;

    /**
     * @param string $content
     *
     * @throws ErrorException
     */
    public function __construct(string $content)
    {
        $this->content = $content;
        $this->tempPath = FileConfigurator::createTemporaryFile($content);
    }

    /**
     * @param string $path
     *
     * @return static
     *
     * @throws ErrorException
     */
    public static function createFromFile(string $path): self
    {
        $content = file_get_contents($path);

        return self::createFromContent($content);
    }

    /**
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

        return self::createFromContent($content);
    }

    /**
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
     * @return string
     */
    public function getContent(): string
    {
        return $this->content ?: (string)file_get_contents($this->getTempPath());
    }

    /**
     * @return string
     */
    public function getTempPath(): string
    {
        return $this->tempPath;
    }

    /**
     * @return string
     */
    public function getMimeType(): string
    {
        return (string)mime_content_type($this->getTempPath());
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return (int)filesize($this->getTempPath());
    }
}
