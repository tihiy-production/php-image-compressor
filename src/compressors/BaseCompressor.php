<?php

namespace tihiy\Compressor\compressors;

use ErrorException;
use Exception;
use tihiy\Compressor\compressors\components\FileConfigurator;
use tihiy\Compressor\compressors\components\SystemCommand;

/**
 * Class BaseCompressor.
 *
 * @author  Nikita Vorushilo <young95strong@gmail.com>
 *
 * @link    https://github.com/tihiy-production/php-image-compressor
 */
abstract class BaseCompressor
{
    /**
     * Path to the file to compress
     *
     * @var string
     */
    private $sourceFilePath;

    /**
     * Binary data of the file to compress
     *
     * @var string
     */
    private $sourceFileData;

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
     * @param string $sourceFilePath
     * @param string $sourceFileData
     */
    public function __construct(string $sourceFilePath, string $sourceFileData)
    {
        $this->sourceFilePath = $sourceFilePath;
        $this->sourceFileData = $sourceFileData;
        $this->fileConfigurator = new FileConfigurator();
        $this->systemCommand = new SystemCommand();
    }

    /**
     * Compress image
     *
     * @param string|null $path Path to the file where the compressed file will be saved
     *
     * @return bool
     */
    public function compress(?string $path = null): bool
    {
        try {
            if (!$path) {
                $path = $this->getSourceFilePath();
            }

            $tempFilePath = $this->fileConfigurator->createTemporaryFile();

            $command = static::getCommand(
                $this->systemCommand->getEscapedFilePath($this->getSourceFilePath()),
                $this->systemCommand->getEscapedFilePath($tempFilePath)
            );

            if ($this->systemCommand->execute($command)->isSuccess()) {
                if ($this->saveFile($path, $tempFilePath)) {
                    return true;
                }
            }

            return copy($this->getSourceFilePath(), $path);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Command to be executed
     *
     * @param string $sourceFilePath Path to the file to compress
     * @param string $tempFilePath Buffer temporary file to save compressed file
     *
     * @return string
     *
     * @throws ErrorException
     */
    abstract protected function getCommand(string $sourceFilePath, string $tempFilePath): string;

    /**
     * BaseCompressor destructor.
     */
    public function __destruct()
    {
        FileConfigurator::removeTemporaryFiles();
    }

    /**
     * Return path to the file to compress
     *
     * @return string
     */
    protected function getSourceFilePath(): string
    {
        return $this->sourceFilePath;
    }

    /**
     * Return binary data of a file
     *
     * @return string
     */
    protected function getSourceFileData(): string
    {
        return $this->sourceFileData;
    }

    /**
     * Save compressed file
     *
     * @param string $path Path to save the compressed file
     * @param string $tempFilePath Path to the temporary compressed file
     *
     * @return bool
     *
     * @throws ErrorException
     */
    private function saveFile(string $path, string $tempFilePath): bool
    {
        if (FileConfigurator::getFileSize($tempFilePath)) {
            return (bool)file_put_contents($path, file_get_contents($tempFilePath));
        }

        return false;
    }
}
