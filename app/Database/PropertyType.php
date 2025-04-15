<?php

namespace Otomaties\Omnicasa\Database;

class PropertyType extends Abstracts\Table
{
    public const PRIMARY_KEY = 'id';

    public const TABLE_NAME = 'omnicasa_property_types';

    public static function create(): void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . self::TABLE_NAME;

        $sql = "CREATE TABLE $tableName (
                id mediumint(9) NOT NULL,
                name varchar(255) NULL,
                web_id mediumint(9) NULL,
                abbr varchar(255) NULL,
                original_abbr varchar(255) NULL,
                parent_id mediumint(9) NULL,
                UNIQUE KEY id (id)
                );";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
