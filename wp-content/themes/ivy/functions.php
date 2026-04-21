<?php
/**
 * Ivy functions and definitions
 *
 * @package Ivy
 */
require_once get_stylesheet_directory() . '/inc/header.php';
require_once get_stylesheet_directory() . '/inc/woocommerce_min_max.php';

// Tailwind CSS laden (build output, z.B. /dist/style.css)
function ivy_enqueue_styles() {
    // Tailwind zuerst laden
    wp_enqueue_style( 'ivy-style', get_stylesheet_directory_uri() . '/dist/style.css', array(), wp_get_theme()->get('Version') );
    // Eigene SCSS/CSS zuletzt laden, damit es alles überschreibt
    wp_enqueue_style( 'ivy-global', get_stylesheet_directory_uri() . '/dist/global.css', array('ivy-style'), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'ivy_enqueue_styles', 20000 );

// Storefront-Styles und dynamische Styles entfernen
add_action('wp_enqueue_scripts', function() {
    // Storefront-Stylesheets entfernen

  //  wp_dequeue_style('storefront-style');
  //  wp_deregister_style('storefront-style');
    wp_dequeue_style('storefront-fonts');
    wp_deregister_style('storefront-fonts');
  //  wp_dequeue_style('storefront-icons');
  //  wp_deregister_style('storefront-icons');
    // WooCommerce/Jetpack Styles entfernen (falls eingebunden)
  //  wp_dequeue_style('storefront-woocommerce-style');
  //  wp_deregister_style('storefront-woocommerce-style');
    wp_dequeue_style('storefront-jetpack-style');
    wp_deregister_style('storefront-jetpack-style');
    // Inline-Styles entfernen
  //  remove_action('wp_head', 'storefront_customizer_css');
  //  remove_action('wp_head', 'storefront_add_customizer_css');
  //  remove_action('wp_enqueue_scripts', 'storefront_add_customizer_css');

}, 20);

add_action( 'init', function() {
    remove_action( 'storefront_header', 'storefront_skip_links', 5 );
    remove_action( 'storefront_header', 'storefront_site_branding', 20 );
    remove_action( 'storefront_header', 'storefront_product_search', 40 );
    remove_action('storefront_header', 'storefront_primary_navigation', 50);
    remove_action('storefront_header', 'storefront_header_cart', 60);
    /*
    remove_action('storefront_header', 'storefront_header_container', 0);
    remove_action('storefront_header', 'storefront_header_container_close', 41);
    remove_action('storefront_header', 'storefront_primary_navigation_wrapper', 42);
    remove_action('storefront_header', 'storefront_primary_navigation_wrapper_close', 68);
    remove_action('storefront_before_header', 'storefront_header_container', 0);
    remove_action('storefront_before_header', 'storefront_header_container_close', 100);
    remove_action('storefront_before_content', 'storefront_header_container', 0);
    remove_action('storefront_before_content', 'storefront_header_container_close', 100);
    remove_action('storefront_after_header', 'storefront_header_container', 0);
    remove_action('storefront_after_header', 'storefront_header_container_close', 100);
    */
    // Entfernt die Sidebar aus allen Storefront-Hooks
    remove_action('storefront_sidebar', 'storefront_get_sidebar', 10);
    // Entfernt Sidebar-Widgets aus WooCommerce-Hooks
    remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    // Optional: Entfernt Sidebar-Regionen aus dem Customizer
    unregister_sidebar('sidebar-1');
});
function ivy_enqueue_quantity_selector_script() {
    wp_enqueue_script(
        'ivy-quantity-selector',
        get_stylesheet_directory_uri() . '/quantity-selector.js',
        array(),
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'ivy_enqueue_quantity_selector_script', 20100);
