<?php
/**
 * Extra Intro fuer Unterkategorien von "Catering".
 *
 * @package Ivy
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="mb-8 rounded-lg bg-gray-50 p-6">
    <h1 class="text-3xl font-bold mb-8">Für jeden Anlass das Richtige!</h1>
</section>

<?php

$current_term = get_queried_object();


// Parent-Kategorie des aktuellen Terms laden (falls vorhanden).
$parent_term = null;
if ( $current_term instanceof WP_Term && ! empty( $current_term->parent ) ) {
    $parent_term = get_term( (int) $current_term->parent, 'product_cat' );
    if ( is_wp_error( $parent_term ) ) {
        $parent_term = null;
    }
}


// Unterkategorien anzeigen, wenn vorhanden
if ( is_product_category() && $parent_term instanceof WP_Term ) {
    ivy_render_product_category_links( $parent_term->term_id, false, $current_term->term_id );
}
?>

<?php

if ( woocommerce_product_loop() ) {


	woocommerce_product_loop_start();

	if ( wc_get_loop_prop( 'total' ) ) {
		while ( have_posts() ) {
			the_post();
			wc_get_template_part( 'content', 'product' );
		}
	}

	woocommerce_product_loop_end();

} else {
	/**
	 * Hook: woocommerce_no_products_found.
	 *
	 * @hooked wc_no_products_found - 10
	 */
	do_action( 'woocommerce_no_products_found' );
}

?>

