<?php

namespace CoinInfoPlugin\migration;

class Schema
{
    public function CreateCoinTable()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'coin_info';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id VARCHAR(50) NOT NULL,
        symbol VARCHAR(10) NOT NULL,
        name VARCHAR(100) NOT NULL,
        price FLOAT NOT NULL,
        market_cap FLOAT NOT NULL,
        last_update DATETIME NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
