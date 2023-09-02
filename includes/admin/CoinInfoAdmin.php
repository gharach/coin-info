<?php

namespace CoinInfoPlugin\includes\admin;

use CoinInfoPlugin\api\UpdatePrices;
use CoinInfoPlugin\models\Coin;

class CoinInfoAdmin
{
    public function fetch_new_data_ajax()
    {
        check_ajax_referer('fetch-data-nonce', 'security');

        // Fetch new data from the API and update the database
        $new_data = UpdatePrices::fetch_coin_data_from_api();
        if (!empty($new_data)) {
            $coin = new Coin();
            $coin->update_coin_data($new_data); // Add your function to update data here
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

}
