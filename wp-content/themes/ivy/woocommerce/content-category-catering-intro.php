<?php
/**
 * Extra Intro fuer die Produktkategorie "Catering".
 *
 * @package Ivy
 */

defined( 'ABSPATH' ) || exit;
?>

<section class="mb-8 rounded-lg bg-gray-50 p-6">
    <h1 class="text-3xl font-bold mb-8">Für jeden Anlass das Richtige!</h1>
    <p class="text-base text-gray-700">
        Ob Firmenevent, Hochzeit, 40. Geburtstag oder Weihnachtsfeier – wir übernehmen euer leibliches Wohl.
        Stellt euch hier euer gewünschtes Angebot zusammen. Ob Firmenevent, Hochzeit, 40. Geburtstag oder Weihnachtsfeier – wir übernehmen euer leibliches Wohl. Stellt euch hier euer gewünschtes Angebot zusammen.
    </p>
</section>

<?php

$current_term = get_queried_object();


// Unterkategorien anzeigen, wenn vorhanden
if (is_product_category()) {
    $term = $current_term;
    $term_id = $term->term_id;
    $taxonomy = 'product_cat';
    $child_terms = get_terms([
            'taxonomy' => $taxonomy,
            'parent' => $term_id,
            'hide_empty' => false
    ]);


    if (!empty($child_terms) && !is_wp_error($child_terms)) {
        echo '<div class="mb-8 flex flex-wrap gap-4">';
        foreach ($child_terms as $child) {
            $link = get_term_link($child);
            echo '<a href="' . esc_url($link) . '" class="inline-block px-4 py-2 bg-primary text-white rounded hover:bg-primary/80">' . esc_html($child->name) . '</a>';
        }
        echo '</div>';
    }

    if (!empty($child_terms) && !is_wp_error($child_terms)) {
        echo '<div class="mb-8 flex flex-wrap gap-4">';
        foreach ($child_terms as $child) {
            $link = get_term_link($child);
            $thumbnail_id = get_term_meta($child->term_id, 'thumbnail_id', true);
            $image_html = $thumbnail_id
                    ? wp_get_attachment_image($thumbnail_id, 'woocommerce_thumbnail', false, ['class' => 'h-14 w-14 rounded object-cover'])
                    : wc_placeholder_img('woocommerce_thumbnail', ['class' => 'h-14 w-14 rounded object-cover']);
            echo '<a href="' . esc_url($link) . '" class="inline-flex items-center gap-3 px-4 py-2 bg-primary text-white rounded hover:bg-primary/80">' . $image_html . '<span>' . esc_html($child->name) . '</span></a>';
        }
        echo '</div>';
    }
}




?>

