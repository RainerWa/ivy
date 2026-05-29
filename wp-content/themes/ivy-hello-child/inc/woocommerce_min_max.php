<?php
/**
 * WooCommerce: Mindest- und Maximalmenge pro Produkt
 */

/**
 * Hilfsfunktion: Min/Max auslesen.
 */
function wb_get_product_qty_limits( $product_id ) {
	$min = (int) get_post_meta( $product_id, '_wb_min_qty', true );
	$max = (int) get_post_meta( $product_id, '_wb_max_qty', true );

	if ( $min < 1 ) {
		$min = 1;
	}

	// 0 oder leer = kein Limit
	if ( $max < 1 ) {
		$max = 0;
	}

	// Falls jemand Unsinn einträgt: max darf nicht kleiner als min sein
	if ( $max > 0 && $max < $min ) {
		$max = $min;
	}

	return [
		'min' => $min,
		'max' => $max,
	];
}

/**
 * Bei Variationen die Variation bevorzugen, sonst Parent-Produkt.
 */
function wb_get_effective_product_id_for_limits( $product_id, $variation_id = 0 ) {
	if ( ! empty( $variation_id ) ) {
		return (int) $variation_id;
	}

	return (int) $product_id;
}

/**
 * 1) Felder im Produkt-Backend anzeigen (einfaches Produkt / Produkt allgemein).
 */
add_action( 'woocommerce_product_options_inventory_product_data', function () {
	echo '<div class="options_group">';

	woocommerce_wp_text_input( [
		'id'                => '_wb_min_qty',
		'label'             => __( 'Mindestmenge', 'your-textdomain' ),
		'desc_tip'          => true,
		'description'       => __( 'Minimale Menge, die bestellt werden darf.', 'your-textdomain' ),
		'type'              => 'number',
		'custom_attributes' => [
			'min'  => '1',
			'step' => '1',
		],
	] );

	woocommerce_wp_text_input( [
		'id'                => '_wb_max_qty',
		'label'             => __( 'Maximalmenge', 'your-textdomain' ),
		'desc_tip'          => true,
		'description'       => __( 'Maximale Menge, die bestellt werden darf. Leer oder 0 = kein Maximum.', 'your-textdomain' ),
		'type'              => 'number',
		'custom_attributes' => [
			'min'  => '0',
			'step' => '1',
		],
	] );

	echo '</div>';
} );

/**
 * 2) Felder speichern.
 */
add_action( 'woocommerce_process_product_meta', function ( $post_id ) {
	$min = isset( $_POST['_wb_min_qty'] ) ? (int) wp_unslash( $_POST['_wb_min_qty'] ) : 1;
	$max = isset( $_POST['_wb_max_qty'] ) ? (int) wp_unslash( $_POST['_wb_max_qty'] ) : 0;

	if ( $min < 1 ) {
		$min = 1;
	}

	if ( $max < 0 ) {
		$max = 0;
	}

	if ( $max > 0 && $max < $min ) {
		$max = $min;
	}

	update_post_meta( $post_id, '_wb_min_qty', $min );
	update_post_meta( $post_id, '_wb_max_qty', $max );
} );

/**
 * 3) Optional: Felder auch für Variationen anzeigen.
 */
add_action( 'woocommerce_variation_options_inventory', function ( $loop, $variation_data, $variation ) {
	woocommerce_wp_text_input( [
		'id'                => "_wb_min_qty_variation[$loop]",
		'name'              => "_wb_min_qty_variation[$loop]",
		'value'             => get_post_meta( $variation->ID, '_wb_min_qty', true ),
		'label'             => __( 'Mindestmenge', 'your-textdomain' ),
		'desc_tip'          => true,
		'description'       => __( 'Minimale Menge für diese Variante.', 'your-textdomain' ),
		'type'              => 'number',
		'custom_attributes' => [
			'min'  => '1',
			'step' => '1',
		],
	] );

	woocommerce_wp_text_input( [
		'id'                => "_wb_max_qty_variation[$loop]",
		'name'              => "_wb_max_qty_variation[$loop]",
		'value'             => get_post_meta( $variation->ID, '_wb_max_qty', true ),
		'label'             => __( 'Maximalmenge', 'your-textdomain' ),
		'desc_tip'          => true,
		'description'       => __( 'Maximale Menge für diese Variante. Leer oder 0 = kein Maximum.', 'your-textdomain' ),
		'type'              => 'number',
		'custom_attributes' => [
			'min'  => '0',
			'step' => '1',
		],
	] );
}, 10, 3 );

/**
 * 4) Variationsfelder speichern.
 */
