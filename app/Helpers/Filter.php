<?php

namespace Otomaties\Omnicasa\Helpers;

class Filter
{
    public function available($metaKey, ?int $goal = null, $postType = 'property')
    {
        global $wpdb;
        $query = "SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = '%s' AND post_id IN (SELECT ID FROM $wpdb->posts WHERE post_type = '%s'";

        if ($goal !== null) {
            if ($goal === 0) {
                $query .= " AND (ID IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'goal' AND (meta_value = '0' OR meta_value IS NULL)) OR ID NOT IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'goal'))";
            } else {
                $query .= " AND ID IN (SELECT post_id FROM $wpdb->postmeta WHERE meta_key = 'goal' AND meta_value = '$goal')";
            }
        }

        $query .= ')';

        $results = $wpdb->get_results($wpdb->prepare($query, $metaKey, $postType), ARRAY_A);

        return array_column($results, 'meta_value');
    }
}
