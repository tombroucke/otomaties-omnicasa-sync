<?php

namespace Otomaties\Omnicasa\Database;

class Substatus extends Abstracts\Table
{
    const PRIMARY_KEY = 'id';
    
    const TABLE_NAME = 'omnicasa_property_substatuses';

    public static function create() : void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . self::TABLE_NAME;
        
        $sql = "CREATE TABLE $tableName (
                id mediumint(9) NOT NULL,
                name varchar(255) NULL,
                short_name varchar(255) NULL,
                marquee varchar(255) NULL,
                show_on_map boolean DEFAULT false,
                UNIQUE KEY id (id)
                );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
