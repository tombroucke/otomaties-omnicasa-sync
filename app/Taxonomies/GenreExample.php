<?php

namespace Otomaties\Omnicasa\Taxonomies;

use ExtCPTs\Taxonomy as ExtCPTsTaxonomy;
use Otomaties\Omnicasa\Exceptions\ExtendedCptsNotInstalledException;
use Otomaties\Omnicasa\Helpers\Labels;
use Otomaties\Omnicasa\Taxonomies\Contracts\Taxonomy;

class GenreExample implements Taxonomy
{
    public const TAXONOMY = 'genre';

    public const POST_TYPE = 'book';

    public function register(): ExtCPTsTaxonomy
    {
        if (! function_exists('register_extended_taxonomy')) {
            throw new ExtendedCptsNotInstalledException();
        }

        $taxonomySingularName = __('Genre', 'otomaties-omnicasa-sync');
        $taxonomyPluralName = __('Genres', 'otomaties-omnicasa-sync');

        $args = [
            'meta_box' => 'radio', // can be null, 'simple', 'radio', 'dropdown'
            'exclusive' => false, // true means: just one can be selected; only for simple
            'labels' => Labels::taxonomy($taxonomySingularName, $taxonomyPluralName),
            'admin_cols' => [
                'updated' => [
                    'title_cb' => function () {
                        return '<em>Last</em> Updated';
                    },
                    'meta_key' => 'updated_date',
                    'date_format' => 'd/m/Y',
                ],
            ],
        ];

        $names = [
            'plural' => $taxonomyPluralName,
        ];

        return register_extended_taxonomy(self::TAXONOMY, self::POST_TYPE, $args, $names);
    }
}
