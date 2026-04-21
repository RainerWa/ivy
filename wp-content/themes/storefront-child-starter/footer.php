<?php
/**
 * Theme footer.
 */

defined('ABSPATH') || exit;
?>
<footer class="mt-20 border-t border-black/10 bg-white">
    <div class="mx-auto grid max-w-[1440px] gap-10 px-4 py-10 md:grid-cols-3 md:px-8 lg:px-10">
        <div>
            <p class="text-[11px] uppercase tracking-[0.22em] text-neutral-500">Restaurant</p>
            <p class="mt-3 max-w-[28ch] text-sm leading-6 text-neutral-700">
                Stilvolle Website für Catering, Content und späteren Merchandise-Verkauf.
            </p>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-[0.22em] text-neutral-500">Navigation</p>
            <?php
            wp_nav_menu([
                'theme_location' => 'footer',
                'container'      => false,
                'fallback_cb'    => false,
                'menu_class'     => 'mt-3 space-y-2 text-sm text-neutral-700',
                'depth'          => 1,
            ]);
            ?>
        </div>
        <div>
            <p class="text-[11px] uppercase tracking-[0.22em] text-neutral-500">Kontakt</p>
            <p class="mt-3 text-sm leading-6 text-neutral-700">
                Beispielstraße 10<br>
                20095 Hamburg<br>
                hello@example.com
            </p>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
