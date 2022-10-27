<?php

namespace Maxweb\Toybox\Console\Commands;

use Exception;
use Maxweb\Toybox\Console\Kernel;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

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
        $domain     = $input->getOption("domain");
        $fieldGroup = $input->getOption("fieldgroup");
        $location   = $input->getOption("location");

        // Connect to WordPress
        Kernel::connectToWordpress($domain);

        // Multisite also requires a "domain" option - if the domain isn't set, throw an error
        if (is_multisite() && $domain === null) {
            $output->writeln("<error>Domain must be set for multisite installations.</error>");

            return self::INVALID;
        }

        // Set ACF write path - we need to do this here so the block exports to the correct directory
        add_filter('acf/settings/save_json', function ($path) use ($name) {
            return get_stylesheet_directory() . '/blocks/' . slugify($name) . '/acf-json';
        });

        // Search by location
        if ($location !== false) {
            // Fetch all field groups for the block
            $fieldGroups = acf_get_field_groups(['block' => 'acf/' . slugify($name)]);
            $totalGroups = count($fieldGroups);
            $groupLabel  = $totalGroups === 1 ? "group" : "groups";

            $output->writeln("<info>Found {$totalGroups} field {$groupLabel} for block \"{$name}\"</info>");

            // Create the directory (if necessary)
            if (! $this->acfPathExists($name)) {
                $this->createAcfPath($name);
            }

            $output->writeln("<info>Exporting field {$groupLabel} for \"{$name}\"..</info>");

            // Loop all the field groups and export the settings for each
            foreach ($fieldGroups as $fieldGroup) {
                $fieldGroup['fields'] = acf_get_fields($fieldGroup['ID']);
                unset($fieldGroup['local_file']);

                $output->writeln("<info>Exporting field group \"{$fieldGroup['title']}\"..</info>");

                // Write the file
                acf_write_json_field_group($fieldGroup);
            }
        } else {
            // If the name of the field group isn't passed in, ask for it.
            if (empty($fieldGroup)) {
                // Get all field groups
                $groups = [];
                foreach (acf_get_field_groups() as $fieldGroup) {
                    $groups[] = $fieldGroup['title'];
                }

                // Prompt for the field group
                $helper   = $this->getHelper('question');
                $question = new ChoiceQuestion(
                    'Please select a field group to export',
                    $groups
                );

                $question->setErrorMessage('Field group %s is invalid.');

                $fieldGroup = $helper->ask($input, $output, $question);
            }

            // Grab the field group, or error if it doesn't exist
            if (! $fieldGroup = $this->getFieldGroup($fieldGroup)) {
                $output->writeln("<error>Could not locate field group.</error>");
                return self::FAILURE;
            }

            // Create the directory (if necessary)
            if (! $this->acfPathExists($name)) {
                $this->createAcfPath($name);
            }

            $output->writeln("<info>Exporting field group \"{$fieldGroup['title']}\"..</info>");

            // Write the file
            acf_write_json_field_group($fieldGroup);
        }

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

        $this->addOption(
            "fieldgroup",
            "g",
            InputOption::VALUE_REQUIRED,
            "The name of the field group."
        );

        $this->addOption(
            "location",
            "l",
            InputOption::VALUE_NONE,
            "Export all field groups for this block, based on location."
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
                unset($fieldGroup['local_file']);

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