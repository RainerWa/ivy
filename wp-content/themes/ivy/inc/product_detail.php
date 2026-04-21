<?php

/**
 * Produktbeschreibung zwischen Preis und Bestellbutton anzeigen
 */
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 15 );


remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );


/**
 * Produktbeschreibung (the_content) zwischen Preis und Bestellbutton anzeigen
 */
add_action('woocommerce_single_product_summary', function() {
	global $post;
	echo '<div class="product-long-description" style="margin-bottom:1.5em">';
	echo apply_filters('the_content', get_post_field('post_content', $post->ID));

	// Min/Max Menge anzeigen
	$min = get_post_meta($post->ID, '_wb_min_qty', true);
	$max = get_post_meta($post->ID, '_wb_max_qty', true);
	$min = (int) $min;
	$max = (int) $max;
	if ($min < 1) $min = 1;
	if ($max < 1) $max = 0;
	echo '<div class="wb-min-max-info" style="font-size:0.95em; color:#666; margin-top:0.5em;">';
	if ($max > 0) {
		echo '<div>';
		printf(__('Mindestmenge: %d', 'your-textdomain'), $min);
		echo '</div>';
		echo '<div>';
		printf(__('Maximalmenge: %d', 'your-textdomain'), $max);
		echo '</div>';
	} else {
		echo '<div>';
		printf(__('Mindestmenge: %d', 'your-textdomain'), $min);
		echo '</div>';
	}
	echo '</div>';

	echo '</div>';
}, 25);

/**
 * Beschreibungstab entfernen
 */
add_filter('woocommerce_product_tabs', function($tabs) {
	unset($tabs['description']);
	return $tabs;
}, 98);


/**
 * Backend-Feld für "Weitere Informationen" im Produktdaten-Panel
 */
add_action('woocommerce_product_options_general_product_data', function() {
	woocommerce_wp_textarea_input([
		'id' => '_ivy_additional_info',
		'label' => __('Weitere Informationen', 'ivy'),
		'desc_tip' => true,
		'description' => __('Dieser Text erscheint auf der Produktseite unter dem Preis.', 'ivy'),
		'rows' => 4,
	]);
});

/**
 * Speichern des Feldes
 */
add_action('woocommerce_process_product_meta', function($post_id) {
	if (isset($_POST['_ivy_additional_info'])) {
		update_post_meta($post_id, '_ivy_additional_info', wp_kses_post($_POST['_ivy_additional_info']));
	}
});


/**
 * Ausgabe auf der Produktseite unter dem Preis
 */
add_action('woocommerce_single_product_summary', function() {
	global $post;
	$info = get_post_meta($post->ID, '_ivy_additional_info', true);
	if (!empty($info)) {
		echo '<div class="ivy-additional-info" style="margin:1.5em 0 1em 0;">';
		echo '<h3 style="font-size:1.1em; font-weight:600; margin-bottom:0.3em;">' . esc_html__('Weitere Informationen', 'ivy') . '</h3>';
		echo wpautop(wp_kses_post($info));
		echo '</div>';
	}
}, 32);

/**
 * Backend-Feld für "Allergien" als Tagging-Combobox (ähnlich Up-Sells)
 */
