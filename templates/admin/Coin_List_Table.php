<?php

namespace CoinInfoPlugin\templates\admin;
// coin-list-table.php


class Coin_List_Table extends \WP_List_Table
{
    public function __construct()
    {
        parent::__construct(array(
            'singular' => 'coin',
            'plural' => 'coins',
            'ajax' => false,
        ));
    }

    public function get_columns()
    {
        return array(
            'cb' => '<input type="checkbox" />',
            'title' => 'Title',
            'symbol' => 'Symbol',
            'price' => 'Price',
            'market_cap' => 'Market Cap',
            'last_update' => 'Last Update',
        );
    }

    public function prepare_items()
    {
        global $wpdb;
        $per_page = 10;
        $current_page = $this->get_pagenum();

        $sortable_columns = $this->get_sortable_columns();
        $default_sort_column = 'id'; // Default sort column
        $default_sort_order = 'asc'; // Default sort order

        if (isset($_GET['orderby']) && in_array($_GET['orderby'], array_keys($sortable_columns))) {
            $orderby = $_GET['orderby'];
        } else {
            $orderby = $default_sort_column;
        }

        if (isset($_GET['order']) && in_array($_GET['order'], array('asc', 'desc'))) {
            $order = $_GET['order'];
        } else {
            $order = $default_sort_order;
        }

        $query_args = array(
            'post_type' => 'coin',  // Custom post type
            'posts_per_page' => $per_page,
            'paged' => $current_page,
            'orderby' => $orderby,
            'order' => $order,
        );

        $query = new \WP_Query($query_args);
        $this->items = $query->posts;

        $this->_column_headers = array($this->get_columns(), array(), $sortable_columns);

        $total_items = $query->found_posts;
        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
        ));
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'title':
                return $item->post_title;
            case 'symbol':
            case 'price':
            case 'market_cap':
            case 'last_update':
                return get_post_meta($item->ID, $column_name, true);
            default:
                return '';
        }
    }
}
