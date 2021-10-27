<?php

declare(strict_types=1);

namespace App\Factory\FileReader;

use App\Client\FtpClient;
use App\Interfaces\FileReaderInterface;

class FileReaderRemote implements FileReaderInterface
{
    /**
     * @var FtpClient
     */
    private $ftpClient;

    public function __construct(FtpClient $ftpClient)
    {
        $this->ftpClient = $ftpClient;
    }

    public function getData(string $fileName): string
    {
        return $this->ftpClient->readFileContent($fileName);
    }
}