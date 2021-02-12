<?php

namespace tihiy\Compressor\compressors;

use tihiy\Compressor\compressors\components\FileConfigurator;
use tihiy\Compressor\compressors\components\SystemCommand;

/**
 * Class BaseCompressor
 *
 * @package tihiy\Compressor\compressors
 */
abstract class BaseCompressor
{
    /**
     * The path to the file to compress
     *
     * @var string
     */
    private $sourcePath;

    /**
     * @var FileConfigurator
     */
    protected $fileConfigurator;

    /**
     * @var SystemCommand
     */
    protected $systemCommand;

    /**
     * BaseCompressor constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->sourcePath = $path;
        $this->fileConfigurator = new FileConfigurator();
        $this->systemCommand = new SystemCommand();
    }

    /**
     * Compress image
     *
     * @param string|null $path The path to the file where the compressed file will be saved
     *
     * @return bool
     */
    abstract public function compress(?string $path = null): bool;

    /**
     * Reset all
     */
    public function __destruct()
    {
        $this->fileConfigurator->removeTemporaryFile();
    }

    /**
     * @return string
     */
    protected function getSourcePath(): string
    {
        return $this->sourcePath;
    }

    /**
     * Save compressed file
     *
     * @param string $path
     * @param string $tempFilePath
     *
     * @return bool
     */
    protected function saveFile(string $path, string $tempFilePath): bool
    {
        return (bool)file_put_contents($path, file_get_contents($tempFilePath));
    }
}
