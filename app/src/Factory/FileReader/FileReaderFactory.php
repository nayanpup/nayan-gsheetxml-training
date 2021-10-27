<?php

declare(strict_types=1);

namespace App\Factory\FileReader;

use App\Client\FtpClient;
use App\Constants\AppConstants;
use App\Exception\InvalidFileReaderArgumentException;
use App\Interfaces\FileReaderInterface;

class FileReaderFactory
{
    /**
     * @var FtpClient
     */
    private $ftpClient;

    public function __construct(FtpClient $ftpClient)
    {
        $this->ftpClient = $ftpClient;
    }

    public function getReader(string $uploadFrom): FileReaderInterface
    {
        switch ($uploadFrom) {
            case AppConstants::LOCAL:
                return new FileReaderLocal();
            case AppConstants::REMOTE:
                return new FileReaderRemote($this->ftpClient);
            default:
                throw new InvalidFileReaderArgumentException('Invalid arguments for file reader.');
        }
    }
}