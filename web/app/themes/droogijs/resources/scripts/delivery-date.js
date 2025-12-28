/**
 * Delivery Date Picker
 * Initializes Flatpickr on elements with .delivery-date-picker class
 */
(function() {
  'use strict';

  // Wait for Flatpickr to be loaded
  function initWhenReady() {
    if (typeof flatpickr === 'undefined') {
      setTimeout(initWhenReady, 100);
      return;
    }
    initDeliveryDatePickers();
  }

  // Initialize all delivery date pickers
  function initDeliveryDatePickers() {
    var pickers = document.querySelectorAll('.delivery-date-picker');

    pickers.forEach(function(picker) {
      // Skip if already initialized
      if (picker._flatpickr) {
        return;
      }

      var minDays = parseInt(picker.dataset.minDays || '1', 10);
      var minDate = new Date();
      minDate.setDate(minDate.getDate() + minDays);

      // Get Dutch locale if available
      var localeConfig = {};
      if (typeof flatpickr.l10ns !== 'undefined' && flatpickr.l10ns.nl) {
        localeConfig.locale = flatpickr.l10ns.nl;
      }

      flatpickr(picker, Object.assign({}, localeConfig, {
        dateFormat: 'd-m-Y',
        minDate: minDate,
        defaultDate: picker.value || minDate,
        disable: [
          // Disable Sundays
          function(date) {
            return date.getDay() === 0;
          }
        ],
        allowInput: false,
        disableMobile: true,
        clickOpens: true,
        onChange: function(selectedDates, dateStr, instance) {
          picker.value = dateStr;
        }
      }));
    });
  }

  // Initialize on DOM ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWhenReady);
  } else {
    initWhenReady();
  }

  // Re-initialize after WooCommerce AJAX cart updates
  if (typeof jQuery !== 'undefined') {
    jQuery(document.body).on('updated_cart_totals updated_checkout', function() {
      initDeliveryDatePickers();
    });
  }
})();
