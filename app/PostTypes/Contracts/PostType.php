<?php

namespace Otomaties\Omnicasa\PostTypes\Contracts;

use ExtCPTs\PostType as ExtCPTsPostType;

interface PostType
{
    public function register() : ExtCPTsPostType;
}
