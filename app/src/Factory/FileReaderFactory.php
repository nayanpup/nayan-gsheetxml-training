<?php

declare(strict_types=1);

namespace App\Factory;

use App\Constants\AppConstants;
use App\Exception\InvalidFileReaderArgumentException;
use App\FileReader\FileReaderLocal;
use App\Interfaces\FileReaderInterface;

class FileReaderFactory
{
    public function getReader(string $uploadFrom): FileReaderInterface
    {
        switch ($uploadFrom) {
            case AppConstants::LOCAL:
                return new FileReaderLocal();
            case AppConstants::REMOTE:
//                return new FileReaderFtp(
//                    $this->ftpHost,
//                    $this->ftpUser,
//                    $this->ftpPassword
//                );
            default:
                throw new InvalidFileReaderArgumentException('Invalid arguments for file reader.');
        }
    }
}