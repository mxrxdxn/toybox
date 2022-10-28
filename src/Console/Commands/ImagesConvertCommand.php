<?php

namespace Maxweb\Toybox\Console\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use WebPConvert\Convert\Exceptions\ConversionFailedException;
use WebPConvert\WebPConvert;

class ImagesConvertCommand extends Command
{
    /**
     * @var string $defaultName The name/signature of the command.
     */
    protected static $defaultName = "images:convert";

    /**
     * @var string $defaultDescription The command description shown when running "php toybox list".
     */
    protected static $defaultDescription = 'Converts all images into WebP format.';

    /**
     * Execute the command.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     * @throws ConversionFailedException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Quality
        $quality = null;

        if (! empty($input->getOption('quality'))) {
            $quality = $input->getOption('quality');
        }

        // Path
        $path = $input->getOption('path');

        if (! empty($path)) {
            if (is_file($path)) {
                $this->convertSingle($path, $quality);
            } elseif (is_dir($path)) {
                $this->convertPath($path, $quality);
            } else {
                $output->writeln("<error>Path was not found.</error>");

                return Command::FAILURE;
            }
        }

        return Command::SUCCESS;
    }

    /**
     * Override descriptions, help text etc.
     */
    protected function configure(): void
    {
        // Set help text
        $this->setHelp("Converts all images into WebP format.");

        /**
         * Options
         */

        // Path
        $this->addOption(
            "path",
            "p",
            InputOption::VALUE_REQUIRED,
            "The path to the image to convert."
        );

        // Quality
        $this->addOption(
            "quality",
            "Q",
            InputOption::VALUE_OPTIONAL,
            "The quality to use when converting."
        );
    }

    private function convertSingle(string $path, int|null $quality)
    {
        $source      = $path;
        $destination = $source . '.webp';
        $options     = [
            "encoding" => "auto",
        ];

        if (! empty($quality)) {
            $options["alpha-quality"] = $quality;
            $options["quality"]       = $quality;
        }

        WebPConvert::convert($source, $destination, $options);
    }

    private function convertPath(string $path, int|null $quality)
    {
        // Strip the trailing slash (if there is one)
        $path = preg_replace('#/$#', '', $path);

        // Loop everything and recursively process the whole directory
        foreach (glob("{$path}/*") as $fileOrDir) {
            if (is_file($fileOrDir)) {
                if (preg_match('#\.(png|jpg|jpeg)$#', $fileOrDir)) {
                    $this->convertSingle($fileOrDir, $quality);
                }
            } elseif (is_dir($fileOrDir)) {
                $this->convertPath($fileOrDir, $quality);
            }
        }
    }
}