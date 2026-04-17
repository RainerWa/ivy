<?php
/**
 * Ivy functions and definitions
 *
 * @package Ivy
 */

// Enqueue parent and child theme styles
function ivy_enqueue_styles() {
    wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'ivy-style', get_stylesheet_directory_uri() . '/style.css', array('storefront-style'), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'ivy_enqueue_styles' );

