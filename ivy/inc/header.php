<?php

function ivy_primary_navigation() {
	?>
	<div role="navigation">
		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'primary',
				'menu_class'     => '', // keine Klasse, da kein ul
				'container'      => false, // kein zusätzliches <div>
				'items_wrap'     => '%3$s', // entfernt ul komplett
				'walker'         => new Ivy_Nav_Walker(),
				'fallback_cb'    => false, // kein Fallback-HTML
			)
		);
		?>
	</div>
	<?php
}
add_action('storefront_header', 'ivy_primary_navigation', 50);

function ivy_header_cart() {
	if ( storefront_is_woocommerce_activated() ) {
		if ( is_cart() ) {
			$class = 'current-menu-item';
		} else {
			$class = '';
		}
		?>
		<div id="site-header-cart" class="site-header-cart menu">
			<div class="<?php echo esc_attr( $class ); ?>">
				<?php storefront_cart_link(); ?>
			</div>
			<div ">
			<?php the_widget( 'WC_Widget_Cart', 'title=' ); ?>
		</div>
		</div>
		<?php
	}
}
add_action('storefront_header', 'ivy_header_cart', 60);


class Ivy_Nav_Walker extends Walker_Nav_Menu {
	function start_lvl( &$output, $depth = 0, $args = array() ) {}
	function end_lvl( &$output, $depth = 0, $args = array() ) {}
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$classes = 'px-3 py-2 hover:bg-primary hover:text-white rounded';
		$output .= '<a href="' . esc_attr($item->url) . '" class="' . $classes . '">' . esc_html($item->title) . '</a>';
	}
	function end_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {}
}


if ( ! function_exists( 'storefront_primary_navigation_wrapper' ) ) {
	/**
	 * The primary navigation wrapper
	 */
	function storefront_primary_navigation_wrapper() {
		echo '<div class="storefront-primary-navigation"><div class="col-full"><div class="flex justify-between">LOGO<div class="flex justify-end">';
	}
}

if ( ! function_exists( 'storefront_primary_navigation_wrapper_close' ) ) {
	/**
	 * The primary navigation wrapper close
	 */
	function storefront_primary_navigation_wrapper_close() {
		echo '</div></div></div></div>';
	}
}


if ( ! function_exists( 'storefront_cart_link' ) ) {
	/**
	 * Cart Link
	 * Gibt nur die Anzahl der Artikel im Warenkorb als <span class="count">X items</span> aus
	 */
	function storefront_cart_link() {
		if ( ! storefront_woo_cart_available() ) {
			return;
		}
		$count = WC()->cart->get_cart_contents_count();
		echo '<span class="count">' . $count . '</span>';
	}
}

