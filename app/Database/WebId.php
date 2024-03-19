<?php

namespace Otomaties\Omnicasa\Database;

class WebId extends Abstracts\Table
{
    const PRIMARY_KEY = 'web_id';
    
    const TABLE_NAME = 'omnicasa_property_web_ids';

    public static function create() : void
    {
        global $wpdb;
        $tableName = $wpdb->prefix . self::TABLE_NAME;
        
        $sql = "CREATE TABLE $tableName (
                id mediumint(9) NOT NULL,
                web_id mediumint(9) NULL,
                name varchar(255) NULL,
                show_order mediumint(9) NULL,
                sale boolean DEFAULT false,
                rent boolean DEFAULT false,
                UNIQUE KEY web_id (web_id)
                );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
