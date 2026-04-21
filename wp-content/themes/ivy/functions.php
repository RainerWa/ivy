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

/**
 * Produktbeschreibung zwischen Preis und Bestellbutton anzeigen
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 15 );


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );


/**
 * Produktbeschreibung (the_content) zwischen Preis und Bestellbutton anzeigen
 */
add_action('woocommerce_single_product_summary', function() {
    global $post;
    echo '<div class="product-long-description" style="margin-bottom:1.5em">';
    echo apply_filters('the_content', get_post_field('post_content', $post->ID));

    // Min/Max Menge anzeigen
    $min = get_post_meta($post->ID, '_wb_min_qty', true);
    $max = get_post_meta($post->ID, '_wb_max_qty', true);
    $min = (int) $min;
    $max = (int) $max;
    if ($min < 1) $min = 1;
    if ($max < 1) $max = 0;
    echo '<div class="wb-min-max-info" style="font-size:0.95em; color:#666; margin-top:0.5em;">';
    if ($max > 0) {
	    echo '<div>';
        printf(__('Mindestmenge: %d', 'your-textdomain'), $min);
		echo '</div>';
	    echo '<div>';
        printf(__('Maximalmenge: %d', 'your-textdomain'), $max);
		echo '</div>';
    } else {
	    echo '<div>';
	    printf(__('Mindestmenge: %d', 'your-textdomain'), $min);
	    echo '</div>';
    }
    echo '</div>';

    echo '</div>';
}, 25);

/**
 * Beschreibungstab entfernen
 */
add_filter('woocommerce_product_tabs', function($tabs) {
    unset($tabs['description']);
    return $tabs;
}, 98);
