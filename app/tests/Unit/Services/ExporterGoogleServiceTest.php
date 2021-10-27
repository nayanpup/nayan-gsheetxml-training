<?php

declare(strict_types=1);

namespace App\Tests\Unit\Services;

use App\Services\ExporterGoogleService;
use App\Tests\DataProvider\DataProvider;
use Google\Service\Sheets\UpdateValuesResponse;
use Google_Service_Drive;
use Google_Service_Drive_Permission;
use Google_Service_Drive_Resource_Permissions;
use Google_Service_Sheets;
use Google_Service_Sheets_Resource_Spreadsheets;
use Google_Service_Sheets_Resource_SpreadsheetsValues;
use Google_Service_Sheets_Spreadsheet;
use Google_Service_Sheets_ValueRange;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ExporterGoogleServiceTest extends TestCase
{
    /**
     * @var Google_Service_Sheets|mixed|MockObject
     */
    private $sheetServiceMock;

    /**
     * @var Google_Service_Sheets_Resource_Spreadsheets|mixed|MockObject
     */
    private $sheetResourceMock;

    /**
     * @var mixed|MockObject|LoggerInterface
     */
    private $loggerMock;

    /**
     * @var Google_Service_Drive|mixed|MockObject
     */
    private $driveServiceMock;

    /**
     * @var Google_Service_Drive_Resource_Permissions|mixed|MockObject
     */
    private $drivePermissionResourceMock;

    /**
     * @var Google_Service_Sheets_Resource_SpreadsheetsValues|mixed|MockObject
     */
    private $spreadsheetsValuesResourceMock;

    /**
     * @var DataProvider
     */
    private $dataProvider;

    protected function setUp(): void
    {
        parent::setUp();
        // Mock to inject into services
        $this->sheetServiceMock = $this->createMock(Google_Service_Sheets::class);

        // Mock for calling create function which requires object of @class Google_Service_Sheets_Resource_Spreadsheets
        $this->sheetResourceMock = $this->createMock(Google_Service_Sheets_Resource_Spreadsheets::class);
        $this->sheetServiceMock->spreadsheets = $this->sheetResourceMock;

        // Mock for calling update function which requires object of @class Google_Service_Sheets_Resource_SpreadsheetsValues
        $this->spreadsheetsValuesResourceMock = $this->createMock(Google_Service_Sheets_Resource_SpreadsheetsValues::class);
        $this->sheetServiceMock->spreadsheets_values = $this->spreadsheetsValuesResourceMock;

        $this->driveServiceMock = $this->createMock(Google_Service_Drive::class);
        // Mock for calling permissions create function which requires object of @class Google_Service_Drive_Resource_Permissions
        $this->drivePermissionResourceMock = $this->createMock(Google_Service_Drive_Resource_Permissions::class);
        $this->driveServiceMock->permissions = $this->drivePermissionResourceMock;

        $this->loggerMock = $this->createMock(LoggerInterface::class);

        $this->dataProvider = new DataProvider();
    }

    /** @test */
    public function export_works_correctly()
    {
        $spreadsheetId = uniqid();
        $resultSheetObj = new Google_Service_Sheets_Spreadsheet();
        $resultSheetObj->spreadsheetId = $spreadsheetId;

        $this->sheetResourceMock->expects(static::once())
            ->method('create')
            ->with(new Google_Service_Sheets_Spreadsheet(
                [
                    'properties' => [
                        'title' => 'Xml to G Sheet'
                    ]
                ]
            ))
            ->willReturn($resultSheetObj);

        $permission = new Google_Service_Drive_Permission();
        $permission->setType('anyone');
        $permission->setRole('reader');

        $this->drivePermissionResourceMock->expects(static::once())
            ->method('create')
            ->with($resultSheetObj->spreadsheetId, $permission);

        $body = new Google_Service_Sheets_ValueRange(['values' => $this->dataProvider->exportData()->getExportData()]);
        $params = ['valueInputOption' => 'USER_ENTERED'];

        $this->spreadsheetsValuesResourceMock->expects(static::once())
            ->method('update')
            ->with(
                $resultSheetObj->spreadsheetId, 'Sheet1', $body, $params
            )
            ->willReturn(new UpdateValuesResponse());

        $exportService = new ExporterGoogleService($this->sheetServiceMock, $this->driveServiceMock, $this->loggerMock);

        $result = $exportService->export($this->dataProvider->exportData());

        $this->assertEquals($spreadsheetId, $result);
    }
}