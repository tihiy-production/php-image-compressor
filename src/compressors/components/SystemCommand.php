<?php

namespace tihiy\Compressor\compressors\components;

/**
 * Class SystemCommand.
 *
 * @link    https://github.com/tihiy-production/php-image-compressor
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
     * Run system command
     *
     * @param string $command The command to be executed
     *
     * @return SystemCommand
     */
    public function execute(string $command): self
    {
        $this->resultCode = null;
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
        return $this->resultCode === self::SYSTEM_SUCCESS_CODE;
    }
}
