<?php

declare(strict_types=1);

namespace App\Command;

use App\Constants\AppConstants;
use App\Exception\InvalidFileReaderArgumentException;
use App\Factory\FileReader\FileReaderFactory;
use App\Interfaces\ExportInterface;
use App\Transformers\FileDataTransformer;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UploadCommand extends Command
{
    const UPLOAD_FROM = 'upload-from';
    const ARGUMENT_FILE = 'file';
    const OPTION_DESCRIPTION = 'Options: local, remote';
    protected static $defaultName = 'app:upload-command';

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var FileReaderFactory
     */
    private $fileReaderFactory;

    /**
     * @var FileDataTransformer
     */
    private $fileDataTransformer;

    /**
     * @var ExportInterface
     */
    private $exporter;

    public function __construct(
        string                $name = null,
        ExportInterface       $exporter,
        LoggerInterface       $logger,
        FileReaderFactory     $fileReaderFactory,
        FileDataTransformer   $fileDataTransformer
    )
    {
        parent::__construct($name);
        $this->logger = $logger;
        $this->fileReaderFactory = $fileReaderFactory;
        $this->fileDataTransformer = $fileDataTransformer;
        $this->exporter = $exporter;
    }

    protected function configure(): void
    {
        $this->setDescription('This is xml import export command')
            ->addOption(
                self::UPLOAD_FROM,
                null,
                InputOption::VALUE_REQUIRED,
                self::OPTION_DESCRIPTION,
                AppConstants::LOCAL
            )
            ->addArgument(self::ARGUMENT_FILE, InputArgument::REQUIRED, 'Xml file path from local or URL');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Executing xml to google sheet export command");
        try {
            $reader = $this->fileReaderFactory->getReader($input->getOption(self::UPLOAD_FROM));
        } catch (InvalidFileReaderArgumentException $exception) {
            $this->logger->error("Failure: " . $exception->getMessage());
            return self::FAILURE;
        }

        $output->writeln("Fetching the data as per given options and arguments");
        $fileName = $input->getArgument(self::ARGUMENT_FILE);
        try {
            $fileData = $reader->getData($fileName);
        } catch (Exception $exception) {
            $this->logger->error("Failure: " . $exception->getMessage());
            return self::FAILURE;
        }

        $output->writeln("Transforming file data");
        try {
            $exportDTO = $this->fileDataTransformer->transform($fileData);
        } catch (Exception $exception) {
            $this->logger->error("Failure: " . $exception->getMessage());
            return self::FAILURE;
        }

        $output->writeln("Starting to push xml to google sheet");
        try {
            $spreadsheetId = $this->exporter->export($exportDTO);
            $spreadsheetLink = AppConstants::SPREADSHEET_URL . $spreadsheetId;
            $output->writeln('Spreadsheet created: ' . $spreadsheetLink);
            $this->logger->info(sprintf("Success: %s pushed successfully", $spreadsheetLink));
            return self::SUCCESS;
        } catch (Exception $exception) {
            $this->logger->error("Failure: " . $exception->getMessage());
            return self::FAILURE;
        }
    }
}
