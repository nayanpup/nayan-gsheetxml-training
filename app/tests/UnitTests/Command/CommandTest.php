<?php

declare(strict_types=1);

namespace App\Tests\UnitTests\Command;

use App\Command\UploadCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTest extends TestCase
{
    /** @test */
    public function upload_command_works_correctly()
    {
        $uploadCommand = $this->createMock(UploadCommand::class);
        $commandTester = new CommandTester($uploadCommand);
        $result = $commandTester->execute([
            '--upload-from' => 'local',
            'file' => 'tests/DataProvider/dummy.xml'
        ]);

        $this->assertEquals(Command::SUCCESS, $result);
    }
}