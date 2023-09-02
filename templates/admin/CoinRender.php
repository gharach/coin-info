<?php


namespace CoinInfoPlugin\templates\admin;

class CoinRender
{
    public function render_coin_list_page()
    {

        $coin_list_table = new Coin_List_Table();
        $coin_list_table->prepare_items();
        ?>
        <div class="wrap">
            <h2>Coin List</h2>
            <button id="fetch-data-btn" class="button">Fetch New Data</button>
            <?php $coin_list_table->display(); ?>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                $('#fetch-data-btn').on('click', function () {
                    // Send AJAX request
                    $.ajax({
                        type: 'POST',
                        url: ajaxurl, // WordPress AJAX endpoint
                        data: {
                            action: 'fetch_new_data', // AJAX action name
                            security: '<?php echo wp_create_nonce("fetch-data-nonce"); ?>', // Nonce
                        },
                        success: function (response) {
                            if (response.success) {
                                // Reload the table after fetching data
                                location.reload();
                            } else {
                                alert('Failed to fetch new data.');
                            }
                        }
                    });
                });
            });
        </script>
        <?php
    }
}