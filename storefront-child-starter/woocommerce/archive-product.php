<?php
/**
 * Shop / Produktarchiv.
 */

defined('ABSPATH') || exit;

get_header('shop');
?>
<main class="mx-auto max-w-[1440px] px-4 py-8 md:px-8 lg:px-10">
    <header class="mb-10">
        <?php if (apply_filters('woocommerce_show_page_title', true)) : ?>
            <p class="mb-3 text-[11px] uppercase tracking-[0.22em] text-neutral-500"><?php esc_html_e('Shop', 'storefront-child-starter'); ?></p>
            <h1 class="text-4xl md:text-6xl"><?php woocommerce_page_title(); ?></h1>
        <?php endif; ?>
        <?php do_action('woocommerce_archive_description'); ?>
    </header>

    <?php if (woocommerce_product_loop()) : ?>
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
            <?php do_action('woocommerce_before_shop_loop'); ?>
        </div>

        <?php woocommerce_product_loop_start(); ?>
            <?php if (wc_get_loop_prop('total')) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php do_action('woocommerce_shop_loop'); ?>
                    <?php wc_get_template_part('content', 'product'); ?>
                <?php endwhile; ?>
            <?php endif; ?>
        <?php woocommerce_product_loop_end(); ?>

        <div class="mt-10">
            <?php do_action('woocommerce_after_shop_loop'); ?>
        </div>
    <?php else : ?>
        <?php do_action('woocommerce_no_products_found'); ?>
    <?php endif; ?>
</main>
<?php
get_footer('shop');
