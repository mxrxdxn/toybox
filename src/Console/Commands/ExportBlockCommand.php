<?php

namespace Maxweb\Toybox\Console\Commands;

use Exception;
use Maxweb\Toybox\Console\Kernel;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExportBlockCommand extends Command
{
    /**
     * @var string $defaultName The name/signature of the command.
     */
    protected static $defaultName = "export:block";

    /**
     * @var string $defaultDescription The command description shown when running "php toybox list".
     */
    protected static $defaultDescription = "Exports a block's settings so that it can be redistributed.";

    /**
     * Execute the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Arguments
        $name = $input->getArgument("name");

        // Options
        $domain = $input->getOption("domain");

        // Connect to WordPress
        Kernel::connectToWordpress($domain);

        // Multisite also requires a "domain" option - if the domain isn't set, throw an error
        if (is_multisite() && $domain === null) {
            $output->writeln("<error>Domain must be set for multisite installations.</error>");

            return self::INVALID;
        }

        // Grab the field group, or error if it doesn't exist
        if (! $fieldGroup = $this->getFieldGroup($name)) {
            $output->writeln("<error>Could not locate field group for the block.</error>");
            $output->writeln("<error>Is your naming convention correct? Field group should be titled \"Block: {$name}\".</error>");
            return self::FAILURE;
        }

        // Set ACF write path - we need to do this here so the block exports to the correct directory
        add_filter('acf/settings/save_json', function ($path) use ($name) {
            return get_stylesheet_directory() . '/blocks/' . slugify($name) . '/acf-json';
        });

        // Create the directory (if necessary)
        if (! $this->acfPathExists($name)) {
            $this->createAcfPath($name);
        }

        // Write the file
        acf_write_json_field_group($fieldGroup);

        $output->writeln("<info>Exporting block settings for \"{$name}\"..</info>");



        return Command::SUCCESS;
    }

    /**
     * Override descriptions, help text etc.
     */
    protected function configure(): void
    {
        // Set help text
        $this->setHelp("Exports all block settings, as configured in ACF, so that the block can be reused.");

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
        $this->addOption(
            "domain",
            null,
            InputOption::VALUE_REQUIRED,
            "The domain to export from."
        );
    }

    /**
     * Loops the registered field groups, looking for the correct field group for the given block name.
     *
     * @param string $name
     *
     * @return array|false
     */
    private function getFieldGroup(string $name): array|false
    {
        foreach (acf_get_field_groups() as $fieldGroup) {
            if ($fieldGroup['title'] === "Block: {$name}" || $fieldGroup['title'] === $name) {
                $fieldGroup['fields'] = acf_get_fields($fieldGroup['ID']);

                return $fieldGroup;
            }
        }

        return false;
    }

    /**
     * Checks if the acf-json folder for a block exists.
     *
     * @param string $name The name of the block.
     *
     * @return bool
     */
    private function acfPathExists(string $name): bool
    {
        return file_exists(get_stylesheet_directory() . "/blocks/" . slugify($name) . "/acf-json");
    }

    /**
     * Creates the acf-json folder for a block.
     *
     * @param mixed $name The block name.
     *
     * @return bool
     */
    private function createAcfPath(mixed $name): bool
    {
        return mkdir(get_stylesheet_directory() . "/blocks/" . slugify($name) . "/acf-json");
    }
}