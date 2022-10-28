<?php

namespace Maxweb\Toybox\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InspireCommand extends Command
{
    /**
     * @var string $defaultName The name/signature of the command.
     */
    protected static $defaultName = "inspire";

    /**
     * @var string $defaultDescription The command description shown when running "php toybox list".
     */
    protected static $defaultDescription = "Prints an inspiring quote.";

    /**
     * Execute the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $quotes = [
            "\"I'm not saying I support Hitler, I'm just saying a dictatorship isn't a terrible idea.\" - Danny, 2022",
            "\"Sounds like a you problem.\" - Ron, 2021",
            "\"She knows it's my couch.\" - Sam, 2022",
            "\"Sharing a bathroom with 4 other dudes isn't all bad.\" - Ben, 2022",
            "\"Don't tell me to clear my cache!\" - Em, 2022",
            "?nonitro fixes everything.",
            "\"It works on my local.\"",
            "If clearing your cache doesn't fix the problem, it never worked in the first place.",
            "Always code as if the person who ends up maintaining your code will be a violent psychopath who knows where you live.",
        ];

        $key = array_rand($quotes);

        $output->writeln("<comment>{$quotes[$key]}</comment>");

        return Command::SUCCESS;
    }

    /**
     * Override descriptions, help text etc.
     */
    protected function configure(): void
    {
        // Set help text
        $this->setHelp("Print an inspiring quote to stdout.");
    }
}