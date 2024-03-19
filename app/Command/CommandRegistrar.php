<?php

namespace Otomaties\Omnicasa\Command;

use Otomaties\Omnicasa\Plugin;

class CommandRegistrar
{
    protected array $commands = [
        SyncPropertiesCommand::class,
        SyncProjectsCommand::class,
        SyncStatusesComand::class,
        SyncTypeOfPropertiesCommand::class,
        SyncWebIdsCommand::class,
    ];

    public function __construct(public Plugin $plugin)
    {
    }

    public function register()
    {
        if (!defined('WP_CLI') || !WP_CLI) {
            return;
        }

        foreach ($this->commands as $commandClass) {
            \WP_CLI::add_command(
                $commandClass::COMMAND_NAME,
                function ($args, $assocArgs) use ($commandClass) {
                    $commandInstance = $this->plugin->make($commandClass);
                    $commandInstance->handle($args, $assocArgs);
                },
                [
                'shortdesc' => $commandClass::COMMAND_DESCRIPTION,
                'synopsis' => $commandClass::COMMAND_ARGUMENTS,
                ]
            );
        }
    }
}
