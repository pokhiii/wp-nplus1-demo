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
    global $product, $np_macros_cache;

    $product_id = $product->get_id();

    if ( ! isset( $np_macros_cache[ $product_id ] ) ) {
        return;
    }

    $macros = $np_macros_cache[ $product_id ];

    echo '<div class="np-macros">';
    echo 'Protein: ' . esc_html( $macros['protein'] ) . ' | ';
    echo 'Carbs: ' . esc_html( $macros['carb'] ) . ' | ';
    echo 'Fat: ' . esc_html( $macros['fat'] ) . ' | ';
    echo 'Calories: ' . esc_html( $macros['calories'] );
    echo '</div>';
}


// Prefetch all macros for products in loop
add_action( 'woocommerce_before_shop_loop', 'np_prefetch_macros' );
function np_prefetch_macros() {
    global $wpdb, $np_macros_cache;

    $np_macros_cache = [];

    // Get product IDs from main query
    global $wp_query;
    $product_ids = wp_list_pluck( $wp_query->posts, 'ID' );

    if ( empty( $product_ids ) ) {
        return;
    }

    $table_name = $wpdb->prefix . 'product_macros';

    // Batch query
    // 🔑 Indexing note:
    // - Always ensure columns used in WHERE/JOIN have indexes.
    // - Here: product_id should be indexed (because of WHERE).
    // - Rule of thumb: if you filter/join/order by a column often → index it.
    $results = $wpdb->get_results(
        "SELECT product_id, protein, carb, fat, calories
         FROM $table_name
         WHERE product_id IN (" . implode( ',', array_map( 'absint', $product_ids ) ) . ")"
    );

    // Organize results by product
    foreach ( $results as $row ) {
        $np_macros_cache[ $row->product_id ] = [
            'protein'  => $row->protein,
            'carb'     => $row->carb,
            'fat'      => $row->fat,
            'calories' => $row->calories,
        ];
    }
}

