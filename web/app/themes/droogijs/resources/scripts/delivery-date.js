/**
 * Delivery Date Picker
 * Initializes Flatpickr on elements with .delivery-date-picker class
 *
 * Reads configuration from data attributes:
 * - data-min-days: Minimum days ahead
 * - data-disabled-weekdays: Comma-separated weekday numbers (0=Sunday, 6=Saturday)
 * - data-blocked-dates: JSON array of blocked dates/ranges
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

  // Parse disabled weekdays from data attribute
  function parseDisabledWeekdays(picker) {
    var weekdaysStr = picker.dataset.disabledWeekdays || '';
    if (!weekdaysStr) return [];
    return weekdaysStr.split(',').map(function(d) {
      return parseInt(d.trim(), 10);
    }).filter(function(d) {
      return !isNaN(d);
    });
  }

  // Parse blocked dates from data attribute
  function parseBlockedDates(picker) {
    var blockedStr = picker.dataset.blockedDates || '[]';
    try {
      return JSON.parse(blockedStr);
    } catch (e) {
      return [];
    }
  }

  // Parse date string (YYYY-MM-DD) to local Date object without timezone issues
  function parseDate(dateStr) {
    var parts = dateStr.split('-');
    return new Date(parts[0], parts[1] - 1, parts[2]);
  }

  // Build the disable array for Flatpickr
  function buildDisableArray(disabledWeekdays, blockedDates) {
    var disable = [];

    // Add weekday function if any weekdays are disabled
    if (disabledWeekdays.length > 0) {
      disable.push(function(date) {
        return disabledWeekdays.indexOf(date.getDay()) !== -1;
      });
    }

    // Add blocked dates
    blockedDates.forEach(function(item) {
      if (typeof item === 'string') {
        // Single date
        disable.push(parseDate(item));
      } else if (item.from && item.to) {
        // Date range
        disable.push({
          from: parseDate(item.from),
          to: parseDate(item.to)
        });
      }
    });

    return disable;
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

      var disabledWeekdays = parseDisabledWeekdays(picker);
      var blockedDates = parseBlockedDates(picker);
      var disableArray = buildDisableArray(disabledWeekdays, blockedDates);

      // Get Dutch locale if available
      var localeConfig = {};
      if (typeof flatpickr.l10ns !== 'undefined' && flatpickr.l10ns.nl) {
        localeConfig.locale = flatpickr.l10ns.nl;
      }

      flatpickr(picker, Object.assign({}, localeConfig, {
        dateFormat: 'd-m-Y',
        minDate: minDate,
        defaultDate: picker.value || minDate,
        disable: disableArray,
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
