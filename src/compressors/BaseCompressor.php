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
     * Content of the file to compress
     *
     * @var string
     */
    private $sourceFileContent;

    /**
     * Temporary path to the file to compress
     *
     * @var string
     */
    private $sourceTempFilePath;

    /**
     * Temporary file content of the compressed file
     *
     * @var string
     */
    private $compressedTempFileContent;

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
     * @param string           $sourceFileContent
     * @param FileConfigurator $fileConfigurator
     *
     * @throws ErrorException
     */
    public function __construct(string $sourceFileContent, FileConfigurator $fileConfigurator)
    {
        $this->systemCommand = new SystemCommand();
        $this->fileConfigurator = $fileConfigurator;
        $this->sourceTempFilePath = $this->fileConfigurator->createTemporaryFile($sourceFileContent);
        $this->sourceFileContent = $sourceFileContent;
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
        $fileContent = $this->getSourceFileContent();
        if ($this->compress()) {
            $fileContent = $this->getCompressedFileContent();
        }

        return (bool)file_put_contents($path, $fileContent);
    }

    /**
     * Content of the compressed file
     *
     * @return string
     */
    public function toContent(): string
    {
        if ($this->compress()) {
            return $this->getCompressedFileContent();
        }

        return $this->getSourceFileContent();
    }

    /**
     * Compression command to be executed
     *
     * @param string $sourceFilePath Path to the file to compress
     * @param string $tempFilePath Temporary file to save compressed file
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
     * Return path to the source temporary file
     *
     * @return string
     */
    protected function getSourceTempFilePath(): string
    {
        return $this->sourceTempFilePath;
    }

    /**
     * Return content of a source file
     *
     * @return string
     */
    protected function getSourceFileContent(): string
    {
        return $this->sourceFileContent;
    }

    /**
     * Compress file
     *
     * @return bool
     */
    private function compress(): bool
    {
        try {
            $tempFilePath = $this->fileConfigurator->createTemporaryFile();

            $command = static::getCommand(
                $this->systemCommand->getEscapedFilePath($this->getSourceTempFilePath()),
                $this->systemCommand->getEscapedFilePath($tempFilePath)
            );

            if (!$this->systemCommand->execute($command)->isSuccess()) {
                return false;
            }

            if (!FileConfigurator::getFileSize($tempFilePath)) {
                return false;
            }

            $this->compressedTempFileContent = FileConfigurator::getFileContent($tempFilePath);

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Return content of the compressed file
     *
     * @return string
     */
    private function getCompressedFileContent(): string
    {
        return $this->compressedTempFileContent;
    }
}
