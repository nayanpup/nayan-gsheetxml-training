<?php

declare(strict_types=1);

namespace App\Tests\UnitTests\Command;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

class CommandTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    /** @test */
    public function upload_command_works_correctly()
    {
        $application = new Application(self::$kernel);
        $command = $application->find('app:upload-command');
        $commandTester = new CommandTester($command);
        $result = $commandTester->execute([]);
        $this->assertEquals(Command::SUCCESS, $result);
    }
}