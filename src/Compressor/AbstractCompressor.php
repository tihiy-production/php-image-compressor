<?php

namespace tihiy\Compressor\Compressor;

use tihiy\Compressor\Service\SystemCommand;

abstract class AbstractCompressor implements CompressorInterface
{
    protected $systemCommand;

    public function __construct()
    {
        $this->systemCommand = new SystemCommand();
    }

    protected function executeCommand(string $command): bool
    {
        return $this->systemCommand->execute($command)->isSuccess();
    }
}
