<?php

declare(strict_types=1);

namespace App\Tests\UnitTests\Transformers;

use App\Exception\InvalidXMLContentException;
use App\Tests\DataProvider\DataProvider;
use App\Transformers\FileDataTransformer;
use PHPUnit\Framework\TestCase;

class FileDataTransformerTest extends TestCase
{
    /**
     * @var DataProvider
     */
    private $dataProvider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->dataProvider = new DataProvider();
    }

    /** @test */
    public function it_creates_export_dto()
    {
        $transformer = new FileDataTransformer();
        $content = file_get_contents( 'tests/DataProvider/dummy.xml');
        $result = $transformer->transform($content);
        $this->assertEquals($this->dataProvider->exportData(), $result);
    }

    /** @test  */
    public function it_throws_exception_when_invalid_file_provided()
    {
        $transformer = new FileDataTransformer();
        $this->expectException(InvalidXMLContentException::class);
        $content = file_get_contents('tests/DataProvider/abc.txt');
        $transformer->transform($content);
    }
}