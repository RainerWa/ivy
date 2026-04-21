<?php
/**
 * Front page template.
 */

defined('ABSPATH') || exit;

get_header();
?>

<main class="mx-auto max-w-[1440px] px-4 py-6 md:px-8 lg:px-10">
    <section class="grid gap-4 md:grid-cols-12 md:gap-6">
        <div class="overflow-hidden rounded-[28px] bg-neutral-100 md:col-span-8">
            <div class="aspect-[16/10] w-full bg-[linear-gradient(135deg,#d9d3ca,#f4f1eb)]"></div>
            <div class="p-6 md:p-8">
                <p class="mb-3 text-[11px] uppercase tracking-[0.22em] text-neutral-500">Fresh catering</p>
                <h1 class="max-w-[12ch] text-4xl leading-none md:text-6xl">Catering mit Restaurant-Look &amp; Gefühl.</h1>
                <p class="mt-4 max-w-[55ch] text-sm leading-6 text-neutral-700 md:text-base">
                    Elegante Menüs, klare Produktseiten und ein Shop, der später auch Merchandise aufnehmen kann.
                </p>
                <div class="mt-6 flex flex-wrap gap-3">
                    <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="inline-flex rounded-full border border-black px-5 py-3 text-xs uppercase tracking-[0.18em] transition hover:bg-black hover:text-white">
                        Catering ansehen
                    </a>
                    <a href="#content" class="inline-flex rounded-full border border-neutral-300 px-5 py-3 text-xs uppercase tracking-[0.18em] transition hover:border-black">
                        Mehr erfahren
                    </a>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:col-span-4">
            <article class="overflow-hidden rounded-[28px] bg-[#ece5d7] p-6 md:p-8">
                <p class="text-[11px] uppercase tracking-[0.22em] text-neutral-600">Lieferung</p>
                <h2 class="mt-3 text-2xl leading-tight">Wähle Datum, Mindestmenge und Extras.</h2>
                <p class="mt-3 text-sm leading-6 text-neutral-700">
                    Catering-Produkte lassen sich später mit Mindestmengen, Lieferslots und gesperrten Tagen kombinieren.
                </p>
            </article>

            <article class="overflow-hidden rounded-[28px] bg-neutral-900 p-6 text-white md:p-8">
                <p class="text-[11px] uppercase tracking-[0.22em] text-white/70">Content</p>
                <h2 class="mt-3 text-2xl leading-tight">Editoriale Seiten für Marke, Story und Locations.</h2>
                <p class="mt-3 text-sm leading-6 text-white/80">
                    Ideal für Restaurant, Storytelling, Standorte, Firmenkunden und spätere Aktionen.
                </p>
            </article>
        </div>
    </section>

    <section id="content" class="mt-16">
        <div class="mb-6 flex items-end justify-between gap-4">
            <div>
                <p class="text-[11px] uppercase tracking-[0.22em] text-neutral-500">Shop</p>
                <h2 class="mt-2 text-3xl md:text-4xl">Beliebte Menüs</h2>
            </div>
            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="hidden text-sm underline underline-offset-4 md:inline">Zum Shop</a>
        </div>

        <?php echo do_shortcode('[products limit="4" columns="4" orderby="date" order="DESC"]'); ?>
    </section>
</main>

<?php
get_footer();
