<?php
/**
 * Override WooCommerce Quantity Input with custom block-style plus/minus buttons
 *
 * Place this file in yourtheme/woocommerce/global/quantity-input.php
 */

defined( 'ABSPATH' ) || exit;

/* translators: %s: Quantity. */
$label = ! empty( $args['product_name'] ) ? sprintf( esc_html__( '%s quantity', 'woocommerce' ), wp_strip_all_tags( $args['product_name'] ) ) : esc_html__( 'Quantity', 'woocommerce' );

$min = isset($min_value) ? $min_value : 1;
$max = isset($max_value) ? $max_value : 0;
$step = isset($step) ? $step : 1;
$value = isset($input_value) ? $input_value : $min;
$input_id = isset($input_id) ? $input_id : uniqid('quantity_');
$readonly = isset($readonly) ? $readonly : false;
$type = isset($type) ? $type : 'number';
$classes = isset($classes) ? $classes : array('input-text', 'qty', 'text');
$input_name = isset($input_name) ? $input_name : 'quantity';
$placeholder = isset($placeholder) ? $placeholder : '';
$inputmode = isset($inputmode) ? $inputmode : 'numeric';
$autocomplete = isset($autocomplete) ? $autocomplete : 'off';
?>
<div class="wc-block-components-quantity-selector">
    <button aria-label="Menge verringern" class="wc-block-components-quantity-selector__button wc-block-components-quantity-selector__button--minus" type="button">−</button>


    <input
        class="wc-block-components-quantity-selector__input <?php echo esc_attr( join( ' ', (array) $classes ) ); ?>"
        type="number"
        step="<?php echo esc_attr($step); ?>"
        min="<?php echo esc_attr($min); ?>"
        <?php if ( 0 < $max ) : ?>max="<?php echo esc_attr($max); ?>"<?php endif; ?>
        aria-label="<?php echo esc_attr($label); ?>"
        value="<?php echo esc_attr($value); ?>"
        id="<?php echo esc_attr($input_id); ?>"
        name="<?php echo esc_attr($input_name); ?>"
        placeholder="<?php echo esc_attr($placeholder); ?>"
        inputmode="<?php echo esc_attr($inputmode); ?>"
        autocomplete="<?php echo esc_attr($autocomplete); ?>"
        <?php echo $readonly ? 'readonly="readonly"' : ''; ?>
    >
    <button aria-label="Menge erhöhen" class="wc-block-components-quantity-selector__button wc-block-components-quantity-selector__button--plus" type="button">＋</button>
</div>

