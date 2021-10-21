<?php

declare(strict_types=1);

namespace App\Transformers;

use App\DTO\ExportDTO;
use App\Exception\EmptyLocalFileException;
use App\Exception\InvalidXMLContentException;
use Exception;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class FileDataTransformer
{
    public function transform(string $content): ExportDTO
    {
        try {
            $decoder = new Serializer([new ObjectNormalizer()], [new XmlEncoder()]);
            $fileData = $decoder->decode($content, 'xml');
        } catch (Exception $exception) {
            throw new InvalidXMLContentException("Invalid xml content");
        }

        $exportData = [];

        foreach ($fileData['item'] as $key => $datum) {
            if (0 === $key) {
                $exportData[] = array_map(
                    function ($arg) {
                        return ucfirst($arg);
                    }, array_keys($datum)
                );
            }

            $exportData[] = array_map(
                function ($arg) {
                    return is_array($arg) ? '' : trim($arg);
                }, array_values($datum)
            );
        }

        return new ExportDTO($exportData);
    }
}