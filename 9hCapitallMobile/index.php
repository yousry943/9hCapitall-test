<?php

/*
Plugin Name: 9hCapitallMobile API WooCommerce
Description: This plugin gets all products from WooCommerce and returns the data in JSON.
Author: 9hCapitall
Version: 1.0.0
*/

function get_all_products_as_json() {
    $args = array(
        'post_type' => 'product', // Assuming WooCommerce is used for products.
        'posts_per_page' => -1,
    );

    $products = new WP_Query($args);

    $product_data = array();
    while ($products->have_posts()) {
        $products->the_post();

        $product_data[] = array(
            'ID' => get_the_ID(),
            'Title' => get_the_title(),
            // Add more product data as needed.
        );
    }

    wp_reset_postdata();

    // Return product data as JSON.
    wp_send_json($product_data);
}

add_action('rest_api_init', function () {
    register_rest_route('9hCapitallMobile/v1', '/products', array(
        'methods' => 'GET',
        'callback' => 'get_all_products_as_json',
    ));
});


