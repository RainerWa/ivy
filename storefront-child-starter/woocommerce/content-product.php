<?php
/**
 * Produktkarte im Shop-Grid.
 *
 * Diese Datei überschreibt WooCommerce:
 * woocommerce/content-product.php
 */

defined('ABSPATH') || exit;

global $product;

if (!is_a($product, WC_Product::class) || !$product->is_visible()) {
    return;
}

$product_id      = $product->get_id();
$product_name    = $product->get_name();
$product_url     = get_permalink($product_id);
$product_price   = $product->get_price_html();
$product_excerpt = wp_strip_all_tags(get_the_excerpt($product_id));
$product_excerpt = wp_trim_words($product_excerpt, 18, '…');
$product_image   = $product->get_image('woocommerce_thumbnail', [
    'class' => 'h-full w-full object-cover transition duration-500 group-hover:scale-[1.03]',
    'loading' => 'lazy',
]);
$product_cats    = wc_get_product_category_list($product_id, ', ', '', '');
$is_catering     = has_term('catering', 'product_cat', $product_id);
$price_label     = $is_catering ? __('ab', 'storefront-child-starter') . ' ' : '';
$button_text     = $is_catering ? __('Menü ansehen', 'storefront-child-starter') : __('Produkt ansehen', 'storefront-child-starter');
?>
<li <?php wc_product_class('group list-none', $product); ?>>
    <article class="overflow-hidden rounded-[28px] bg-white transition duration-300 hover:-translate-y-1 hover:shadow-[0_20px_60px_rgba(0,0,0,0.08)]">
        <a href="<?php echo esc_url($product_url); ?>" class="block">
            <div class="relative aspect-[4/5] overflow-hidden bg-neutral-100">
                <?php if ($product_image) : ?>
                    <?php echo $product_image; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                <?php else : ?>
                    <div class="flex h-full items-center justify-center bg-[linear-gradient(135deg,#ece7df,#d9d3ca)]">
                        <span class="text-[11px] uppercase tracking-[0.22em] text-neutral-500">
                            <?php esc_html_e('Kein Bild', 'storefront-child-starter'); ?>
                        </span>
                    </div>
                <?php endif; ?>

                <?php if ($product->is_on_sale()) : ?>
                    <span class="absolute left-4 top-4 rounded-full bg-black px-3 py-1 text-[10px] uppercase tracking-[0.22em] text-white">
                        <?php esc_html_e('Angebot', 'storefront-child-starter'); ?>
                    </span>
                <?php endif; ?>
            </div>
        </a>

        <div class="p-5 md:p-6">
            <?php if ($product_cats) : ?>
                <div class="mb-3 text-[10px] uppercase tracking-[0.22em] text-neutral-500">
                    <?php echo wp_kses_post($product_cats); ?>
                </div>
            <?php endif; ?>

            <div class="flex items-start justify-between gap-4">
                <div>
                    <h3 class="text-xl leading-tight md:text-2xl">
                        <a href="<?php echo esc_url($product_url); ?>" class="no-underline hover:opacity-70">
                            <?php echo esc_html($product_name); ?>
                        </a>
                    </h3>
                </div>

                <?php if ($product_price) : ?>
                    <div class="shrink-0 text-right">
                        <div class="text-[10px] uppercase tracking-[0.22em] text-neutral-500">
                            <?php echo esc_html($price_label); ?>
                        </div>
                        <div class="text-base font-medium md:text-lg">
                            <?php echo wp_kses_post($product_price); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($product_excerpt) : ?>
                <p class="mt-4 text-sm leading-6 text-neutral-700">
                    <?php echo esc_html($product_excerpt); ?>
                </p>
            <?php endif; ?>

            <div class="mt-6 flex items-center justify-between gap-3">
                <a href="<?php echo esc_url($product_url); ?>" class="inline-flex items-center rounded-full border border-black px-4 py-3 text-[11px] uppercase tracking-[0.18em] transition hover:bg-black hover:text-white">
                    <?php echo esc_html($button_text); ?>
                </a>

                <?php if ($product->is_purchasable() && $product->is_in_stock() && !$is_catering && $product->supports('ajax_add_to_cart')) : ?>
                    <?php
                    echo apply_filters(
                        'woocommerce_loop_add_to_cart_link',
                        sprintf(
                            '<a href="%s" data-quantity="1" class="%s" %s>%s</a>',
                            esc_url($product->add_to_cart_url()),
                            esc_attr('add_to_cart_button ajax_add_to_cart inline-flex items-center rounded-full bg-black px-4 py-3 text-[11px] uppercase tracking-[0.18em] text-white transition hover:opacity-90'),
                            wc_implode_html_attributes([
                                'data-product_id'  => $product->get_id(),
                                'data-product_sku' => $product->get_sku(),
                                'aria-label'       => $product->add_to_cart_description(),
                                'rel'              => 'nofollow',
                            ]),
                            esc_html($product->add_to_cart_text())
                        ),
                        $product,
                        []
                    );
                    ?>
                <?php endif; ?>
            </div>
        </div>
    </article>
</li>
