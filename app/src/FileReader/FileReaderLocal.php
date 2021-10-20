<?php

declare(strict_types=1);

namespace App\FileReader;

use App\Exception\LocalFileNotFoundException;
use App\Interfaces\FileReaderInterface;

class FileReaderLocal implements FileReaderInterface
{
    public function getData(string $fileName): string
    {
        if (file_exists($fileName)) {
            return file_get_contents($fileName);
        } else {
            throw new LocalFileNotFoundException(
                sprintf('File "%s" could not be found.', $fileName)
            );
        }
    }
}