<?php

namespace tihiy\Compressor\Service;

class SystemCommand
{
    protected const SYSTEM_SUCCESS_CODE = 0;

    /**
     * Exit code of the finished process
     *
     * @var integer
     */
    protected $resultCode;

    /**
     * Escape a string to be used as a command line argument.
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
     * Execute a system command.
     *
     * @param string $command The command to be executed.
     *
     * @return SystemCommand
     */
    public function execute(string $command): self
    {
        system($command, $this->resultCode);

        return $this;
    }

    /**
     * Check if the system command was executed successfully.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        return self::SYSTEM_SUCCESS_CODE === $this->resultCode;
    }
}
