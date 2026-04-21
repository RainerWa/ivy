<?php
/**
 * Einzelnes Produkt.
 */

defined('ABSPATH') || exit;

get_header('shop');
?>
<main class="mx-auto max-w-[1440px] px-4 py-8 md:px-8 lg:px-10">
    <?php while (have_posts()) : the_post(); ?>
        <?php global $product; ?>

        <article id="product-<?php the_ID(); ?>" <?php wc_product_class('grid gap-8 lg:grid-cols-12 lg:gap-12', $product); ?>>
            <div class="lg:col-span-7">
                <div class="overflow-hidden rounded-[28px] bg-white p-4 md:p-6">
                    <?php do_action('woocommerce_before_single_product_summary'); ?>
                </div>
            </div>

            <div class="lg:col-span-5">
                <div class="rounded-[28px] bg-white p-6 md:p-8">
                    <?php do_action('woocommerce_single_product_summary'); ?>
                </div>
            </div>
        </article>

        <section class="mt-10 rounded-[28px] bg-white p-6 md:p-8">
            <?php do_action('woocommerce_after_single_product_summary'); ?>
        </section>
    <?php endwhile; ?>
</main>
<?php
get_footer('shop');
