<?php


namespace CoinInfoPlugin\cron;


use CoinInfoPlugin\models\Coin;

class UpdatePrices
{
    public function exec()
    {
        add_filter('cron_schedules', array($this, 'add_custom_recurrence_interval'));
        register_activation_hook(__FILE__, array($this, 'schedule_coin_update_event'));
        add_action('update_coin_data_event', array($this, 'update_coin_data_cron'));

    }

    public function add_custom_recurrence_interval($schedules)
    {
        $schedules['every_10_minutes'] = array(
            'interval' => 600, // 10 minutes in seconds
            'display' => __('Every 10 Minutes')
        );
        return $schedules;
    }

    public function schedule_coin_update_event()
    {
//        if (!wp_next_scheduled(array($this, 'update_coin_data_event'))) {
//            wp_schedule_event(time(), 'every_10_minutes', array($this, 'update_coin_data_event'));
//        }
       $this->$this->update_coin_data_cron();
    }

    public function update_coin_data_cron()
    {
        $new_data = \CoinInfoPlugin\api\UpdatePrices::fetch_coin_data_from_api();
        if (!empty($new_data)) {
            $coin = new Coin();
            $coin->update_coin_data($new_data);// Add your function to update data here
        }
    }
}