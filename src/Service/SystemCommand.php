<?php

namespace tihiy\Compressor\Service;

/**
 * Class SystemCommand.
 */
class SystemCommand
{
    /**
     * Success exit code of finished process
     */
    private const SYSTEM_SUCCESS_CODE = 0;

    /**
     * Exit code of finished process
     *
     * @var integer
     */
    private $resultCode;

    /**
     * Escape a string so that it can be used as a command line argument
     *
     * @param string $path
     *
     * @return string
     */
    public function getEscapedFilePath(string $path): string
    {
        return escapeshellarg($path);
    }

    /**
     * Execute system command
     *
     * @param string $command The command to be executed
     *
     * @return SystemCommand
     */
    public function execute(string $command): self
    {
        system($command, $this->resultCode);

        return $this;
    }

    /**
     * The system command was executed successfully
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return self::SYSTEM_SUCCESS_CODE === $this->resultCode;
    }
}
