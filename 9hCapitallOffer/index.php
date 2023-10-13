<?php
/*


Plugin Name: 9hCapitalOffer


Plugin URI: https://ahmed-yousry.com/


Description: Plugin to company  for  creating crud  and  custom post type


Version: 1.0


Author: ahmed yousry


Author URI: https://ahmed-yousry.com/


*/

/* 
Create “Item” CPT (Custom Post Type) uplude file , name , upload files, Each user is assigned to one storage space.  
Create REST routes to CRUD “Item”.

*/
function add_offer_product_to_cart( $cart_item_key, $product_id, $quantity, $variation_id, $cart_item_data, $args ) {
    // Check if the product being added to the cart is from the "Special Products" category.
    $product_categories = get_the_terms( $product_id, 'product_cat' );
  
    foreach ( $product_categories as $product_category ) {
        // If the product category is 'special-products', add the offer product to the cart based on the count of special products and the count of products in the cart.
        if ( $product_category->slug === 'special-products' ) {
            // Get the number of special products in the cart.
            $special_product_count = 0;
   
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $product_categories = get_the_terms( $cart_item['product_id'], 'product_cat' );
            
                foreach ( $product_categories as $product_category ) {
                    if ( $product_category->slug === 'special-products' ) {
                      $quantity = $cart_item['quantity'];
                       
                        $special_product_count=+ $quantity;
                    }
                }
            }
    
            // Get the number of products in the cart.
            $product_count = count( WC()->cart->get_cart() );
            for ( $i = 0; $i < $special_product_count; $i++ ) {
                // Add the offer product to the cart.
                $offer_product_id = 4438; // Replace with the ID of the "Offer" product.
                WC()->cart->add_to_cart( $offer_product_id, 1 );
            }
        }
    }
}

add_action( 'woocommerce_add_to_cart', 'add_offer_product_to_cart', 10, 6 );




function disable_offer_product_quantity_field($product_quantity, $cart_item_key, $cart_item) {
    // Replace '123' with the actual Product ID of your 'Offer' product.
    $offer_product_id = 4438;

    if ($cart_item['product_id'] == $offer_product_id) {
        return '<input type="text" size="4" class="input-text" value="' . esc_attr($cart_item['quantity']) . '" readonly>';
    }

    return $product_quantity;
}

add_filter('woocommerce_cart_item_quantity', 'disable_offer_product_quantity_field', 10, 3);










add_action( 'woocommerce_cart_item_removed', 'remove_offer_products_from_cart_when_all_special_products_are_removed' );

function remove_offer_products_from_cart_when_all_special_products_are_removed( $cart_item_key ) {
    // Get the ID of the offer product.
    $offer_product_id = 4438; // Replace with the ID of the "Offer" product.

    // Check if the cart item being removed is a special product.
    $product_categories = get_the_terms( $cart_item_key, 'product_cat' );
    foreach ( $product_categories as $product_category ) {
        if ( $product_category->slug === 'special-products' ) {
            // Check if there are any special products left in the cart.
            $special_product_count = 0;
            foreach ( WC()->cart->get_cart() as $cart_item ) {
                $product_categories = get_the_terms( $cart_item['product_id'], 'product_cat' );
                foreach ( $product_categories as $product_category ) {
                    if ( $product_category->slug === 'special-products' ) {
                        $special_product_count++;
                    }
                }
            }

            // If there are no special products left in the cart, remove all offer products from the cart.
            if ( $special_product_count === 0 ) {
                foreach ( WC()->cart->get_cart() as $cart_item ) {
                    if ( $cart_item['product_id'] === $offer_product_id ) {
                        WC()->cart->remove_cart_item( $cart_item['key'] );
                    }
                }
            }
        }
    }
}