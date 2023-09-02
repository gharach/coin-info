<?php
/*
Plugin Name: Coin Info Plugin
Description: This plugin creates a custom post type "coin" and displays coin information using a custom WP_List_Table.
Version: 1.0
Author: Ali Gharachorloo
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once(plugin_dir_path(__FILE__) . 'vendor/autoload.php'); // Include composer autoloader

use CoinInfoPlugin\bootstrap\Init;

$bootstrap = new Init();


// Register activation hook
register_activation_hook(__FILE__, array($bootstrap, 'activate'));

?>
