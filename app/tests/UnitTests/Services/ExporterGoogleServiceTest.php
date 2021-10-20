<?php

declare(strict_types=1);

namespace App\Tests\UnitTests\Services;

use Google_Service_Sheets;
use PHPUnit\Framework\TestCase;

class ExporterGoogleServiceTest extends TestCase
{
    /** @test */
    public function export_works_correctly()
    {
         $googleSheetServiceMock = $this->createMock(Google_Service_Sheets::class);
    }
}