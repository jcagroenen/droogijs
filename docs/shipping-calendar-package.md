# Shipping Calendar - Composer Package (Future)

This document outlines how to extract the Shipping Calendar feature into a standalone Composer package for Acorn/Sage.

## Why a Composer Package?

- Reusable across multiple Sage projects
- Keeps all Acorn benefits (Blade, service container, etc.)
- Easy installation via `composer require`
- Separate versioning and updates

## Package Structure

```
droogijs-shipping-calendar/
├── composer.json
├── src/
│   ├── ShippingCalendarServiceProvider.php
│   ├── Admin/
│   │   └── ShippingCalendar.php
│   └── WooCommerce/
│       └── DeliveryDate.php
├── resources/
│   └── scripts/
│       └── delivery-date.js
└── config/
    └── shipping-calendar.php
```

## composer.json

```json
{
  "name": "droogijs/shipping-calendar",
  "description": "Delivery date picker for WooCommerce with Acorn/Sage",
  "type": "library",
  "license": "MIT",
  "authors": [
    {
      "name": "Droogijs",
      "email": "info@droogijs.nl"
    }
  ],
  "require": {
    "php": "^8.1",
    "roots/acorn": "^4.0 || ^5.0"
  },
  "autoload": {
    "psr-4": {
      "Droogijs\\ShippingCalendar\\": "src/"
    }
  },
  "extra": {
    "acorn": {
      "providers": [
        "Droogijs\\ShippingCalendar\\ShippingCalendarServiceProvider"
      ]
    }
  }
}
```

## Service Provider

`src/ShippingCalendarServiceProvider.php`:

```php
<?php

namespace Droogijs\ShippingCalendar;

use Illuminate\Support\ServiceProvider;
use Droogijs\ShippingCalendar\Admin\ShippingCalendar;
use Droogijs\ShippingCalendar\WooCommerce\DeliveryDate;

class ShippingCalendarServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/shipping-calendar.php',
            'shipping-calendar'
        );

        $this->app->singleton(ShippingCalendar::class);
        $this->app->singleton(DeliveryDate::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/shipping-calendar.php' => config_path('shipping-calendar.php'),
        ], 'shipping-calendar-config');

        $this->app->make(ShippingCalendar::class)->boot();
        $this->app->make(DeliveryDate::class)->boot();

        // Enqueue scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts(): void
    {
        if (! is_product() && ! is_cart() && ! is_checkout()) {
            return;
        }

        // Flatpickr from CDN (or bundle locally)
        wp_enqueue_style('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
        wp_enqueue_script('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr');
        wp_enqueue_script('flatpickr-nl', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/nl.js');

        // Package script
        wp_enqueue_script(
            'shipping-calendar',
            $this->getAssetUrl('delivery-date.js'),
            ['flatpickr'],
            null,
            ['in_footer' => true, 'strategy' => 'defer']
        );
    }

    protected function getAssetUrl(string $file): string
    {
        // Adjust path based on package location
        return plugin_dir_url(__DIR__) . 'resources/scripts/' . $file;
    }
}
```

## Config File

`config/shipping-calendar.php`:

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | These are the default settings used when no admin settings are saved.
    |
    */

    'enabled' => true,
    'min_days' => 1,
    'disabled_weekdays' => [0], // Sunday
    'blocked_dates' => '',
    'helper_text' => 'Levering is mogelijk vanaf morgen (behalve op zondag)',
];
```

## Installation (Future)

Once published to Packagist or a private repository:

```bash
cd web/app/themes/your-theme
composer require droogijs/shipping-calendar
```

The service provider auto-registers via Acorn's package discovery (the `extra.acorn.providers` key in composer.json).

Optionally publish the config:

```bash
wp acorn vendor:publish --tag=shipping-calendar-config
```

## Migration from Theme

To migrate from the current theme implementation:

1. Remove from theme:
   - `app/Admin/ShippingCalendar.php`
   - `app/WooCommerce/DeliveryDate.php`
   - `resources/scripts/delivery-date.js`
   - References in `ThemeServiceProvider.php`
   - Flatpickr enqueue in `setup.php`

2. Install the package:
   ```bash
   composer require droogijs/shipping-calendar
   ```

3. Settings are stored in `wp_options` table, so existing settings will persist.

## Current Theme Location

The feature currently lives in:

| File | Purpose |
|------|---------|
| `app/Admin/ShippingCalendar.php` | Admin settings page |
| `app/WooCommerce/DeliveryDate.php` | WooCommerce hooks |
| `resources/scripts/delivery-date.js` | Flatpickr initialization |
| `app/Providers/ThemeServiceProvider.php` | Registration |
| `app/setup.php` | Script enqueuing |

## Notes

- The quantity input styling (`woocommerce/global/quantity-input.blade.php`) is NOT part of this package - that's a general theme styling fix.
- Flatpickr could be bundled locally instead of using CDN in a production package.
- Consider adding more configuration options (date format, locale, etc.) for flexibility.
