<?php

namespace Otomaties\Omnicasa\PostTypes;

use ExtCPTs\PostType as ExtCPTsPostType;
use Otomaties\Omnicasa\Helpers\Labels;
use Otomaties\Omnicasa\PostTypes\Contracts\PostType;
use Otomaties\Omnicasa\Exceptions\ExtendedCptsNotInstalledException;

class Project implements PostType
{
    const POST_TYPE = 'project';

    public function register() : ExtCPTsPostType
    {
        if (!function_exists('register_extended_post_type')) {
            throw new ExtendedCptsNotInstalledException();
        }

        $postSingularName = __('Project', 'functionality-plugin');
        $postPluralName = __('Projects', 'functionality-plugin');

        $args = [
            'show_in_feed' => true,
            'show_in_rest' => true,
            'menu_icon' => 'dashicons-admin-multisite',
            'labels' => Labels::postType($postSingularName, $postPluralName),
            'dashboard_activity' => true,
            // 'admin_cols' => [
            //     'property_featured_image' => [
            //         'title'          => __('Illustration', 'functionality-plugin'),
            //         'featured_image' => 'thumbnail',
            //     ],
            //     'property_published' => [
            //         'title_icon'  => 'dashicons-calendar-alt',
            //         'meta_key'    => 'published_date',
            //         'date_format' => 'd/m/Y',
            //     ],
            // ],

        ];

        $names = [
            'singular' => $postSingularName,
            'plural'   => $postPluralName,
            'slug'     => self::POST_TYPE,
        ];

        return register_extended_post_type(self::POST_TYPE, $args, $names);
    }
}
