<?php

declare(strict_types=1);

namespace App\DTO;

class ExportDTO
{
    /**
     * @var array
     */
    private $exportData;

    public function __construct(array $data)
    {
        $this->exportData = $data;
    }

    /**
     * @return array
     */
    public function getExportData(): array
    {
        return $this->exportData;
    }
}