add_action('woocommerce_product_options_general_product_data', function() {
    global $post;
    $allergies = get_post_meta($post->ID, '_ivy_allergies', true);
    if (!is_array($allergies)) {
        $allergies = array_filter(array_map('trim', explode(',', (string)$allergies)));
    }
    ?>
    <p class="form-field ivy_allergies_field">
        <label for="_ivy_allergies"><?php _e('Allergien', 'ivy'); ?></label>
        <select id="_ivy_allergies" name="_ivy_allergies[]" class="ivy-allergies wc-enhanced-select" multiple="multiple" style="width: 50%;">
            <?php
            $used = array();
            // Vorschläge: Allergien, die schon mal verwendet wurden
            $query = new WP_Query([
                'post_type' => 'product',
                'posts_per_page' => -1,
                'post_status' => 'any',
                'fields' => 'ids',
                'meta_query' => [
                    [
                        'key' => '_ivy_allergies',
                        'compare' => 'EXISTS',
                    ]
                ]
            ]);
            if ($query->have_posts()) {
                foreach ($query->posts as $pid) {
                    $vals = get_post_meta($pid, '_ivy_allergies', true);
                    if (!is_array($vals)) {
                        $vals = array_filter(array_map('trim', explode(',', (string)$vals)));
                    }
                    foreach ($vals as $val) {
                        $used[$val] = true;
                    }
                }
            }
            $used = array_keys($used);
            foreach ($used as $val) {
                printf('<option value="%s"%s>%s</option>', esc_attr($val), in_array($val, $allergies) ? ' selected' : '', esc_html($val));
            }
            // Neue Werte, die noch nicht in $used sind
            foreach ($allergies as $val) {
                if ($val && !in_array($val, $used)) {
                    printf('<option value="%s" selected>%s</option>', esc_attr($val), esc_html($val));
                }
            }
            ?>
        </select>
        <span class="description"><?php _e('Mehrere Allergien durch Komma oder Enter trennen. Bereits verwendete Begriffe werden vorgeschlagen.', 'ivy'); ?></span>
    </p>
    <script>
    jQuery(function($){
        $('#_ivy_allergies').select2({
            tags: true,
            tokenSeparators: [','],
            width: 'resolve',
            placeholder: 'Allergien eingeben...'
        });
    });
    </script>
    <?php
});

/**
 * Speichern des Feldes "Allergien"
 */
add_action('woocommerce_process_product_meta', function($post_id) {
    if (isset($_POST['_ivy_allergies'])) {
        $allergies = array_filter(array_map('sanitize_text_field', (array)$_POST['_ivy_allergies']));
        update_post_meta($post_id, '_ivy_allergies', $allergies);
    }
});

/**
 * Ausgabe der Allergien auf der Produktseite
 */
add_action('woocommerce_single_product_summary', function() {
    global $post;
    $allergies = get_post_meta($post->ID, '_ivy_allergies', true);
    if (is_array($allergies) && !empty($allergies)) {
        echo '<div class="ivy-allergies" style="margin:0.5em 0 0.5em 0; font-size:0.97em; color:#444;">';
        echo '<strong>' . esc_html__('Allergene:', 'ivy') . '</strong> ';
        echo esc_html(implode(', ', $allergies));
        echo '</div>';
    }
}, 33);


add_action('woocommerce_before_single_product_summary', function() {
    global $post;
    $hauptkategorie_slug = 'catering'; // Slug der gewünschten Hauptkategorie anpassen!
    $hauptkategorie = get_term_by('slug', $hauptkategorie_slug, 'product_cat');
    if (!$hauptkategorie) {
        return;
    }
    // Alle Unterkategorien dieser Hauptkategorie holen
    $unterkategorien = get_terms([
        'taxonomy' => 'product_cat',
        'parent' => $hauptkategorie->term_id,
        'hide_empty' => false,
    ]);
    // Hole die Kategorien des aktuellen Produkts
    $produkt_kategorien = wp_get_post_terms($post->ID, 'product_cat', ['fields' => 'ids']);
    if ($unterkategorien && !is_wp_error($unterkategorien)) {
        echo '<div class="ivy-subcategories" style="margin-bottom:1.5em">';
        echo '<strong>Unterkategorien von ' . esc_html($hauptkategorie->name) . ':</strong> ';
        echo '<ul style="margin:0; padding:0; list-style:none; display:flex; flex-wrap:wrap; gap:1em;">';
        foreach ($unterkategorien as $cat) {
            $link = get_term_link($cat);
            $is_current = in_array($cat->term_id, $produkt_kategorien) ? ' current' : '';
            echo '<li><a href="' . esc_url($link) . '" class="' . esc_attr($is_current) . '" style="text-decoration:underline;">' . esc_html($cat->name) . '</a></li>';
        }
        echo '</ul>';
        echo '</div>';
    }
}, 5);
