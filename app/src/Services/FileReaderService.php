<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\AppConstants;
use App\Exception\FileOpenException;
use App\Exception\InvalidFileReaderArgumentException;
use App\Interfaces\FileReaderInterface;
use Exception;
use Generator;

class FileReaderService implements FileReaderInterface
{
    /**
     * @var string
     */
    private $ftpPrefix;

    /**
     * @var string
     */
    private $projectDir;

    public function __construct(string $ftpPrefix, string $projectDir)
    {
        $this->ftpPrefix = $ftpPrefix;
        $this->projectDir = $projectDir;
    }

    public function getContent(string $source, string $fileName): string
    {
        switch ($source) {
            case AppConstants::LOCAL:
                $filePath = $this->projectDir . $fileName;
                break;
            case AppConstants::REMOTE:
                $filePath = $this->ftpPrefix . $fileName;
                break;
            default:
                throw new InvalidFileReaderArgumentException('Invalid arguments.');
        }

        $fileData = "";
        foreach ($this->readContent($filePath) as $line)
        {
            $fileData .= $line;
        }

        return $fileData;
    }

    /**
     * @throws Exception
     */
    private function readContent($filePath): Generator
    {
        $sourceFile = @fopen($filePath, 'rb');

        if (!$sourceFile) {
            throw new FileOpenException('File open failed.');
        }

        while (!feof($sourceFile)) {
            yield trim(fgets($sourceFile), "\r\n");
        }

        fclose($sourceFile);
    }
}