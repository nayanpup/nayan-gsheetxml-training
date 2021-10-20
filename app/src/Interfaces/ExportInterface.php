<?php

declare(strict_types=1);

namespace App\Interfaces;

use App\DTO\ExportDTO;

interface ExportInterface
{
    public function export(ExportDTO $exportDTO): string;
}