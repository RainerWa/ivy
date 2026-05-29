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

// Letzten Menueeintrag mit Warenkorb-Zaehler im Primary-Menue ausgeben.
add_filter( 'wp_nav_menu_items', function( $items, $args ) {
	$menu_id        = isset( $args->menu_id ) ? (string) $args->menu_id : '';
	$theme_location = isset( $args->theme_location ) ? (string) $args->theme_location : '';
	$menu_class     = isset( $args->menu_class ) ? (string) $args->menu_class : '';

	$looks_like_primary = 'menu-primary' === $menu_id || 'primary' === $theme_location || false !== strpos( $menu_class, 'menu' );

	if ( ! $looks_like_primary ) {
		return $items;
	}

	$cart_url   = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#';
	$cart_count = 0;

	if ( function_exists( 'WC' ) && WC()->cart ) {
		$cart_count = (int) WC()->cart->get_cart_contents_count();
	}
	$label_html = '<span class="ivy-cart-link-inner" aria-hidden="true"><span class="ivy-cart-icon"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="20" r="1"></circle><circle cx="17" cy="20" r="1"></circle><path d="M3 4h2l2.2 10.2a2 2 0 0 0 2 1.6h7.6a2 2 0 0 0 2-1.6L21 7H7"></path></svg></span><span class="ivy-cart-count-badge"><span class="ivy-cart-count">' . $cart_count . '</span></span></span><span class="screen-reader-text">Warenkorb</span>';

	if ( false !== strpos( $items, '<li' ) ) {
		$items .= '<li class="menu-item menu-item-type-custom ivy-menu-cart-item"><a href="' . esc_url( $cart_url ) . '">' . $label_html . '</a></li>';
	} else {
		$items .= '<a href="' . esc_url( $cart_url ) . '" class="ivy-menu-cart-item">' . $label_html . '</a>';
	}

	return $items;
}, 20, 2 );

// Warenkorb-Zaehler im Menue nach AJAX Add-to-Cart aktualisieren.
add_filter( 'woocommerce_add_to_cart_fragments', function( $fragments ) {
	if ( ! function_exists( 'WC' ) || ! WC()->cart ) {
		return $fragments;
	}

	$fragments['#menu-primary .ivy-cart-count'] = '<span class="ivy-cart-count">' . (int) WC()->cart->get_cart_contents_count() . '</span>';

	return $fragments;
} );

// Badge-Styling fuer den Warenkorb-Menueeintrag.
add_action( 'wp_enqueue_scripts', function() {
	$css = '.ivy-menu-cart-item > a{position:relative;display:inline-flex;align-items:center;justify-content:center;min-width:2.25rem;min-height:2.25rem}.ivy-cart-link-inner{position:relative;display:inline-flex;line-height:1}.ivy-cart-icon{display:inline-flex;color:#1f3d31}.ivy-cart-count-badge{position:absolute;top:-8px;right:-10px;display:inline-flex;align-items:center;justify-content:center;min-width:1.5rem;height:1.5rem;padding:0 0.3rem;border-radius:9999px;background:#f07467;color:#fff;font-size:.8rem;font-weight:700;line-height:1;box-shadow:0 0 0 2px #fff}';
	wp_add_inline_style( 'ivy-global', $css );
}, 21000 );

// Badge auch bei Mengenanpassungen im Warenkorb aktualisieren.
add_action( 'wp_footer', function() {
	?>
	<script>
	(function() {
		function applyFragments(data) {
			if (!data || !data.fragments) {
				return;
			}
			var html = data.fragments['#menu-primary .ivy-cart-count'];
			if (typeof html === 'string') {
				document.querySelectorAll('#menu-primary .ivy-cart-count').forEach(function(el) {
					el.outerHTML = html;
				});
			}
		}

		function refreshCartBadge() {
			var url = (window.wc_cart_fragments_params && window.wc_cart_fragments_params.wc_ajax_url)
				? window.wc_cart_fragments_params.wc_ajax_url.replace('%%endpoint%%', 'get_refreshed_fragments')
				: '/?wc-ajax=get_refreshed_fragments';

			fetch(url, { credentials: 'same-origin' })
				.then(function(response) { return response.json(); })
				.then(applyFragments)
				.catch(function() {});
		}

		document.addEventListener('click', function(event) {
			if (event.target.closest('.wc-block-components-quantity-selector__button')) {
				setTimeout(refreshCartBadge, 300);
				setTimeout(refreshCartBadge, 900);
			}
		});

		if (window.jQuery) {
			window.jQuery(document.body).on('updated_wc_div updated_cart_totals added_to_cart removed_from_cart', function() {
				refreshCartBadge();
			});
		}
	})();
	</script>
	<?php
}, 999 );

