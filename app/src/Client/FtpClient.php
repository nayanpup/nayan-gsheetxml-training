<?php

declare(strict_types=1);

namespace App\Client;

use App\Exception\InvalidCredentialsException;
use App\Exception\InvalidRemoteConnectionException;
use App\Exception\RemoteFileNotFoundException;
use Exception;
use phpDocumentor\Reflection\Types\True_;

class FtpClient
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

    /**
     * @var false|resource
     */
    private $connectionId;

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     */
    public function __construct(string $host, string $username, string $password)
    {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
    }

    private function connect(): void
    {
        if (!empty($this->connectionId)) {
            return;
        }

        try {
            $this->connectionId = ftp_connect($this->host);
        } catch (Exception $exception) {
            throw new InvalidRemoteConnectionException('Invalid host.');
        }

        try {
            ftp_login($this->connectionId, $this->username, $this->password);
        } catch (Exception $e) {
            throw new InvalidCredentialsException('Invalid username or password');
        }

        ftp_pasv($this->connectionId, true);
    }

    public function readFileContent(string $fileName): string
    {
        $this->connect();
        $tmpFile = tempnam('/tmp', 'FTP_FILE');

        try {
            ftp_get($this->connectionId, $tmpFile, $fileName, FTP_BINARY);
        } catch (Exception $e) {
            throw new RemoteFileNotFoundException(sprintf("%s file not found", $fileName));
        }

        $fileContents = file_get_contents($tmpFile);
        unlink($tmpFile);

        return $fileContents;
    }

    public function __destruct()
    {
        if ($this->connectionId) {
            ftp_close($this->connectionId);
        }
    }
}