<?php

namespace Otomaties\Omnicasa\Taxonomies\Contracts;

use ExtCPTs\Taxonomy as ExtCPTsTaxonomy;

interface Taxonomy
{
    public function register() : ExtCPTsTaxonomy;
}
