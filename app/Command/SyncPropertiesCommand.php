<?php

namespace Otomaties\Omnicasa\Command;

use Otomaties\Omnicasa\Services\SyncService;
use Otomaties\Omnicasa\Command\Contracts\CommandContract;

class SyncPropertiesCommand implements CommandContract
{
    
    public const COMMAND_NAME = 'omnicasa sync properties';

    public const COMMAND_DESCRIPTION = 'Sync Omnicasa properties with WordPress';

    public const COMMAND_ARGUMENTS = [
        // [
        //     'type' => 'assoc',
        //     'name' => 'bar',
        //     'description' => 'Enter a value for bar',
        //     'optional' => false,
        // ]
    ];

    public function __construct(private SyncService $syncService)
    {
    }

    /**
     * Run below command to activate:
     *
     * wp vrd sync handle
     */
    public function handle(array $args, array $assocArgs): void
    {
        $defaultAssocArgs = [
            'limit' => null
        ];
        $assocArgs = array_merge($defaultAssocArgs, $assocArgs);

        $this->syncService->syncProperties($assocArgs['limit']);

        \WP_CLI::success('Done syncing');
    }
}
