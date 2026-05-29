<?php
/**
 * Ivy Hello Child functions.
 *
 * @package IvyHelloChild
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Parent-Theme und eigene Styles laden.
add_action( 'wp_enqueue_scripts', function() {
	$parent = wp_get_theme( get_template() );

	wp_enqueue_style(
		'hello-elementor',
		get_template_directory_uri() . '/style.css',
		array(),
		$parent ? $parent->get( 'Version' ) : null
	);

	wp_enqueue_style(
		'ivy-style',
		get_stylesheet_directory_uri() . '/dist/style.css',
		array( 'hello-elementor' ),
		wp_get_theme()->get( 'Version' )
	);

	wp_enqueue_style(
		'ivy-global',
		get_stylesheet_directory_uri() . '/dist/global.css',
		array( 'ivy-style' ),
		wp_get_theme()->get( 'Version' )
	);
}, 20000 );

// Eigene Theme-Module laden.
require_once get_stylesheet_directory() . '/inc/product_categories.php';
require_once get_stylesheet_directory() . '/inc/product_detail.php';
require_once get_stylesheet_directory() . '/inc/woocommerce_min_max.php';
require_once get_stylesheet_directory() . '/inc/checkout.php';

// Optionales Frontend-Script.
add_action( 'wp_enqueue_scripts', function() {
	wp_enqueue_script(
		'ivy-quantity-selector',
		get_stylesheet_directory_uri() . '/quantity-selector.js',
		array(),
		wp_get_theme()->get( 'Version' ),
		true
	);
}, 20100 );

