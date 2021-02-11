<?php

namespace tihiy\Compressor\compressors;

use ErrorException;

/**
 * Class BaseCompressor
 *
 * @package tihiy\Compressor\compressors
 */
abstract class BaseCompressor
{
    /**
     * Success code on the last line of the command output
     */
    protected const SYSTEM_SUCCESS_CODE = 0;

    /**
     * The path to the temporary file
     *
     * @var string
     */
    protected $temporaryFile;

    /**
     * The path to the file to compress
     *
     * @var string
     */
    protected $path;

    /**
     * Compression options
     *
     * @var array
     */
    protected $options = [];

    /**
     * BaseCompressor constructor.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options): BaseCompressor
    {
        $this->options = $options;

        return $this;
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
        $this->flushOptions();
        $this->removeTemporaryFiles();
    }

    /**
     * @return void
     */
    protected function flushOptions(): void
    {
        $this->options = [];
    }

    /**
     * @return string
     */
    protected function getOptions(): string
    {
        return implode(' ', $this->options);
    }

    /**
     * Escape a string so that it can be used as a command line argument
     *
     * @param string $path
     *
     * @return string
     */
    protected function getEscapedFilePath(string $path): string
    {
        return escapeshellarg($path);
    }

    /**
     * Run system command
     *
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

    /**
     * Create a temporary file and save data to it
     *
     * @param string $fileData
     *
     * @return string
     *
     * @throws ErrorException
     */
    protected function createTemporaryFile(string $fileData = ''): string
    {
        if ($filePath = tempnam(sys_get_temp_dir(), 'File')) {
            $this->registerTemporaryFile($filePath);

            if ($handler = fopen($filePath, 'wb')) {
                fwrite($handler, $fileData);
                fclose($handler);

                return $filePath;
            }
        }

        throw new ErrorException('Unable to create temporary file.');
    }

    /**
     * Register temporary file for deletion at the end of work
     *
     * @param string $pathToFile
     */
    protected function registerTemporaryFile(string $pathToFile): void
    {
        $this->temporaryFile = $pathToFile;
    }

    /**
     * Delete all registered temporary files
     */
    protected function removeTemporaryFiles(): void
    {
        if (is_file($this->temporaryFile)) {
            unlink($this->temporaryFile);
        }

        unset($this->temporaryFile);
    }
}
