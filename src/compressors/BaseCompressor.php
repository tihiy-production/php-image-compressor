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
    private $sourcePath;

    /**
     * @var FileConfigurator
     */
    private $fileConfigurator;

    /**
     * @var SystemCommand
     */
    private $systemCommand;

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
    public function compress(?string $path = null): bool
    {
        try {
            if (!$path) {
                $path = $this->getSourcePath();
            }

            $tempFilePath = $this->fileConfigurator->createTemporaryFile();

            $command = static::getCommand(
                $this->systemCommand->getEscapedFilePath($this->getSourcePath()),
                $this->systemCommand->getEscapedFilePath($tempFilePath)
            );

            if ($this->systemCommand->execute($command)) {
                if ($this->saveFile($path, $tempFilePath)) {
                    return true;
                }
            }

            return copy($this->getSourcePath(), $path);
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * Command to be executed
     *
     * @param string $sourcePath Path to the file to compress
     * @param string $tempFilePath Buffer temporary file to save compressed file
     *
     * @return string
     *
     * @throws ErrorException
     */
    abstract protected function getCommand(string $sourcePath, string $tempFilePath): string;

    /**
     * BaseCompressor destructor.
     */
    public function __destruct()
    {
        $this->fileConfigurator->removeTemporaryFile();
    }

    /**
     * Path to the file to compress
     *
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
    private function saveFile(string $path, string $tempFilePath): bool
    {
        if (filesize($tempFilePath)) {
            return (bool)file_put_contents($path, file_get_contents($tempFilePath));
        }

        return false;
    }
}
