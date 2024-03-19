<?php

namespace Otomaties\Omnicasa\Command\Contracts;

interface CommandContract
{
    public function handle(array $args, array $assocArgs): void;
}
