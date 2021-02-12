# Compress image size

**ImageCompressor** - this is an easy way to compress images on the fly.

There are following methods:

* sourceFile - takes the path to the file to be compressed. (requred)
* compress - takes a file path to save after compression. Returns the boolean value of the execution result. If a save
  path was not passed, the file that was passed in the "sourceFile" method will be compressed directly. (optional)

# Requirements

The following libraries need to be installed:

### Jpegoptim

```
sudo apt-get -y install jpegoptim
```

### Pngquant

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
