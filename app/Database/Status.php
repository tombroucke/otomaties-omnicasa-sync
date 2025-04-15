<?php

namespace Otomaties\Omnicasa\Database;

class Status extends Abstracts\Table
{
    public const PRIMARY_KEY = 'id';

    public const TABLE_NAME = 'omnicasa_property_statuses';

    public static function create(): void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . self::TABLE_NAME;

        $sql = "CREATE TABLE $tableName (
                id mediumint(9) NOT NULL,
                name varchar(255) NULL,
                UNIQUE KEY id (id)
                );";

        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
