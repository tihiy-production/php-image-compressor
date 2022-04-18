<?php

namespace tihiy\Compressor\Compressor;

use ErrorException;
use Exception;
use tihiy\Compressor\Object\File;
use tihiy\Compressor\Service\SystemCommand;

/**
 * Class AbstractCompressor.
 */
abstract class AbstractCompressor
{
    /**
     * @var File
     */
    private $sourceFile;

    /**
     * @var File
     */
    private $compressedFile;

    /**
     * @var SystemCommand
     */
    protected $systemCommand;

    /**
     * AbstractCompressor constructor.
     *
     * @param File $sourceFile
     *
     * @throws ErrorException
     */
    public function __construct(File $sourceFile)
    {
        $this->systemCommand = new SystemCommand();
        $this->sourceFile = $sourceFile;
        $this->compressedFile = File::createFromContent('');
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
        $fileContent = $this->getSourceFile()->getContent();
        if ($this->compress()) {
            $fileContent = $this->getCompressedFile()->getContent();
        }

        return (bool)file_put_contents($path, $fileContent);
    }

    /**
     * Return content of the compressed file
     *
     * @return string
     */
    public function toContent(): string
    {
        if ($this->compress()) {
            return $this->getCompressedFile()->getContent();
        }

        return $this->getSourceFile()->getContent();
    }

    /**
     * Compression command to be executed
     *
     * @param string $sourceFilePath
     * @param string $compressedFilePath
     *
     * @return string
     *
     * @throws ErrorException
     */
    abstract protected function getCommand(string $sourceFilePath, string $compressedFilePath): string;

    /**
     * @return File
     */
    protected function getSourceFile(): File
    {
        return $this->sourceFile;
    }

    /**
     * @return File
     */
    private function getCompressedFile(): File
    {
        return $this->compressedFile;
    }

    /**
     * @return bool
     */
    private function compress(): bool
    {
        try {
            $command = $this->getCommand(
                $this->systemCommand->getEscapedFilePath($this->getSourceFile()->getTempPath()),
                $this->systemCommand->getEscapedFilePath($this->getCompressedFile()->getTempPath())
            );

            if (!$this->systemCommand->execute($command)->isSuccess()) {
                return false;
            }

            if (!$this->getCompressedFile()->getSize()) {
                return false;
            }

            return true;
        } catch (Exception $exception) {
            return false;
        }
    }
}
