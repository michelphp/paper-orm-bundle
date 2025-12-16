<?php

namespace Michel\PaperORMBundle\Command;

use Michel\Console\CommandRunner;
use Michel\PaperORM\Command\DatabaseCreateCommand;
use Michel\PaperORM\EntityManagerInterface;
use Michel\PaperORMBundle\Tools\MichelConsoleWrapper;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'paper:database:create', description: 'Creates the database configured for PaperORM')]
class PaperDatabaseCreateCommand extends Command
{
    private EntityManagerInterface $em;


    public function __construct(EntityManagerInterface  $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
        $this
            ->setName('paper:database:create')
            ->setDescription('Creates the database configured for PaperORM')
            ->addOption('if-not-exists', null, InputOption::VALUE_NONE, 'Only create the database if it does not already exist');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $options = [];
        $ifNotExists = $input->getOption('if-not-exists');
        if ($ifNotExists) {
            $options = ['--if-not-exists'];
        }
        return MichelConsoleWrapper::executeForSymfony(new DatabaseCreateCommand($this->em), [], $options, function ($message) use ($output) {
            $output->write($message);
        });
    }

}
