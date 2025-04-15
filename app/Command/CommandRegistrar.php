<?php

namespace Otomaties\Omnicasa\Command;

use Otomaties\Omnicasa\Command\Contracts\CommandContract;
use Otomaties\Omnicasa\Plugin;

class CommandRegistrar
{
    public function __construct(public Plugin $plugin)
    {
        //
    }

    public function register()
    {
        if (! defined('WP_CLI') || ! WP_CLI) {
            return;
        }

        collect(glob($this->plugin->config('paths.app') . '/Command/*.php'))
            ->map(fn($file) => 'Otomaties\\Omnicasa\\Command\\' . str_replace('.php', '', basename($file)))
            ->filter(fn($className) => class_exists($className) && in_array(CommandContract::class, class_implements($className)))
            ->each(function ($commandClass) {
                \WP_CLI::add_command(
                    $commandClass::COMMAND_NAME,
                    function ($args, $assocArgs) use ($commandClass) {
                        $commandInstance = $this->plugin->make($commandClass);
                        $commandInstance->handle($args, $assocArgs);
                    },
                    [
                        'shortdesc' => $commandClass::COMMAND_DESCRIPTION,
                        'synopsis' => $commandClass::COMMAND_ARGUMENTS,
                    ],
                );
            });
    }
}
