<?php

namespace Michel\PaperORMBundle\Command;

use Michel\Console\CommandRunner;
use Michel\PaperORM\Command\DatabaseCreateCommand;
use Michel\PaperORM\Command\DatabaseDropCommand;
use Michel\PaperORM\EntityManagerInterface;
use Michel\PaperORMBundle\Tools\MichelConsoleWrapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

#[AsCommand(name: 'paper:database:drop', description: 'Drops the configured SQL database',)]
class PaperDatabaseDropCommand extends Command
{
    private EntityManagerInterface $em;
    private string $env;


    public function __construct(EntityManagerInterface $em, ParameterBagInterface $params)
    {
        parent::__construct();
        $this->em = $em;
        $this->env = $params->get('kernel.environment');
    }

    protected function configure(): void
    {
        $this
            ->setName('paper:database:drop')
            ->setDescription('Drops the configured SQL database')
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Force the database drop without asking for confirmation');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $options = [];
        $force = $input->getOption('force');
        if ($force) {
            $options = ['--force'];
        }
        return MichelConsoleWrapper::executeForSymfony(new DatabaseDropCommand($this->em, strtolower($this->env)), [], $options, function ($message) use ($output) {
            $output->write($message);
        });
    }


}
