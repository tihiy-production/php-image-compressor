<?php

use PHPUnit\Framework\TestCase;
use tihiy\Compressor\ImageCompressor;

class ImageCompressorTest extends TestCase
{
    public function testCompress()
    {
        $this->assertTrue(
            ImageCompressor::sourceFile('img/example.jpg')->compress('img/example_compressed.jpg')
        );
        $this->assertTrue(
            ImageCompressor::sourceFile('img/example2.png')->compress('img/example2_compressed.png')
        );
    }
}