add_action( 'woocommerce_save_product_variation', function ( $variation_id, $i ) {
	$min_values = isset( $_POST['_wb_min_qty_variation'] ) ? (array) wp_unslash( $_POST['_wb_min_qty_variation'] ) : [];
	$max_values = isset( $_POST['_wb_max_qty_variation'] ) ? (array) wp_unslash( $_POST['_wb_max_qty_variation'] ) : [];

	$min = isset( $min_values[ $i ] ) ? (int) $min_values[ $i ] : 1;
	$max = isset( $max_values[ $i ] ) ? (int) $max_values[ $i ] : 0;

	if ( $min < 1 ) {
		$min = 1;
	}

	if ( $max < 0 ) {
		$max = 0;
	}

	if ( $max > 0 && $max < $min ) {
		$max = $min;
	}

	update_post_meta( $variation_id, '_wb_min_qty', $min );
	update_post_meta( $variation_id, '_wb_max_qty', $max );
}, 10, 2 );

/**
 * 5) Mengenfeld auf Produktseite anpassen.
 */
add_filter( 'woocommerce_quantity_input_args', function ( $args, $product ) {
	if ( ! $product instanceof WC_Product ) {
		return $args;
	}

	$limits = wb_get_product_qty_limits( $product->get_id() );

	$args['min_value'] = $limits['min'];

	if ( $limits['max'] > 0 ) {
		$args['max_value'] = $limits['max'];
	}

	// Standardwert beim ersten Laden
	if ( empty( $args['input_value'] ) || $args['input_value'] < $limits['min'] ) {
		$args['input_value'] = $limits['min'];
	}

	return $args;
}, 10, 2 );

/**
 * 6) Add-to-cart validieren.
 */
add_filter( 'woocommerce_add_to_cart_validation', function ( $passed, $product_id, $quantity, $variation_id = 0 ) {
	$effective_product_id = wb_get_effective_product_id_for_limits( $product_id, $variation_id );
	$limits               = wb_get_product_qty_limits( $effective_product_id );

	if ( $quantity < $limits['min'] ) {
		wc_add_notice(
			sprintf(
				__( 'Dieses Produkt kann nur ab %d Stück bestellt werden.', 'your-textdomain' ),
				$limits['min']
			),
			'error'
		);
		return false;
	}

	if ( $limits['max'] > 0 && $quantity > $limits['max'] ) {
		wc_add_notice(
			sprintf(
				__( 'Dieses Produkt kann nur bis maximal %d Stück bestellt werden.', 'your-textdomain' ),
				$limits['max']
			),
			'error'
		);
		return false;
	}

	return $passed;
}, 10, 4 );

/**
 * 7) Auch Änderungen im Warenkorb validieren.
 */
add_filter( 'woocommerce_update_cart_validation', function ( $passed, $cart_item_key, $values, $quantity ) {
	$product_id   = isset( $values['product_id'] ) ? (int) $values['product_id'] : 0;
	$variation_id = isset( $values['variation_id'] ) ? (int) $values['variation_id'] : 0;

	$effective_product_id = wb_get_effective_product_id_for_limits( $product_id, $variation_id );
	$limits               = wb_get_product_qty_limits( $effective_product_id );

	if ( $quantity < $limits['min'] ) {
		wc_add_notice(
			sprintf(
				__( 'Dieses Produkt kann nur ab %d Stück bestellt werden.', 'your-textdomain' ),
				$limits['min']
			),
			'error'
		);
		return false;
	}

	if ( $limits['max'] > 0 && $quantity > $limits['max'] ) {
		wc_add_notice(
			sprintf(
				__( 'Dieses Produkt kann nur bis maximal %d Stück bestellt werden.', 'your-textdomain' ),
				$limits['max']
			),
			'error'
		);
		return false;
	}

	return $passed;
}, 10, 4 );

/**
 * Min/Max unterhalb des Preises im Shop-Loop anzeigen
 */
add_action( 'woocommerce_after_shop_loop_item_title', function() {
    global $product;
    if ( ! $product instanceof WC_Product ) {
        return;
    }
    $min = get_post_meta( $product->get_id(), '_wb_min_qty', true );
    $max = get_post_meta( $product->get_id(), '_wb_max_qty', true );
    $min = (int) $min;
    $max = (int) $max;
    if ( $min < 1 ) {
        $min = 1;
    }
    if ( $max < 1 ) {
        $max = 0;
    }
    echo '<div class="wb-min-max-info" style="font-size:0.95em; color:#666; margin-top:0.3em;">';
    if ( $max > 0 ) {
        printf( __( 'Mindestmenge: %d &ndash; Maximalmenge: %d', 'your-textdomain' ), $min, $max );
    } else {
        printf( __( 'Mindestmenge: %d', 'your-textdomain' ), $min );
    }
    echo '</div>';
}, 15 );
