<?php
declare(strict_types=1);

namespace App\Tests\UnitTests\FileReader;

use App\Exception\LocalFileNotFoundException;
use App\Factory\FileReader\FileReaderLocal;
use PHPUnit\Framework\TestCase;

class FileReaderTest extends TestCase
{
    /** @test */
    public function throws_exception_when_file_not_found()
    {
        $fileReader = new FileReaderLocal();
        $this->expectException(LocalFileNotFoundException::class);
        $fileReader->getData("invalid_file_name");
    }
}
