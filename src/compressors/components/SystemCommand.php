<?php

namespace tihiy\Compressor\compressors\components;

/**
 * Class SystemCommand.
 *
 * @author  Nikita Vorushilo <young95strong@gmail.com>
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
     * @param string $command
     *
     * @return bool
     */
    public function execute(string $command): bool
    {
        $resultCode = null;
        system($command, $resultCode);

        return $resultCode === self::SYSTEM_SUCCESS_CODE;
    }
}
