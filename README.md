# Compress image size

**ImageCompressor** - this is an easy way to compress images on the fly.

There are two methods: sourceFile, compress

* sourceFile - takes the path to the file to be compressed. Returns an ImageCompressor object.
* compress - takes a file path to save after compression. Returns the boolean value of the execution result.

If a save path was not passed to "compress" method, the file that was passed in the "sourceFile" method will be
compressed.

# Requirements

To use, you need to install the following libraries:

### ImageMagick

```
sudo apt-get -y install imagemagick
```

### pngquant

```
sudo apt-get -y install pngquant
```

# Installation

## Composer

Execute the following command to install this package as a dependency in your project:

```
composer require tihiy-production/php-image-compressor
```

## Usage example

```php
ImageCompressor::sourceFile('test.jpg')->compress('test_compressed.jpg');
```
