<?php
// inc/checkout.php
// Zusätzliche Checkout-Felder für Lieferdatum und Lieferzeit

// Konfiguration: Mindestvorlauf in Tagen
$ivy_min_days_ahead = 2;

// Feld im Checkout über den Rechnungsdaten (linke Spalte) anzeigen
add_action('woocommerce_before_checkout_billing_form', function() use ($ivy_min_days_ahead) {
    $checkout = WC()->checkout();
    // Mindestdatum berechnen (heute + X Tage)
    $min_date = (new DateTime('+'.$ivy_min_days_ahead.' days'))->format('Y-m-d');
    echo '<div id="ivy-delivery-fields"><h3>' . __('Lieferdatum & Uhrzeit', 'ivy') . '</h3>';
    woocommerce_form_field('ivy_delivery_datetime', [
        'type'        => 'datetime-local',
        'class'       => array('form-row-wide'),
        'label'       => __('Wunschtermin für die Lieferung', 'ivy'),
        'required'    => true,
        'custom_attributes' => [
            'min' => $min_date.'T00:00',
            'step' => 1200, // 20 Minuten in Sekunden
        ],
    ], $checkout->get_value('ivy_delivery_datetime'));
    echo '<small style="color:#666;">' . sprintf(__('Nur Termine ab %s möglich.', 'ivy'), date_i18n(get_option('date_format'), strtotime($min_date))) . '</small>';
    echo '</div>';
});

// Validierung
add_action('woocommerce_checkout_process', function() use ($ivy_min_days_ahead) {
    if (empty($_POST['ivy_delivery_datetime'])) {
        wc_add_notice(__('Bitte geben Sie ein Lieferdatum und eine Uhrzeit an.', 'ivy'), 'error');
    } else {
        $min_date = (new DateTime('+'.$ivy_min_days_ahead.' days'))->format('Y-m-d');
        $user_date = substr(sanitize_text_field($_POST['ivy_delivery_datetime']), 0, 10);
        if ($user_date < $min_date) {
            wc_add_notice(sprintf(__('Das Lieferdatum muss mindestens %d Tage in der Zukunft liegen.', 'ivy'), $ivy_min_days_ahead), 'error');
        }
    }
});

// Speichern
add_action('woocommerce_checkout_update_order_meta', function($order_id) {
    if (!empty($_POST['ivy_delivery_datetime'])) {
        update_post_meta($order_id, '_ivy_delivery_datetime', sanitize_text_field($_POST['ivy_delivery_datetime']));
    }
});

// Anzeige in der Admin-Bestellübersicht
add_action('woocommerce_admin_order_data_after_billing_address', function($order){
    $datetime = get_post_meta($order->get_id(), '_ivy_delivery_datetime', true);
    if ($datetime) {
        echo '<p><strong>' . __('Liefertermin', 'ivy') . ':</strong> ' . esc_html($datetime) . '</p>';
    }
});

// Anzeige in der E-Mail
add_filter('woocommerce_email_order_meta_fields', function($fields, $sent_to_admin, $order) {
    $datetime = get_post_meta($order->get_id(), '_ivy_delivery_datetime', true);
    if ($datetime) {
        $fields['ivy_delivery_datetime'] = array(
            'label' => __('Liefertermin', 'ivy'),
            'value' => $datetime,
        );
    }
    return $fields;
}, 10, 3);

// Entferne das Feld aus woocommerce_after_order_notes, falls noch vorhanden
remove_action('woocommerce_after_order_notes', 'ivy_delivery_datetime');


// Zahlungsarten entfernen (Checkout bleibt Anfrage)
add_filter('woocommerce_cart_needs_payment', '__return_false');

// Lieferadresse IMMER abfragen, auch bei virtuellen Produkten
add_filter('woocommerce_cart_needs_shipping_address', '__return_true');

// Checkbox "Rechnungsadresse ist abweichend" standardmäßig DEAKTIVIEREN (damit Rechnungsadresse nur bei Bedarf erscheint)
add_filter('woocommerce_ship_to_different_address_checked', '__return_false');

// Keine Felder entfernen oder als optional markieren – WooCommerce-Standardverhalten nutzen

// Button-Text anpassen
add_filter('woocommerce_order_button_text', function($text) {
    return __('Anfrage absenden', 'ivy');
});

// Bestellung als Anfrage kennzeichnen und E-Mails versenden
add_action('woocommerce_checkout_update_order_meta', function($order_id) {
    update_post_meta($order_id, '_ivy_is_request', 1);
});

add_action('woocommerce_thankyou', function($order_id) {

    $order = wc_get_order($order_id);
    if (get_post_meta($order_id, '_ivy_is_request', true)) {
        $admin_email = get_option('admin_email');
        $customer_email = $order->get_billing_email();
        $headers = array('Content-Type: text/html; charset=UTF-8');

        // Logging für Debugging
        if (empty($admin_email)) {
            error_log('IVY CATERING: Admin-E-Mail ist leer!');
        }
        if (empty($customer_email)) {
            error_log('IVY CATERING: Kunden-E-Mail ist leer!');
        }
        // Betreiber-Mail
        $admin_sent = wp_mail(
            $admin_email,
            'Neue Catering-Anfrage',
            'Es ist eine neue Catering-Anfrage eingegangen. Bitte prüfen Sie die Bestellung im Backend.',
            $headers
        );

        if (!$admin_sent) {
            error_log('IVY CATERING: Betreiber-Mail konnte nicht gesendet werden!');
        }
        // Besteller-Mail
        $customer_sent = wp_mail(
            $customer_email,
            'Ihre Catering-Anfrage ist eingegangen',
            'Vielen Dank für Ihre Anfrage! Wir melden uns schnellstmöglich.',
            $headers
        );
        if (!$customer_sent) {
            error_log('IVY CATERING: Kunden-Mail konnte nicht gesendet werden!');
        }
    }
});


// Keine weiteren Checkout-Feld-Anpassungen nötig, damit WooCommerce-Standard greift
