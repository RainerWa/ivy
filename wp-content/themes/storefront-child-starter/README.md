# Storefront Child Starter

## Installation
1. `storefront` installieren und aktivieren.
2. Dieses Theme als ZIP hochladen oder in `wp-content/themes/` entpacken.
3. Child Theme aktivieren.
4. Im Theme-Ordner `npm install` ausführen.
5. Für Entwicklung `npm run dev`, für Build `npm run build`.

## Wichtige Dateien
- `functions.php`: Theme-Support, Menüs, Enqueue
- `front-page.php`: Startseite
- `woocommerce/content-product.php`: Produktkarte
- `woocommerce/archive-product.php`: Shop-Archiv
- `woocommerce/single-product.php`: Produktdetail-Grundlayout

## Hinweise
- `assets/css/app.min.css` ist als Platzhalter enthalten.
- Für echtes Tailwind-CSS bitte Build laufen lassen.
- Catering-Logik wie Mindestmengen, Liefertage und Sperrtage kommt später über Plugins oder Custom-Code dazu.
