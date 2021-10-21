<?php
declare(strict_types=1);

namespace App\Tests\UnitTests\FileReader;

use App\Constants\AppConstants;
use App\Exception\InvalidFileReaderArgumentException;
use App\Exception\LocalFileNotFoundException;
use App\Factory\FileReader\FileReaderFactory;
use App\Factory\FileReader\FileReaderLocal;
use App\Factory\FileReader\FileReaderRemote;
use PHPUnit\Framework\TestCase;

class FileReaderTest extends TestCase
{
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
        $fileFactory = new FileReaderFactory("host", "username", "password");
        $this->expectException(InvalidFileReaderArgumentException::class);
        $fileFactory->getReader("invalid_argument");
    }

    /** @test */
    public function correct_instance_created_when_passed_local_argument()
    {
        $fileFactory = new FileReaderFactory("host", "username", "password");
        $result = $fileFactory->getReader(AppConstants::LOCAL);
        $this->assertInstanceOf(FileReaderLocal::class, $result);
    }

    /** @test */
    public function correct_instance_created_when_passed_remote_argument()
    {
        $fileFactory = new FileReaderFactory("host", "username", "password");
        $result = $fileFactory->getReader(AppConstants::REMOTE);
        $this->assertInstanceOf(FileReaderRemote::class, $result);
    }
}
