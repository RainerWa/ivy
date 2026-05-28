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
    <?php
    $current_term = get_queried_object();
    if ( is_product_category() && $current_term instanceof WP_Term ) {
        $is_catering_category = isset($current_term->slug) && 'catering' === strtolower($current_term->slug);
        $is_child_of_catering = false;

        if ( ! $is_catering_category && ! empty($current_term->parent) ) {
            $parent_term = get_term((int) $current_term->parent, 'product_cat');
            if ( $parent_term instanceof WP_Term && isset($parent_term->slug) ) {
                $is_child_of_catering = 'catering' === strtolower($parent_term->slug);
            }
        }

        if ( $is_catering_category ) {
            wc_get_template('content-category-catering-intro.php');
        } elseif ( $is_child_of_catering ) {
            wc_get_template('content-category-catering-child-intro.php');
        }
    }
    ?>
</main>

<?php get_footer(); ?>
