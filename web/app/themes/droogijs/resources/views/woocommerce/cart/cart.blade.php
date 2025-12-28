<?php
/**
 * Cart Page
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.9.0
 */

defined('ABSPATH') || exit;
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
  <?php do_action('woocommerce_before_cart'); ?>

  <div class="mb-8">
    <h1 class="text-3xl md:text-4xl font-bold text-gray-900">Winkelwagen</h1>
    <p class="text-gray-600 mt-2">
      <?php
      $cart_count = WC()->cart->get_cart_contents_count();
      printf(_n('%d product', '%d producten', $cart_count, 'flavor'), $cart_count);
      ?>
    </p>
  </div>

  <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
    <?php do_action('woocommerce_before_cart_table'); ?>

    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
      {{-- Cart Items --}}
      <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
          {{-- Header --}}
          <div class="hidden md:grid md:grid-cols-12 gap-4 px-6 py-4 bg-gray-50 border-b border-gray-200 text-sm font-semibold text-gray-600">
            <div class="col-span-6">Product</div>
            <div class="col-span-2 text-center">Prijs</div>
            <div class="col-span-2 text-center">Aantal</div>
            <div class="col-span-2 text-right">Totaal</div>
          </div>

          <?php do_action('woocommerce_before_cart_contents'); ?>

          {{-- Cart Items List --}}
          <div class="divide-y divide-gray-200">
            <?php
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
              $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
              $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
              $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

              if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) {
                $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                ?>

                <div class="cart-item p-4 md:p-6 <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                  <div class="flex flex-col md:grid md:grid-cols-12 md:gap-4 md:items-center">
                    {{-- Product Image & Info --}}
                    <div class="flex gap-4 md:col-span-6">
                      {{-- Remove Button (Mobile: top right) --}}
                      <div class="md:hidden absolute right-4">
                        <?php
                        echo apply_filters(
                          'woocommerce_cart_item_remove_link',
                          sprintf(
                            '<a href="%s" class="text-gray-400 hover:text-red-500 transition-colors" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                              <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                              </svg>
                            </a>',
                            esc_url(wc_get_cart_remove_url($cart_item_key)),
                            esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                            esc_attr($product_id),
                            esc_attr($_product->get_sku())
                          ),
                          $cart_item_key
                        );
                        ?>
                      </div>

                      {{-- Thumbnail --}}
                      <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl bg-gray-100 overflow-hidden shrink-0">
                        <?php
                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail', ['class' => 'w-full h-full object-cover']), $cart_item, $cart_item_key);
                        if (!$product_permalink) {
                          echo $thumbnail;
                        } else {
                          printf('<a href="%s" class="block w-full h-full">%s</a>', esc_url($product_permalink), $thumbnail);
                        }
                        ?>
                      </div>

                      {{-- Product Name --}}
                      <div class="flex-1 min-w-0">
                        <h3 class="font-semibold text-gray-900 mb-1">
                          <?php
                          if (!$product_permalink) {
                            echo wp_kses_post($product_name);
                          } else {
                            echo wp_kses_post(sprintf('<a href="%s" class="hover:text-brand-600 transition-colors">%s</a>', esc_url($product_permalink), $_product->get_name()));
                          }
                          ?>
                        </h3>

                        <?php
                        do_action('woocommerce_after_cart_item_name', $cart_item, $cart_item_key);

                        // Always show delivery date picker - use stored date or calculate default
                        $delivery_date = $cart_item['delivery_date'] ?? '';
                        if (empty($delivery_date)) {
                          // Calculate default date (tomorrow, skip Sunday)
                          $default_date = new DateTime();
                          $default_date->modify('+1 day');
                          if ($default_date->format('w') == 0) {
                            $default_date->modify('+1 day');
                          }
                          $delivery_date = $default_date->format('d-m-Y');
                        }
                        ?>
                        <div class="mt-2 flex items-center gap-2">
                          <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                          </svg>
                          <span class="text-sm text-brand-600 font-medium">Levering:</span>
                          <input
                            type="text"
                            name="cart_delivery_date[<?php echo esc_attr($cart_item_key); ?>]"
                            class="delivery-date-picker text-sm px-2 py-1 border border-brand-300 rounded cursor-pointer bg-brand-50 text-brand-700 font-medium max-w-[110px]"
                            data-cart-item-key="<?php echo esc_attr($cart_item_key); ?>"
                            data-min-days="1"
                            value="<?php echo esc_attr($delivery_date); ?>"
                          />
                        </div>
                        <?php
                        // Don't show wc_get_formatted_cart_item_data - we display delivery date ourselves

                        if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                          echo '<p class="text-sm text-amber-600 mt-1">' . esc_html__('Available on backorder', 'woocommerce') . '</p>';
                        }
                        ?>

                        {{-- Remove Button (Desktop) --}}
                        <div class="hidden md:block mt-2">
                          <?php
                          echo apply_filters(
                            'woocommerce_cart_item_remove_link',
                            sprintf(
                              '<a href="%s" class="text-sm text-gray-500 hover:text-red-500 transition-colors inline-flex items-center gap-1" aria-label="%s" data-product_id="%s" data-product_sku="%s">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                Verwijderen
                              </a>',
                              esc_url(wc_get_cart_remove_url($cart_item_key)),
                              esc_attr(sprintf(__('Remove %s from cart', 'woocommerce'), wp_strip_all_tags($product_name))),
                              esc_attr($product_id),
                              esc_attr($_product->get_sku())
                            ),
                            $cart_item_key
                          );
                          ?>
                        </div>
                      </div>
                    </div>

                    {{-- Price --}}
                    <div class="hidden md:block md:col-span-2 text-center text-gray-600">
                      <?php
                      echo apply_filters('woocommerce_cart_item_price', WC()->cart->get_product_price($_product), $cart_item, $cart_item_key);
                      ?>
                    </div>

                    {{-- Quantity --}}
                    <div class="mt-4 md:mt-0 md:col-span-2">
                      <div class="flex items-center justify-between md:justify-center">
                        <span class="text-sm text-gray-500 md:hidden">Aantal:</span>
                        <?php
                        if ($_product->is_sold_individually()) {
                          $min_quantity = 1;
                          $max_quantity = 1;
                        } else {
                          $min_quantity = 0;
                          $max_quantity = $_product->get_max_purchase_quantity();
                        }

                        $product_quantity = woocommerce_quantity_input(
                          array(
                            'input_name' => "cart[{$cart_item_key}][qty]",
                            'input_value' => $cart_item['quantity'],
                            'max_value' => $max_quantity,
                            'min_value' => $min_quantity,
                            'product_name' => $product_name,
                            'classes' => 'w-20 text-center border border-gray-300 rounded-lg py-2 px-3 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500',
                          ),
                          $_product,
                          false
                        );

                        echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                        ?>
                      </div>
                    </div>

                    {{-- Subtotal --}}
                    <div class="mt-4 md:mt-0 md:col-span-2 flex items-center justify-between md:justify-end">
                      <span class="text-sm text-gray-500 md:hidden">Totaal:</span>
                      <span class="font-semibold text-gray-900">
                        <?php
                        echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                        ?>
                      </span>
                    </div>
                  </div>
                </div>

                <?php
              }
            }
            ?>
          </div>

          <?php do_action('woocommerce_cart_contents'); ?>
        </div>

        {{-- Coupon & Update Cart --}}
        <div class="mt-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          {{-- Coupon --}}
          <?php if (wc_coupons_enabled()) : ?>
            <div x-data="{ open: false }" class="flex-1">
              <button
                type="button"
                x-on:click="open = !open"
                class="text-brand-600 hover:text-brand-700 font-medium text-sm inline-flex items-center gap-1"
              >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                </svg>
                <span x-text="open ? 'Verberg' : 'Kortingscode toevoegen'"></span>
              </button>

              <div x-show="open" x-cloak x-transition class="mt-3 flex gap-2">
                <input
                  type="text"
                  name="coupon_code"
                  id="coupon_code"
                  placeholder="Kortingscode"
                  class="flex-1 border border-gray-300 rounded-lg py-2 px-4 focus:outline-none focus:ring-2 focus:ring-brand-500 focus:border-brand-500"
                />
                <button
                  type="submit"
                  name="apply_coupon"
                  class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors"
                  value="<?php esc_attr_e('Apply coupon', 'woocommerce'); ?>"
                >
                  Toepassen
                </button>
              </div>
              <?php do_action('woocommerce_cart_coupon'); ?>
            </div>
          <?php endif; ?>

          {{-- Update Cart Button --}}
          <button
            type="submit"
            name="update_cart"
            class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors inline-flex items-center gap-2"
            value="<?php esc_attr_e('Update cart', 'woocommerce'); ?>"
          >
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
            </svg>
            Winkelwagen bijwerken
          </button>

          <?php do_action('woocommerce_cart_actions'); ?>
          <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
        </div>

        <?php do_action('woocommerce_after_cart_contents'); ?>
        <?php do_action('woocommerce_after_cart_table'); ?>
      </div>

      {{-- Cart Totals Sidebar --}}
      <div class="mt-8 lg:mt-0">
        <?php do_action('woocommerce_before_cart_collaterals'); ?>

        <div class="cart-collaterals sticky top-28">
          <?php
          /**
           * Cart collaterals hook.
           *
           * @hooked woocommerce_cross_sell_display
           * @hooked woocommerce_cart_totals - 10
           */
          do_action('woocommerce_cart_collaterals');
          ?>
        </div>
      </div>
    </div>
  </form>

  <?php do_action('woocommerce_after_cart'); ?>
</div>
