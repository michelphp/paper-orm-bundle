<?php

namespace Test\Michel\PaperORMBundle;

use Michel\PaperORMBundle\Command\PaperDatabaseCreateCommand;
use Michel\PaperORMBundle\Command\PaperDatabaseDropCommand;
use Michel\PaperORMBundle\Command\PaperDatabaseSyncCommand;
use Michel\PaperORMBundle\Command\PaperMigrationDiffCommand;
use Michel\PaperORMBundle\Command\PaperMigrationMigrateCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Test\Michel\PaperORMBundle\Fixtures\TestKernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;

class CommandTest extends KernelTestCase
{

    protected static function getKernelClass(): string
    {
        return TestKernel::class;
    }

    protected function setUp(): void
    {
        self::bootKernel();
    }

    protected function tearDown(): void
    {
        $folder = self::$kernel->getContainer()->getParameter('kernel.project_dir') . '/migrations';
        array_map('unlink', glob("$folder/*.*"));
    }

    public function testCreateDatabaseCommand(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = self::$kernel->getContainer()->get(PaperDatabaseCreateCommand::class);
        $application->add($command);

        $commandTester = new CommandTester($application->find('paper:database:create'));
        $exitCode = $commandTester->execute([
            '--if-not-exists' => true,
        ]);

        $this->assertSame(0, $exitCode, 'Command should return success code 0');
        $this->assertStringContainsString(
            '[INFO] The SQL database ":memory:" has been successfully created (if it did not already exist).',
            $commandTester->getDisplay()
        );
    }

    public function testDropDatabaseCommand(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = self::$kernel->getContainer()->get(PaperDatabaseDropCommand::class);
        $application->add($command);

        $commandTester = new CommandTester($application->find('paper:database:drop'));
        $exitCode = $commandTester->execute([
        ]);


        $this->assertSame(1, $exitCode, 'Command should return success code 1');
        $this->assertStringContainsString(
            '[ERROR] You must use the --force option to drop the database.',
            $commandTester->getDisplay()
        );
        $exitCode = $commandTester->execute([
            '--force' => true
        ]);

        $this->assertSame(0, $exitCode, 'Command should return success code 0');
        $this->assertStringContainsString(
            '[OK] The SQL database has been successfully dropped.',
            $commandTester->getDisplay()
        );
    }

    public function testDatabaseSyncCommand(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = self::$kernel->getContainer()->get(PaperDatabaseSyncCommand::class);
        $application->add($command);

        $commandTester = new CommandTester($application->find('paper:database:sync'));
        $exitCode = $commandTester->execute([
        ]);


        $this->assertSame(0, $exitCode, 'Command should return success code 0');
        $this->assertStringContainsString(
            'Normal entities : 0',
            $commandTester->getDisplay()
        );
    }

    public function testMigrationDiffCommand(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = self::$kernel->getContainer()->get(PaperMigrationDiffCommand::class);
        $application->add($command);

        $commandTester = new CommandTester($application->find('paper:migration:diff'));
        $exitCode = $commandTester->execute([
        ]);

        $this->assertSame(0, $exitCode, 'Command should return success code 0');
        $this->assertStringContainsString(
            '[INFO] No application migration file was generated — schema already in sync.',
            $commandTester->getDisplay()
        );
    }

    public function testMigrationMigrateCommand(): void
    {
        self::bootKernel();
        $application = new Application(self::$kernel);

        $command = self::$kernel->getContainer()->get(PaperMigrationMigrateCommand::class);
        $application->add($command);

        $commandTester = new CommandTester($application->find('paper:migration:migrate'));
        $exitCode = $commandTester->execute([
        ]);


        $this->assertSame(0, $exitCode, 'Command should return success code 0');
        $this->assertStringContainsString(
            '[INFO] No migrations to run. The database is already up to date.',
            $commandTester->getDisplay()
        );

    }
}
