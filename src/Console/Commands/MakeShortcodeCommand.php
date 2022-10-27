<?php

namespace Maxweb\Toybox\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakeShortcodeCommand extends Command
{
    /**
     * @var string $defaultName The name/signature of the command.
     */
    protected static $defaultName = "make:shortcode";

    /**
     * @var string $defaultDescription The command description shown when running "php toybox list".
     */
    protected static $defaultDescription = 'Creates a new shortcode.';

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
        $withStyles = $input->getOption("with-styles");
        $withJS     = $input->getOption("with-js");

        // Slug the name
        $sluggedName = slugify($name);

        // Create the directory
        mkdir(TOYBOX_DIR . "/shortcodes/{$sluggedName}");

        // Copy the files over, replacing the names
        $initFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-shortcode/init.php");
        $initFile = str_replace('example-shortcode', $sluggedName, $initFile);
        file_put_contents(TOYBOX_DIR . "/shortcodes/{$sluggedName}/init.php", $initFile);

        $templateFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-shortcode/template.php");
        $templateFile = str_replace('example-shortcode', $sluggedName, $templateFile);
        file_put_contents(TOYBOX_DIR . "/shortcodes/{$sluggedName}/template.php", $templateFile);

        // Show success message
        $output->writeln("<info>Created shortcode successfully.</info>");

        // Create resources folder
        if ($withStyles || $withJS) {
            // Create the resources directory
            mkdir(TOYBOX_DIR . "/shortcodes/{$sluggedName}/resources");

            $output->writeln("<info>Created resources directory: ./shortcodes/{$sluggedName}/resources</info>");
        }

        // Create Styles
        if ($withStyles) {
            // Create the SCSS directory
            mkdir(TOYBOX_DIR . "/shortcodes/{$sluggedName}/resources/scss");

            // Create the main SCSS file
            $scssFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-shortcode/resources/scss/example-shortcode.scss");
            $scssFile = str_replace('.shortcode-example', ".shortcode-{$sluggedName}", $scssFile);
            file_put_contents(TOYBOX_DIR . "/shortcodes/{$sluggedName}/resources/scss/{$sluggedName}.scss", $scssFile);

            // Create the variables file
            $variablesFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-shortcode/resources/scss/_variables.scss");
            file_put_contents(TOYBOX_DIR . "/shortcodes/{$sluggedName}/resources/scss/_variables.scss", $variablesFile);

            $output->writeln("<info>Created SCSS stylesheet: ./shortcodes/{$sluggedName}/resources/scss/{$sluggedName}.scss</info>");
        }

        // Create JS
        if ($withJS) {
            // Create the JS directory
            mkdir(TOYBOX_DIR . "/shortcodes/{$sluggedName}/resources/js");

            // Create the main JS file
            $jsFile = file_get_contents(TOYBOX_DIR . "/src/stubs/example-shortcode/resources/js/example-shortcode.js");
            file_put_contents(TOYBOX_DIR . "/shortcodes/{$sluggedName}/resources/js/{$sluggedName}.js", $jsFile);

            $output->writeln("<info>Created JS script: ./shortcodes/{$sluggedName}/resources/js/{$sluggedName}.js</info>");
        }

        // Show a final message if resources were added
        if ($withStyles || $withJS) {
            $output->writeln("<comment>Your shortcode assets should be automatically detected. If you are currently running `npm run watch`,</comment>");
            $output->writeln("<comment>you will need to cancel the process and start it again.</comment>");
        }

        return Command::SUCCESS;
    }

    /**
     * Override descriptions, help text etc.
     */
    protected function configure(): void
    {
        // Set help text
        $this->setHelp("Create a shortcode from the stub.");

        /**
         * Arguments
         */

        // Shortcode Name
        $this->addArgument(
            "name",
            InputArgument::REQUIRED,
            "The name of the shortcode."
        );

        /**
         * Options
         */

        // With Styles
        $this->addOption(
            "with-styles",
            null,
            InputOption::VALUE_NONE,
            "Include style assets"
        );

        // With JS
        $this->addOption(
            "with-js",
            null,
            InputOption::VALUE_NONE,
            "Include JS assets"
        );
    }
}