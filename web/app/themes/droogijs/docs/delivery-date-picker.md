# Delivery Date Picker

The delivery date picker allows customers to select their preferred delivery date when ordering products. This feature is integrated throughout the WooCommerce flow.

## Overview

- **Product Page**: Customer selects delivery date before adding to cart (inside the add-to-cart form)
- **Cart Page**: Customer can edit the delivery date with styled picker
- **Checkout Page**: Delivery date is displayed (read-only) with calendar icon
- **Mini-cart Dropdown**: Delivery date shown with calendar icon
- **Order/Admin**: Delivery date is saved as order item meta and visible in admin and emails

## Technical Implementation

### Frontend (JavaScript)

**File:** `resources/scripts/delivery-date.js`

Uses [Flatpickr](https://flatpickr.js.org/) with Dutch localization, loaded via CDN.

The date picker is automatically initialized on any element with the class `.delivery-date-picker`.

**Configuration options via data attributes:**

| Attribute | Description | Default |
|-----------|-------------|---------|
| `data-min-days` | Minimum days ahead for delivery | `1` |
| `data-cart-item-key` | Cart item key (for cart page updates) | - |

**Date restrictions:**
- Minimum date: Tomorrow (or `data-min-days` days ahead)
- Sundays are disabled
- Format: `dd-mm-YYYY` (Dutch format)

### Backend (PHP)

**File:** `app/filters.php`

#### Hooks Used

| Hook | Purpose |
|------|---------|
| `woocommerce_add_cart_item_data` | Captures delivery date when adding to cart |
| `woocommerce_get_item_data` | Displays delivery date in cart/checkout |
| `woocommerce_update_cart_action_cart_updated` | Updates delivery date when cart is updated |
| `woocommerce_checkout_create_order_line_item` | Saves delivery date to order item meta |
| `woocommerce_order_item_get_formatted_meta_data` | Formats delivery date in order emails |
| `woocommerce_widget_cart_item_quantity` | Shows delivery date in mini-cart |

### Templates

| Template | Purpose |
|----------|---------|
| `views/woocommerce/cart/cart.blade.php` | Editable date picker per cart item |
| `views/woocommerce/checkout/review-order.blade.php` | Read-only date display |
| `views/header/cart-dropdown.blade.php` | Mini-cart with delivery date |

> **Note:** The product page date picker is injected via `woocommerce_before_add_to_cart_button` hook in `app/filters.php` to ensure it's inside the add-to-cart form.

## Customization

### Change Minimum Days Per Product

Set custom minimum days for a specific product using the `_delivery_min_days` post meta:

```php
update_post_meta($product_id, '_delivery_min_days', 3); // 3 days minimum
```

### Disable Specific Days

Edit `resources/js/app.js` to modify the `disable` array in Flatpickr config:

```javascript
disable: [
  // Disable Sundays
  function(date) {
    return date.getDay() === 0;
  },
  // Disable Saturdays too
  function(date) {
    return date.getDay() === 6;
  },
  // Disable specific dates
  "2024-12-25",
  "2024-12-26",
]
```

### Block Specific Date Ranges (Holidays)

```javascript
disable: [
  {
    from: "2024-12-24",
    to: "2024-12-26"
  }
]
```

### Change Date Format

Edit the `dateFormat` option in `resources/js/app.js`:

```javascript
flatpickr(picker, {
  dateFormat: 'd-m-Y', // Dutch format: 31-12-2024
  // Alternative formats:
  // dateFormat: 'Y-m-d', // ISO: 2024-12-31
  // dateFormat: 'd/m/Y', // Slash: 31/12/2024
});
```

> **Note:** If you change the format, also update the PHP date formatting in `delivery-date.blade.php`.

## Styling

**File:** `resources/css/app.css`

Flatpickr is styled to match the brand colors using CSS custom properties:

```css
.flatpickr-day.selected {
  background-color: var(--brand-600);
  border-color: var(--brand-600);
}
```

The calendar automatically adapts to the current brand (thuis/horeca/industrie).

## Data Flow

```
┌─────────────────┐
│  Product Page   │
│  (date picker)  │
└────────┬────────┘
         │ POST: delivery_date
         ▼
┌─────────────────┐
│   Add to Cart   │
│  (PHP filter)   │
└────────┬────────┘
         │ Stored in cart_item['delivery_date']
         ▼
┌─────────────────┐
│   Cart Page     │
│  (editable)     │
└────────┬────────┘
         │ POST: cart_delivery_date[cart_item_key]
         ▼
┌─────────────────┐
│    Checkout     │
│  (read-only)    │
└────────┬────────┘
         │
         ▼
┌─────────────────┐
│     Order       │
│  (item meta)    │
└─────────────────┘
```

## Troubleshooting

### Date picker not appearing

1. Check that `npm run build` was executed after changes
2. Verify Flatpickr is installed: `npm list flatpickr`
3. Check browser console for JavaScript errors

### Date not saving to cart

1. Ensure the form field `name="delivery_date"` exists in the product page template
2. Check that the product form submits via POST (not AJAX) or that the AJAX handler includes the field

### Date not updating in cart

1. Verify the hidden input `name="cart_delivery_date[{cart_item_key}]"` is present
2. Check that "Update cart" button is clicked after changing the date

### Wrong date format displayed

The system uses Dutch date format (`dd-mm-YYYY`). If you see different formats:

1. Check Flatpickr `dateFormat` setting in `app.js`
2. Check PHP date format in `delivery-date.blade.php`

## Dependencies

Currently loaded via CDN in `app/setup.php`:

```php
wp_enqueue_style('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css');
wp_enqueue_script('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr');
wp_enqueue_script('flatpickr-nl', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/nl.js');
wp_enqueue_script('delivery-date', get_template_directory_uri() . '/resources/scripts/delivery-date.js');
```

Scripts are only loaded on product, cart, and checkout pages.

## TODO

- [ ] Bundle Flatpickr locally instead of using CDN (npm package is installed but not yet bundled through Vite)
