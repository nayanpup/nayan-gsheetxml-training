<?php

declare(strict_types=1);

namespace App\Factory\FileReader;

use App\Constants\AppConstants;
use App\Exception\InvalidFileReaderArgumentException;
use App\Interfaces\FileReaderInterface;

class FileReaderFactory
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    public function __construct(
        string $host,
        string $username,
        string $password
    ) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }

    public function getReader(string $uploadFrom): FileReaderInterface
    {
        switch ($uploadFrom) {
            case AppConstants::LOCAL:
                return new FileReaderLocal();
            case AppConstants::REMOTE:
                return new FileReaderRemote(
                    $this->host,
                    $this->username,
                    $this->password
                );
            default:
                throw new InvalidFileReaderArgumentException('Invalid arguments for file reader.');
        }
    }
}