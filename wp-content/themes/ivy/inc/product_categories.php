<?php
/**
 * Helpers fuer Produktkategorien im Theme.
 *
 * @package Ivy
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! function_exists( 'ivy_get_child_product_categories' ) ) {
    /**
     * Liefert Kind-Kategorien einer Produktkategorie in WooCommerce-Sortierung.
     *
     * @param int  $parent_term_id Parent-Term-ID.
     * @param bool $hide_empty     Leere Kategorien ausblenden.
     * @return WP_Term[]
     */
    function ivy_get_child_product_categories( $parent_term_id, $hide_empty = false ) {
        $parent_term_id = absint( $parent_term_id );

        if ( ! $parent_term_id ) {
            return array();
        }

        $terms = get_terms(
            array(
                'taxonomy'   => 'product_cat',
                'parent'     => $parent_term_id,
                'hide_empty' => $hide_empty,
                'meta_key'   => 'order',
                'orderby'    => 'meta_value_num',
                'order'      => 'ASC',
            )
        );

        if ( is_wp_error( $terms ) || empty( $terms ) ) {
            return array();
        }

        return $terms;
    }
}

if ( ! function_exists( 'ivy_render_product_category_links' ) ) {
    /**
     * Rendert Buttons fuer Produkt-Kindkategorien.
     *
     * @param int  $parent_term_id Parent-Term-ID.
     * @param bool $show_images    Kategorie-Bilder anzeigen.
     * @return void
     */
    function ivy_render_product_category_links( $parent_term_id, $show_images = true, $current_id = 0 ) {
        $child_terms = ivy_get_child_product_categories( $parent_term_id, false );

        if ( empty( $child_terms ) ) {
            return;
        }

        echo '<div class="block md:flex justify-center">';

        foreach ( $child_terms as $child ) {
            $link = get_term_link( $child );
            if ( is_wp_error( $link ) ) {
                continue;
            }

            $name_html = '<span>' . esc_html( $child->name ) . '</span>';

            if ( $show_images ) {
                $thumbnail_id = get_term_meta( $child->term_id, 'thumbnail_id', true );
                $image_html   = $thumbnail_id
                    ? wp_get_attachment_image( $thumbnail_id, 'woocommerce_thumbnail', false, array( 'class' => 'h-14 w-14 rounded object-cover' ) )
                    : wc_placeholder_img( 'woocommerce_thumbnail', array( 'class' => 'h-14 w-14 rounded object-cover' ) );

                echo '<a href="' . esc_url( $link ) . '" class="inline-flex items-center gap-3 px-4 py-2 bg-primary text-white rounded hover:bg-primary/80">' . $image_html . $name_html . '</a>';
                continue;
            }

            $is_current = ( (int) $current_id === (int) $child->term_id );
            $link_class = 'block md:px-10 uppercase' . ( $is_current ? ' current' : '' );
            echo '<a href="' . esc_url( $link ) . '" class="' . esc_attr( $link_class ) . '">' . $name_html . '</a>';
        }

        echo '</div>';
    }
}

