<?php

namespace Otomaties\Omnicasa\Command;

use Otomaties\Omnicasa\Command\Contracts\CommandContract;
use Otomaties\Omnicasa\Services\SyncService;

class SyncStatusesComand implements CommandContract
{
    public const COMMAND_NAME = 'omnicasa sync property-statuses';

    public const COMMAND_DESCRIPTION = 'Sync Omnicasa statuses with WordPress';

    public const COMMAND_ARGUMENTS = [];

    public function __construct(private SyncService $syncService) {}

    /**
     * Run below command to activate:
     *
     * wp vrd sync handle
     */
    public function handle(array $args, array $assocArgs): void
    {
        $this->syncService->syncStatuses();

        \WP_CLI::success('Done syncing');
    }
}
