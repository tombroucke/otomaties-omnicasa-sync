<?php

namespace Otomaties\Omnicasa\Modules\Abstracts;

use Omnicasa\Omnicasa;
use Otomaties\Omnicasa\Helpers\Assets;
use Otomaties\Omnicasa\Helpers\Loader;
use Otomaties\Omnicasa\Helpers\View;

abstract class Module
{
    public function __construct(
        protected Loader $loader,
        protected View $view,
        protected Assets $assets,
        protected Omnicasa $client,
    ) {}

    abstract public function init();
}
