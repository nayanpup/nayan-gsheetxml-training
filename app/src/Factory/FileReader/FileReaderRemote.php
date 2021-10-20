<?php

declare(strict_types=1);

namespace App\Factory\FileReader;

use App\Exception\InvalidCredentialsException;
use App\Exception\InvalidRemoteConnectionException;
use App\Exception\RemoteFileNotFoundException;
use App\Interfaces\FileReaderInterface;
use Exception;

class FileReaderRemote implements FileReaderInterface
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

    public function getData(string $fileName): string
    {
        $connection = ftp_connect($this->host);
        if (!$connection) {
            throw new InvalidRemoteConnectionException('Invalid host.');
        }

        try {
            ftp_login($connection, $this->username, $this->password);
        } catch (Exception $e) {
            throw new InvalidCredentialsException('Invalid username or password');
        }

        $tmpFile = tempnam('/tmp', 'FTP_FILE');

        try {
            ftp_pasv($connection, true);
            ftp_get($connection, $tmpFile, $fileName, FTP_BINARY);
        } catch (Exception $e) {
            throw new RemoteFileNotFoundException(sprintf("%s file not found", $fileName));
        }

        $fileContents = file_get_contents($tmpFile);
        unlink($tmpFile);

        return $fileContents;
    }
}