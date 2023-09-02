<?php


namespace CoinInfoPlugin\api;


class UpdatePrices
{
    public static function fetch_coin_data_from_api()
    {
        $api_url = 'https://api.coingecko.com/api/v3/coins/markets?vs_currency=usd&order=market_cap_desc&per_page=10&page=1';

        // Perform API request
        $response = wp_safe_remote_get($api_url);

        // Check for errors
        if (is_wp_error($response)) {
            return array(); // Return empty array on error
        }

        // Parse the API response
        $data = wp_remote_retrieve_body($response);
        $coin_data = json_decode($data, true);

        return $coin_data;
    }
}