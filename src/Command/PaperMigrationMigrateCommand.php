<?php

namespace Michel\PaperORMBundle\Command;

use Michel\PaperORM\Command\Migration\MigrationMigrateCommand;
use Michel\PaperORM\Migration\PaperMigration;
use Michel\PaperORMBundle\Tools\MichelConsoleWrapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'paper:migration:migrate', description: 'Execute all migrations',)]
class PaperMigrationMigrateCommand extends Command
{
    private PaperMigration $migration;

    public function __construct(PaperMigration $migration)
    {
        parent::__construct();
        $this->migration = $migration;
    }

    protected function configure(): void
    {
        $this
            ->setName('paper:migration:migrate')
            ->setDescription('Execute all migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        return MichelConsoleWrapper::executeForSymfony(new MigrationMigrateCommand($this->migration), [], [], function ($message) use ($output) {
            $output->write($message);
        });
    }
}
