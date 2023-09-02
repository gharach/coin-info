<?php


namespace CoinInfoPlugin\models;


class Coin
{

    public function __construct()
    {
        add_action('save_post_coin', 'save_coin_metabox_data');
    }

    public function save_coin_metabox_data($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

        if (isset($_POST['symbol'])) {
            update_post_meta($post_id, 'symbol', sanitize_text_field($_POST['symbol']));
        }
        if (isset($_POST['price'])) {
            update_post_meta($post_id, 'price', sanitize_text_field($_POST['price']));
        }
        if (isset($_POST['market_cap'])) {
            update_post_meta($post_id, 'market_cap', sanitize_text_field($_POST['market_cap']));
        }
        if (isset($_POST['last_update'])) {
            update_post_meta($post_id, 'last_update', sanitize_text_field($_POST['last_update']));
        }
    }

    public function update_coin_data($new_data)
    {
        global $wpdb;

        foreach ($new_data as $coin) {
            $wpdb->replace(
                $wpdb->prefix . 'coin_info',
                array(
                    'id' => $coin['id'],
                    'symbol' => $coin['symbol'],
                    'name' => $coin['name'],
                    'price' => $coin['current_price'],
                    'market_cap' => $coin['market_cap'],
                    'last_update' => $coin['last_updated']
                ),
                array('%s', '%s', '%s', '%f', '%f', '%s')
            );

            // Update or create meta fields for each coin post
            $coin_query = new \WP_Query(array(
                'post_type' => 'coin',
                'posts_per_page' => 1,
                'post_status' => 'publish',
                'title' => $coin['name']
            ));

            if ($coin_query->have_posts()) {
                $coin_post_id = $coin_query->posts[0]->ID;
            } else {
                $coin_post_id = wp_insert_post(array(
                    'post_type' => 'coin',
                    'post_title' => $coin['name'],
                    'post_status' => 'publish',
                ));
            }

            update_post_meta($coin_post_id, 'symbol', $coin['symbol']);
            update_post_meta($coin_post_id, 'price', $coin['current_price']);
            update_post_meta($coin_post_id, 'market_cap', $coin['market_cap']);
            update_post_meta($coin_post_id, 'last_update', $coin['last_updated']);
        }
    }
}