<?php

declare(strict_types=1);

namespace App\Interfaces;

interface FileReaderInterface
{
    public function getData(string $fileName): string;
}