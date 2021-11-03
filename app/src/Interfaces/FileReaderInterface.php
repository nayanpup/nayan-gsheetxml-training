<?php

declare(strict_types=1);

namespace App\Interfaces;

interface FileReaderInterface
{
    public function getContent(string $source, string $fileName): string;
}