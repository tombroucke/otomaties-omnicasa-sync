<?php

namespace Otomaties\Omnicasa\Modules;

use Otomaties\Omnicasa\Models\Book;
use Otomaties\Omnicasa\Modules\Abstracts\Module;

class Frontend extends Module
{
    public function init()
    {
        // $this->loader->addAction('wp_enqueue_scripts', $this, 'enqueueScripts');
    }

    // public function enqueueScripts()
    // {
    //     if (property_exists($this->assets->entrypoints()->app, 'js')) {
    //         foreach ($this->assets->entrypoints()->app->js as $js) {
    //             wp_enqueue_script('otomaties-omnicasa-sync-app-' . $js, $this->assets->url($js), [], null, true);
    //         }
    //     }
        
    //     if (property_exists($this->assets->entrypoints()->app, 'css')) {
    //         foreach ($this->assets->entrypoints()->app->css as $css) {
    //             wp_enqueue_style('otomaties-omnicasa-sync-app-' . $css, $this->assets->url($css), [], null);
    //         }
    //     }
    // }
}
