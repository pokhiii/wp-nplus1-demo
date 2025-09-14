<?php
/**
 * Plugin Name: N+1 Issue
 * Description: Demo plugin to replicate N+1 queries problem with WooCommerce products.
 * Version: 1.0.0
 * Author: ColoredCow
 */

// 1. Create custom table on plugin activation
register_activation_hook( __FILE__, 'np_create_macros_table' );
function np_create_macros_table() {
    global $wpdb;

    $table_name      = $wpdb->prefix . 'product_macros';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        product_id BIGINT(20) UNSIGNED NOT NULL,
        protein VARCHAR(10) NOT NULL,
        carb VARCHAR(10) NOT NULL,
        fat VARCHAR(10) NOT NULL,
        calories VARCHAR(10) NOT NULL,
        PRIMARY KEY (id),
        KEY product_id (product_id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );

    // Seed data dynamically (only if table empty)
    $count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name" );
    if ( $count == 0 ) {
        // Get all published products
        $products = wc_get_products( [
            'status' => 'publish',
            'limit'  => -1,
        ] );

        foreach ( $products as $product ) {
            $wpdb->insert( $table_name, [
                'product_id' => $product->get_id(),
                'protein'    => rand(20, 50) . 'g',
                'carb'       => rand(20, 60) . 'g',
                'fat'        => rand(5, 30) . 'g',
                'calories'   => rand(300, 600),
            ] );
        }
    }
}

// 2. Helper function to fetch a single macro (simulates N+1)
function np_get_macro( $product_id, $fact ) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'product_macros';
    return $wpdb->get_var(
        $wpdb->prepare(
            "SELECT $fact FROM $table_name WHERE product_id = %d",
            $product_id
        )
    );
}

// 3. Hook into shop loop to display macros (N+1 simulation)
add_action( 'woocommerce_after_shop_loop_item_title', 'np_after_shop_loop_item_title' );
function np_after_shop_loop_item_title() {
    global $product;

    $product_id = $product->get_id();

    // Four separate queries per product
    $protein  = np_get_macro( $product_id, 'protein' );
    $carb     = np_get_macro( $product_id, 'carb' );
    $fat      = np_get_macro( $product_id, 'fat' );
    $calories = np_get_macro( $product_id, 'calories' );

    echo '<div class="np-macros">';
    echo 'Protein: ' . esc_html( $protein ) . ' | ';
    echo 'Carbs: ' . esc_html( $carb ) . ' | ';
    echo 'Fat: ' . esc_html( $fat ) . ' | ';
    echo 'Calories: ' . esc_html( $calories );
    echo '</div>';
}
