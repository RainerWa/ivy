<?php
/**
 * Override: Up-Sells (Verbundene Artikel) – mit Block-Style Mengenfeld
 *
 * Kopie von WooCommerce up-sells.php, angepasst für eigenes Mengenfeld und Button.
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( isset( $upsells ) && $upsells ) : ?>
<section class="up-sells upsells products">
	<?php
	$heading = apply_filters( 'woocommerce_product_upsells_products_heading', __( 'You may also like&hellip;', 'woocommerce' ) );
	if ( $heading ) :
		?>
		<h2><?php echo esc_html( $heading ); ?></h2>
	<?php endif; ?>

	<?php woocommerce_product_loop_start(); ?>

	<?php foreach ( $upsells as $upsell ) :
		$post_object = get_post( $upsell->get_id() );
		setup_postdata( $GLOBALS['post'] = $post_object );
		$product = wc_get_product( $upsell->get_id() );
		?>
		<li <?php wc_product_class( '', $product ); ?>>
			<?php
			// Bild, Titel, Preis etc. wie gewohnt:
			do_action( 'woocommerce_before_shop_loop_item' );
			do_action( 'woocommerce_before_shop_loop_item_title' );
			do_action( 'woocommerce_shop_loop_item_title' );
			do_action( 'woocommerce_after_shop_loop_item_title' );
			// Mengenfeld + Button:
			if ( $product->is_purchasable() && $product->is_in_stock() ) {
				$min = $product->get_min_purchase_quantity();
				$max = $product->get_max_purchase_quantity();
				$step = 1;
				$input_id = uniqid('quantity_');
				?>
				<form class="cart" action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" method="post" enctype="multipart/form-data">
					<div class="wc-block-components-quantity-selector">
						<button aria-label="Menge verringern" class="wc-block-components-quantity-selector__button wc-block-components-quantity-selector__button--minus" type="button">−</button>
						<input
							class="wc-block-components-quantity-selector__input input-text qty text"
							type="number"
							step="<?php echo esc_attr($step); ?>"
							min="<?php echo esc_attr($min); ?>"
							<?php if ( 0 < $max ) : ?>max="<?php echo esc_attr($max); ?>"<?php endif; ?>
							aria-label="<?php echo esc_attr( $product->get_name() ); ?> Menge"
							value="<?php echo esc_attr($min); ?>"
							id="<?php echo esc_attr($input_id); ?>"
							name="quantity"
							inputmode="numeric"
							autocomplete="off"
						>
						<button aria-label="Menge erhöhen" class="wc-block-components-quantity-selector__button wc-block-components-quantity-selector__button--plus" type="button">＋</button>
					</div>
					<button type="submit" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" class="button add_to_cart_button ajax_add_to_cart">
						<?php echo esc_html( $product->add_to_cart_text() ); ?>
					</button>
				</form>
			<?php }
			do_action( 'woocommerce_after_shop_loop_item' );
			?>
		</li>
	<?php endforeach; ?>

	<?php woocommerce_product_loop_end(); ?>
</section>
<?php
endif;
wp_reset_postdata();

