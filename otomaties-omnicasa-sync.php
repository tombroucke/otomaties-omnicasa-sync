<?php

use Otomaties\Omnicasa\Plugin;
use Otomaties\Omnicasa\Helpers\Config;
use Otomaties\Omnicasa\Helpers\Loader;

/**
 * Plugin Name:       Otomaties Omnicasa Sync
 * Description:       Sync Omnicasa properties with WordPress
 * Version:           1.0.2
 * Author:            Tom Broucke
 * Author URI:        https://tombroucke.be/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       otomaties-omnicasa-sync
 * Domain Path:       /resources/languages
 */

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/**
 * Get main plugin class instance
 *
 * @return Plugin
 */
function omnicasa()
{
    static $plugin;

    if (!$plugin) {
        $plugin = new Plugin(
            new Loader(),
            new Config()
        );

        do_action('omnicasa', $plugin);

        $plugin
            ->initialize()
            ->runLoader();
    }

    return $plugin;
}

omnicasa();
