<?php
declare(strict_types=1);

namespace App\Tests\FileReader;

use App\Exception\LocalFileNotFoundException;
use App\FileReader\FileReaderLocal;
use PHPUnit\Framework\TestCase;

class FileReaderTest extends TestCase
{
    protected function setUp( ): void
    {
        parent::setUp();
    }

    /** @test */
    public function throws_exception_when_file_not_found()
    {
        $fileReader = new FileReaderLocal("random/path");
        $this->expectException(LocalFileNotFoundException::class);
        $fileReader->getData("invalid_file_name");
    }
}
