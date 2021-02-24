# Compress image size

**ImageCompressor** - this is an easy way to compress images on the fly.

## Requirements

The following libraries need to be installed:

### Jpegoptim

```
sudo apt-get -y install jpegoptim
```

### Pngquant

```
sudo apt-get -y install pngquant
```

## Installation

### Composer

Execute the following command to install this package as a dependency in your project:

```
composer require tihiy-production/php-image-compressor
```

## Usage

```php
ImageCompressor::sourceFile('test.jpg')->compress('test_compressed.jpg');
```
