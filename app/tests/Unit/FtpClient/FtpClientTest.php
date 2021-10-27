<?php

declare(strict_types=1);

namespace App\Tests\Unit\FtpClient;

use App\Client\FtpClient;
use App\Exception\InvalidCredentialsException;
use App\Exception\InvalidRemoteConnectionException;
use PHPUnit\Framework\TestCase;

class FtpClientTest extends TestCase
{
    /** @test  */
    public function throws_exception_when_invalid_host_provided()
    {
        $ftp = new FtpClient("invalid_host", getenv('FTP_USERNAME'), getenv('FTP_PASSWORD'));
        $this->expectException(InvalidRemoteConnectionException::class);
        $ftp->readFileContent("dummy.xml");
    }

    /** @test  */
    public function throws_exception_when_invalid_credentials_provided()
    {
        $ftp = new FtpClient(getenv('FTP_HOST'), "invalid", getenv('FTP_PASSWORD'));
        $this->expectException(InvalidCredentialsException::class);
        $ftp->readFileContent("dummy.xml");
    }
}