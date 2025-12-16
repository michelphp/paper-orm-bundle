<?php

namespace Michel\PaperORMBundle\Command;

use http\Encoding\Stream;
use Michel\PaperORM\Collector\EntityDirCollector;
use Michel\PaperORM\Command\DatabaseCreateCommand;
use Michel\PaperORM\Command\DatabaseSyncCommand;
use Michel\PaperORM\EntityManagerInterface;
use Michel\PaperORM\Migration\PaperMigration;
use Michel\PaperORMBundle\Tools\MichelConsoleWrapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(name: 'paper:database:sync', description: 'Update the SQL database structure so it matches the current ORM entities.')]
class PaperDatabaseSyncCommand extends Command
{
    private PaperMigration $migration;

    private EntityDirCollector $entityDirCollector;

    /**
     * @var string
     */
    private $env;

    public function __construct(PaperMigration $migration, EntityDirCollector $entityDirCollector, ParameterBagInterface $params)
    {
        parent::__construct();
        $this->migration = $migration;
        $this->entityDirCollector = $entityDirCollector;
        $this->env = $params->get('kernel.environment');
    }

    protected function configure(): void
    {
        $this
            ->setName('paper:database:sync')
            ->setDescription('Update the SQL database structure so it matches the current ORM entities.')
            ->addOption('no-execute', null, InputOption::VALUE_NONE, 'Show the generated SQL statements without executing them.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $options = [];
        $noExecute = $input->getOption('no-execute');
        if ($noExecute) {
            $options = ['--no-execute'];
        }
        return MichelConsoleWrapper::executeForSymfony(
            new DatabaseSyncCommand($this->migration, $this->entityDirCollector, strtolower($this->env)), [], $options, function ($message) use ($output) {
            $output->write($message);
        });
    }


}
