<?php

namespace Maxweb\Toybox\Console;

use Exception;
use Maxweb\Toybox\Console\Commands\ExportBlockCommand;
use Maxweb\Toybox\Console\Commands\InspireCommand;
use Maxweb\Toybox\Console\Commands\MakeBlockCommand;
use Maxweb\Toybox\Console\Commands\MakePostTypeCommand;
use Maxweb\Toybox\Console\Commands\MakeShortcodeCommand;
use Maxweb\Toybox\Theme;
use Symfony\Component\Console\Application;

class Kernel
{
    /**
     * @var Application $application The application.
     */
    private Application $application;

    /**
     * Runs the console application.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        // Set up the application, and register the commands
        $this->bootstrap();
        $this->registerCommands();

        // Run
        $this->application->run();
    }

    /**
     * Sets the application up.
     *
     * @return void
     */
    private function bootstrap(): void
    {
        // Create the application
        $this->application = new Application("Toybox", Theme::VERSION);
    }

    /**
     * Registers the console commands.
     *
     * @return void
     */
    private function registerCommands(): void
    {
        $this->application->add(new ExportBlockCommand());
        $this->application->add(new InspireCommand());
        $this->application->add(new MakeBlockCommand());
        $this->application->add(new MakePostTypeCommand());
        $this->application->add(new MakeShortcodeCommand());
    }

    /**
     * On Bedrock installations, the path to wp-load.php is different - this
     * function figures out if we're on a Bedrock installation.
     *
     * @return bool
     */
    private static function isBedrock(): bool
    {
        return file_exists(__DIR__ . "/../../../../../wp/");
    }

    /**
     * Connect the command line to WordPress when required.
     *
     * @param string|null $domain
     *
     * @return void
     * @throws Exception
     */
    public static function connectToWordpress(string|null $domain = null): void
    {
        $_SERVER['HTTP_HOST'] = $domain;

        // Connect to WordPress
        if (self::isBedrock()) {
            require_once(__DIR__ . "/../../../../../wp/wp-load.php");
        } else {
            require_once(__DIR__ . "/../../../../../wp-load.php");
        }

        // Boot the theme
        Theme::boot();
    }
}