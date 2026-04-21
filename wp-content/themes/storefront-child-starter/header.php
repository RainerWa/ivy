<?php
/**
 * Theme header.
 */

defined('ABSPATH') || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-[#f7f3ee] text-neutral-900 antialiased'); ?>>
<?php wp_body_open(); ?>
<header class="sticky top-0 z-40 border-b border-black/5 bg-[#f7f3ee]/95 backdrop-blur">
    <div class="mx-auto flex max-w-[1440px] items-center justify-between gap-4 px-4 py-4 md:px-8 lg:px-10">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="text-lg font-medium tracking-[0.08em] uppercase">
            <?php bloginfo('name'); ?>
        </a>

        <nav class="hidden items-center gap-6 md:flex" aria-label="Hauptnavigation">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'fallback_cb'    => false,
                'menu_class'     => 'flex items-center gap-6 text-sm uppercase tracking-[0.16em]',
                'depth'          => 1,
            ]);
            ?>
        </nav>

        <div class="flex items-center gap-3">
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="hidden rounded-full border border-black px-4 py-2 text-[11px] uppercase tracking-[0.18em] md:inline-flex">Shop</a>
            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="inline-flex rounded-full bg-black px-4 py-2 text-[11px] uppercase tracking-[0.18em] text-white">
                Warenkorb
            </a>
        </div>
    </div>
</header>
