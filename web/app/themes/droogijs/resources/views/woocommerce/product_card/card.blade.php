{{--
  Product Card Component
  Main wrapper for product cards in the shop grid
--}}

@php
  global $product;

  if (empty($product) || !$product->is_visible()) {
    return;
  }

  $productId = $product->get_id();
  $productName = $product->get_name();
  $productUrl = get_permalink($productId);
  $productImage = wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail');
  $price = $product->get_price_html();
  $onSale = $product->is_on_sale();
  $stockStatus = $product->get_stock_status();
@endphp

<li class="group bg-white rounded-2xl border-2 border-gray-200 hover:border-brand-300 transition-all duration-300 hover:shadow-xl overflow-hidden flex flex-col">
  {{-- Product Image --}}
  @include('woocommerce.product_card.image', [
    'product' => $product,
    'productUrl' => $productUrl,
    'productImage' => $productImage,
    'productName' => $productName,
    'onSale' => $onSale,
  ])

  {{-- Product Info --}}
  @include('woocommerce.product_card.info', [
    'product' => $product,
    'productUrl' => $productUrl,
    'productName' => $productName,
  ])

  {{-- Add to Cart Actions --}}
  @include('woocommerce.product_card.actions', [
    'product' => $product,
    'price' => $price,
  ])
</li>
