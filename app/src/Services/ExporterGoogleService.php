<?php

declare(strict_types=1);

namespace App\Services;

use App\DTO\ExportDTO;
use App\Interfaces\ExportInterface;
use Google\Service\Sheets\UpdateValuesResponse;
use Google_Service_Drive;
use Google_Service_Drive_Permission;
use Google_Service_Sheets;
use Google_Service_Sheets_Spreadsheet;
use Google_Service_Sheets_ValueRange;
use Psr\Log\LoggerInterface;

class ExporterGoogleService implements ExportInterface
{
    /**
     * @var Google_Service_Sheets
     */
    private $serviceSheets;

    /**
     * @var Google_Service_Drive
     */
    private $serviceDrive;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Google_Service_Sheets $serviceSheets,
        Google_Service_Drive $serviceDrive,
        LoggerInterface $logger
    )
    {
        $this->serviceSheets = $serviceSheets;
        $this->serviceDrive = $serviceDrive;
        $this->logger = $logger;
    }

    private function create(): string
    {
        $spreadsheet = new Google_Service_Sheets_Spreadsheet([
            'properties' => [
                'title' => 'Xml to G Sheet'
            ]
        ]);

        $spreadsheet = $this->serviceSheets->spreadsheets->create($spreadsheet, [
            'fields' => 'spreadsheetId'
        ]);

        $this->logger->info(printf("Created Spreadsheet ID: %s\n", $spreadsheet->spreadsheetId));

        return $spreadsheet->spreadsheetId;
    }

    private function updateValues(string $spreadsheetId, string $range, string $valueInputOption, array $values): UpdateValuesResponse
    {
        $body = new Google_Service_Sheets_ValueRange([
            'values' => $values
        ]);
        $params = [
            'valueInputOption' => $valueInputOption
        ];
        $result = $this->serviceSheets->spreadsheets_values->update($spreadsheetId, $range,
            $body, $params);

        $this->logger->info(printf("%d cells updated.", $result->getUpdatedCells()));

        return $result;
    }

    public function getValues(string $spreadsheetId, string $range)
    {
        $result = $this->serviceSheets->spreadsheets_values->get($spreadsheetId, $range);
        $numRows = $result->getValues() != null ? count($result->getValues()) : 0;
        $this->logger->info(printf("%d rows retrieved.", $numRows));

        return $result;
    }

    public function export(ExportDTO $exportDTO): string
    {
        $spreadsheetId = $this->create();
        $this->setPermissions($spreadsheetId);
        $range = "Sheet1";
        $valueInputOption = "USER_ENTERED";
        $values = $exportDTO->getExportData();

        $this->updateValues(
            $spreadsheetId,
            $range,
            $valueInputOption,
            $values
        );

        return $spreadsheetId;
    }

    private function setPermissions(string $spreadsheetId): void
    {
        $permission = new Google_Service_Drive_Permission();
        $permission->setType('anyone');
        $permission->setRole('reader');

        $this->serviceDrive->permissions->create($spreadsheetId, $permission);
    }
}