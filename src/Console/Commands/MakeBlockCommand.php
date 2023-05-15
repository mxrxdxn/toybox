<?php

namespace Maxweb\Toybox\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeBlockCommand extends Command
{
    /**
     * @var string $defaultName The name/signature of the command.
     */
    protected static $defaultName = "make:block";

    /**
     * @var string $defaultDescription The command description shown when running "php toybox list".
     */
    protected static $defaultDescription = 'Creates a new block.';

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

        // Options
        $withoutStyles = $input->getOption("without-styles");
        $withoutJS     = $input->getOption("without-js");

        // Slug the name
        $sluggedName = slugify($name);

        // Create the directory
        mkdir(TOYBOX_DIR . "/blocks/{$sluggedName}");

        // Copy the files over, replacing the names
        $initFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-block/init.php");
        $initFile = str_replace('example-block', $sluggedName, $initFile);
        $initFile = str_replace('Example Block', $name, $initFile);
        file_put_contents(TOYBOX_DIR . "/blocks/{$sluggedName}/init.php", $initFile);

        $jsonDeclaration = file_get_contents(TOYBOX_DIR . "/src/stubs/example-block/block.json");
        $jsonDeclaration = str_replace('example-block', $sluggedName, $jsonDeclaration);
        $jsonDeclaration = str_replace('Example Block', $name, $jsonDeclaration);
        file_put_contents(TOYBOX_DIR . "/blocks/{$sluggedName}/block.json", $jsonDeclaration);

        $templateFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-block/template.php");
        $templateFile = str_replace('example-block', $sluggedName, $templateFile);
        $templateFile = str_replace('example-', "{$sluggedName}-", $templateFile);
        $templateFile = str_replace('block-example', "block-{$sluggedName}", $templateFile);
        $templateFile = str_replace('Example Block', $name, $templateFile);
        file_put_contents(TOYBOX_DIR . "/blocks/{$sluggedName}/template.php", $templateFile);

        // Show success message
        $output->writeln("<info>Created block successfully.</info>");

        // Create resources folder
        if (! ($withoutStyles && $withoutJS)) {
            // Create the resources directory
            mkdir(TOYBOX_DIR . "/blocks/{$sluggedName}/resources");

            $output->writeln("<info>Created resources directory: ./blocks/{$sluggedName}/resources</info>");
        }

        // Create Styles
        if (! $withoutStyles) {
            // Create the SCSS directory
            mkdir(TOYBOX_DIR . "/blocks/{$sluggedName}/resources/scss");

            // Create the main SCSS file
            $scssFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-block/resources/scss/example-block.scss");
            $scssFile = str_replace('.block-example', ".block-{$sluggedName}", $scssFile);
            file_put_contents(TOYBOX_DIR . "/blocks/{$sluggedName}/resources/scss/{$sluggedName}.scss", $scssFile);

            // Create the variables file
            $variablesFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-block/resources/scss/_variables.scss");
            file_put_contents(TOYBOX_DIR . "/blocks/{$sluggedName}/resources/scss/_variables.scss", $variablesFile);

            $output->writeln("<info>Created SCSS stylesheet: ./blocks/{$sluggedName}/resources/scss/{$sluggedName}.scss</info>");
        }

        // Create JS
        if (! $withoutJS) {
            // Create the JS directory
            mkdir(TOYBOX_DIR . "/blocks/{$sluggedName}/resources/js");

            // Create the main JS file
            $jsFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-block/resources/js/example-block.js");
            file_put_contents(TOYBOX_DIR . "/blocks/{$sluggedName}/resources/js/{$sluggedName}.js", $jsFile);

            $output->writeln("<info>Created JS script: ./blocks/{$sluggedName}/resources/js/{$sluggedName}.js</info>");
        }

        // Show a final message if resources were added
        if (! ($withoutStyles || $withoutJS)) {
            $output->writeln("<comment>Your block assets should be automatically detected. If you are currently running `npm run watch`, you will need to cancel the process and start it again.</comment>");
        }

        return Command::SUCCESS;
    }

    /**
     * Override descriptions, help text etc.
     */
    protected function configure(): void
    {
        // Set help text
        $this->setHelp("Create a block from the stub.");

        /**
         * Arguments
         */

        // Block Name
        $this->addArgument(
            "name",
            InputArgument::REQUIRED,
            "The name of the block."
        );

        /**
         * Options
         */

        // With Styles
        $this->addOption(
            "without-styles",
            null,
            InputOption::VALUE_NONE,
            "Don't include style assets"
        );

        // With JS
        $this->addOption(
            "without-js",
            null,
            InputOption::VALUE_NONE,
            "Don't include JS assets"
        );
    }
}