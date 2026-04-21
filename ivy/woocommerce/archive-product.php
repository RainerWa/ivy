<?php
/**
 * Template für Produkt-Kategorie-Archivseiten (WooCommerce)
 *
 * Überschreibt die Standardausgabe für Produktkategorien.
 *
 * @package Ivy
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>

<main class="max-w-content mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold mb-8"><?php woocommerce_page_title(); ?></h1>
    <?php
    // Unterkategorien anzeigen, wenn vorhanden
    if (is_product_category()) {
        $term = get_queried_object();
        $term_id = $term->term_id;
        $taxonomy = 'product_cat';
        $child_terms = get_terms([
            'taxonomy' => $taxonomy,
            'parent' => $term_id,
            'hide_empty' => true,
        ]);
        if (!empty($child_terms) && !is_wp_error($child_terms)) {
            echo '<div class="mb-8 flex flex-wrap gap-4">';
            foreach ($child_terms as $child) {
                $link = get_term_link($child);
                echo '<a href="' . esc_url($link) . '" class="inline-block px-4 py-2 bg-primary text-white rounded hover:bg-primary/80">' . esc_html($child->name) . '</a>';
            }
            echo '</div>';
        }
    }





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
</main>

<?php get_footer(); ?>
