<?php

namespace Otomaties\Omnicasa\Helpers;

class Assets
{
    public function __construct(
        private string $path,
    ) {
        //
    }

    public function entrypoints(): mixed
    {
        $path = fn ($endpoint) => implode('/', [$this->path, $endpoint]);
        $read = fn ($endpoint) => file_get_contents($path($endpoint));

        return json_decode($read('entrypoints.json'));
    }

    /**
     * Get the value of path
     *
     * @param  string  $endpoint  E.g. checkout.scripts
     */
    public function url($endpoint): string
    {
        return implode('/', [plugin_dir_url(dirname(__FILE__, 2)), 'public', $endpoint]);
    }
}
