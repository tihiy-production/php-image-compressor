<?php

namespace tihiy\Compressor\Compressor;

use tihiy\Compressor\Object\File;

interface CompressorInterface
{
    public function compress(File $file): File;
}
