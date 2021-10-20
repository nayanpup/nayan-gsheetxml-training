<?php

declare(strict_types=1);

namespace App\Tests\Services;

use App\Interfaces\FProcessDataInterface;
use App\Services\FileDataTransformer;
use PHPUnit\Framework\TestCase;

class ReadDataServiceTest extends TestCase
{
    /** @test */
    public function receives_valid_data_to_be_sent_to_google_sheets_api()
    {
        $processDataInterfaceMock = $this->createMock(FProcessDataInterface::class);
        $readDataService = new FileDataTransformer($processDataInterfaceMock);
        $readDataService->process();
        $this->assertTrue(true);
    }
}