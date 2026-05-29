// Custom quantity selector JS for WooCommerce product page
(function() {
    function updateButtons(container) {
        var input = container.querySelector('.wc-block-components-quantity-selector__input');
        var minus = container.querySelector('.wc-block-components-quantity-selector__button--minus');
        var plus = container.querySelector('.wc-block-components-quantity-selector__button--plus');
        var min = parseInt(input.getAttribute('min')) || 1;
        var max = parseInt(input.getAttribute('max')) || 0;
        var step = parseInt(input.getAttribute('step')) || 1;
        var value = parseInt(input.value) || min;
        minus.disabled = value <= min;
        plus.disabled = max > 0 && value >= max;
    }
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.wc-block-components-quantity-selector').forEach(function(container) {
            var input = container.querySelector('.wc-block-components-quantity-selector__input');
            var minus = container.querySelector('.wc-block-components-quantity-selector__button--minus');
            var plus = container.querySelector('.wc-block-components-quantity-selector__button--plus');
            updateButtons(container);
            minus.addEventListener('click', function() {
                var min = parseInt(input.getAttribute('min')) || 1;
                var step = parseInt(input.getAttribute('step')) || 1;
                var value = parseInt(input.value) || min;
                if (value > min) {
                    input.value = value - step;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
                updateButtons(container);
            });
            plus.addEventListener('click', function() {
                var max = parseInt(input.getAttribute('max')) || 0;
                var step = parseInt(input.getAttribute('step')) || 1;
                var value = parseInt(input.value) || 1;
                if (!max || value < max) {
                    input.value = value + step;
                    input.dispatchEvent(new Event('change', { bubbles: true }));
                }
                updateButtons(container);
            });
            input.addEventListener('input', function() {
                updateButtons(container);
            });
        });
    });
})();

