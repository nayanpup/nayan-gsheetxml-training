<?php
declare(strict_types=1);

namespace App\Tests\Unit\FileReader;

use App\Client\FtpClient;
use App\Constants\AppConstants;
use App\Exception\InvalidFileReaderArgumentException;
use App\Exception\LocalFileNotFoundException;
use App\Factory\FileReader\FileReaderFactory;
use App\Factory\FileReader\FileReaderLocal;
use App\Factory\FileReader\FileReaderRemote;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FileReaderTest extends TestCase
{
    /**
     * @var FtpClient|mixed|MockObject
     */
    private $ftpClientMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->ftpClientMock = $this->createMock(FtpClient::class);
    }

    /** @test */
    public function it_throws_exception_when_file_not_found()
    {
        $fileReader = new FileReaderLocal();
        $this->expectException(LocalFileNotFoundException::class);
        $fileReader->getData("invalid_file_name");
    }

    /** @test */
    public function it_throws_error_when_invalid_argument_is_passed_from_command()
    {
        $fileFactory = new FileReaderFactory($this->ftpClientMock);
        $this->expectException(InvalidFileReaderArgumentException::class);
        $fileFactory->getReader("invalid_argument");
    }

    /** @test */
    public function correct_instance_created_when_passed_local_argument()
    {
        $fileFactory = new FileReaderFactory($this->ftpClientMock);
        $result = $fileFactory->getReader(AppConstants::LOCAL);
        $this->assertInstanceOf(FileReaderLocal::class, $result);
    }

    /** @test */
    public function correct_instance_created_when_passed_remote_argument()
    {
        $fileFactory = new FileReaderFactory($this->ftpClientMock);
        $result = $fileFactory->getReader(AppConstants::REMOTE);
        $this->assertInstanceOf(FileReaderRemote::class, $result);
    }

    /** @test */
    public function return_file_content_from_remote_ftp_server()
    {
        $remoteFileReader =  new FileReaderRemote($this->ftpClientMock);
        $content = file_get_contents('tests/DataProvider/dummy.xml');

        $this->ftpClientMock->expects(self::once())
            ->method('readFileContent')
            ->willReturn($content);

        $this->assertEquals($remoteFileReader->getData('dummy.xml'), $content);
    }

    /** @test */
    public function return_correct_file_content_from_local_file()
    {
        $content = file_get_contents('tests/DataProvider/dummy.xml');
        $localFileReader =  new FileReaderLocal();
        $result = $localFileReader->getData('tests/DataProvider/dummy.xml');
        $this->assertEquals($result, $content);
    }
}
