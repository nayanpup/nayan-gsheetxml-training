<?php

declare(strict_types=1);

namespace App\Transformers;

use App\DTO\ExportDTO;
use App\Exception\EmptyLocalFileException;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FileDataTransformer
{
    public function transform($content): ExportDTO
    {
        $decoder = new Serializer([new ObjectNormalizer()], [new XmlEncoder()]);
        $fileData = $decoder->decode($content, 'xml');

        if (empty($fileData)) {
            throw new EmptyLocalFileException("File does not contain any data");
        }

        $exportData = [];

        foreach ($fileData['item'] as $key => $datum) {
            if (0 === $key) {
                $exportData[] = array_map(function ($arg) {
                    return ucfirst($arg);
                }, array_keys($datum));
            }

            $exportData[] = array_map(function ($arg) {
                return is_array($arg) ? '' : trim($arg);
            }, array_values($datum));
        }

        return new ExportDTO($exportData);
    }
}