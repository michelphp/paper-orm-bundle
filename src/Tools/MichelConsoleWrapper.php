<?php

namespace Michel\PaperORMBundle\Tools;

use Michel\Console\Command\CommandInterface;
use Michel\Console\CommandParser;
use Michel\Console\CommandRunner;
use Michel\Console\Output;
use Symfony\Component\Console\Command\Command;

final class MichelConsoleWrapper
{

    public static function executeForSymfony(CommandInterface $command, array $arguments = [], array $options = [], ?callable $stdout = null): int
    {
        $app = new CommandRunner([$command]);
        $exitCode = $app->run(new CommandParser(array_merge([''], [$command->getName()], $arguments, $options)), new Output($stdout));

        if ($exitCode === CommandRunner::CLI_ERROR) {
            return Command::FAILURE;
        }
        if ($exitCode !== CommandRunner::CLI_SUCCESS) {
            return Command::INVALID;
        }

        return Command::SUCCESS;
    }
}
