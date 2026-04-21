<?php

if (!defined('ABSPATH')) {
    exit;
}

add_action('after_setup_theme', function () {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ]);

    register_nav_menus([
        'primary' => __('Primary Menu', 'storefront-child-starter'),
        'footer'  => __('Footer Menu', 'storefront-child-starter'),
    ]);
});

add_action('wp_enqueue_scripts', function () {
    $version = file_exists(get_stylesheet_directory() . '/assets/css/app.min.css')
        ? filemtime(get_stylesheet_directory() . '/assets/css/app.min.css')
        : wp_get_theme()->get('Version');

    wp_enqueue_style(
        'storefront-child-starter-app',
        get_stylesheet_directory_uri() . '/assets/css/app.min.css',
        [],
        $version
    );

    wp_enqueue_script(
        'storefront-child-starter-app',
        get_stylesheet_directory_uri() . '/assets/js/app.js',
        [],
        file_exists(get_stylesheet_directory() . '/assets/js/app.js')
            ? filemtime(get_stylesheet_directory() . '/assets/js/app.js')
            : wp_get_theme()->get('Version'),
        true
    );
}, 20);

add_action('widgets_init', function () {
    unregister_sidebar('sidebar-1');
});

/**
 * Optionale Utility-Klasse für Body.
 */
add_filter('body_class', function ($classes) {
    $classes[] = 'restaurant-theme';
    return $classes;
});

/**
 * Beispiel: Shop-Buttontext anpassen.
 */
add_filter('woocommerce_product_add_to_cart_text', function ($text, $product) {
    if ($product && has_term('catering', 'product_cat', $product->get_id())) {
        return __('Menü wählen', 'storefront-child-starter');
    }

    return $text;
}, 10, 2);
