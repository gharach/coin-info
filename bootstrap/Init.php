<?php

namespace CoinInfoPlugin\bootstrap;

use CoinInfoPlugin\api\UpdatePrices;
use CoinInfoPlugin\migration\Schema;
use CoinInfoPlugin\includes\admin\CoinInfoAdmin;
use CoinInfoPlugin\templates\admin\CoinRender;
use templates\admin\Coin_render;

class Init
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_coin_menu_item'));
        $CoinInfoAdmin = new CoinInfoAdmin();
        add_action('wp_ajax_fetch_new_data', array($CoinInfoAdmin, 'fetch_new_data_ajax'));
        add_action('rwmb_meta_boxes', array($this, 'add_coin_metaboxes'));
        $cron = new \CoinInfoPlugin\cron\UpdatePrices();
        $cron->exec();
    }

    function add_coin_menu_item()
    {
        $template = new CoinRender();
        add_menu_page('Coin List', 'Coin List', 'manage_options', 'coin-list', array($template, 'render_coin_list_page'));
        // Generate nonce
        wp_nonce_field('fetch-data-nonce', 'security');
    }

    function add_coin_metaboxes()
    {
        $meta_boxes = array(
            array(
                'id' => 'coin_info',
                'title' => 'Coin Information',
                'post_types' => 'coin',
                'context' => 'normal',
                'priority' => 'high',
                'fields' => array(
                    array(
                        'name' => 'Symbol',
                        'id' => 'symbol',
                        'type' => 'text',
                    ),
                    array(
                        'name' => 'Price',
                        'id' => 'price',
                        'type' => 'number',
                    ),
                    array(
                        'name' => 'Market Cap',
                        'id' => 'market_cap',
                        'type' => 'number',
                    ),
                    array(
                        'name' => 'Last Update',
                        'id' => 'last_update',
                        'type' => 'datetime',
                    ),
                ),
            ),
        );

        return $meta_boxes;
    }


    public function activate()
    {
        $schema = new Schema();
        $schema->CreateCoinTable();

        $cron = new \CoinInfoPlugin\cron\UpdatePrices();
        $cron->exec();
    }

}
