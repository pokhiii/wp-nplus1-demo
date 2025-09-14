<?php
/**
 * Plugin Name: Store Overrides
 * Description: Overrides default Woo+Storefront for our demo.
 * Version: 1.0.0
 * Author: ColoredCow
 */

add_filter( 'loop_shop_per_page', function() {
    return -1; // show all products on shop page
}, 20 );
