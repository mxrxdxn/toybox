<?php

namespace Maxweb\Toybox\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakePostTypeCommand extends Command
{
    /**
     * @var string $defaultName The name/signature of the command.
     */
    protected static $defaultName = "make:post-type";

    /**
     * @var string $defaultDescription The command description shown when running "php toybox list".
     */
    protected static $defaultDescription = 'Creates a new post type.';

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
        // Arguments
        $name = $input->getArgument("name");

        // Transformations of the name (and slug)
        $singularName            = singularize($name);
        $pluralName              = pluralize($name);
        $sluggedSingularName     = slugify($singularName);
        $capitalizedSingularName = ucwords($singularName);
        $capitalizedPluralName   = ucwords($pluralName);


        // Create the file
        $initFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-post-type/example-post-type.php");
        $initFile = str_replace('SLUGGED_SINGULAR_POST_TYPE', $sluggedSingularName, $initFile);
        $initFile = str_replace('SINGULAR_POST_TYPE', $capitalizedSingularName, $initFile);
        $initFile = str_replace('PLURAL_POST_TYPE', $capitalizedPluralName, $initFile);
        file_put_contents(TOYBOX_DIR . "/post-types/{$sluggedSingularName}.php", $initFile);

        // Show success message
        $output->writeln("<info>Created post type successfully.</info>");

        return Command::SUCCESS;
    }

    /**
     * Override descriptions, help text etc.
     */
    protected function configure(): void
    {
        // Set help text
        $this->setHelp("Create a post type from the stub.");

        /**
         * Arguments
         */

        // Post Type Name
        $this->addArgument(
            "name",
            InputArgument::REQUIRED,
            "The name of the post type."
        );
    }
}