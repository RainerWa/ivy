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

// Unterkategorien anzeigen, wenn vorhanden.
if ( is_product_category() && $current_term instanceof WP_Term ) {
    ivy_render_product_category_links( $current_term->term_id, false );
}

if ( is_product_category() && $current_term instanceof WP_Term ) {
    ivy_render_product_category_links( $current_term->term_id );
}




?>

