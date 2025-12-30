# Flat Permalinks

Removes `/product/` and `/product-category/` prefixes from WooCommerce URLs, giving a Magento-style flat URL structure.

## URL Structure

| Before | After |
|--------|-------|
| `/product/droogijs-dry-ice-6-kg/` | `/droogijs-dry-ice-6-kg/` |
| `/product-category/droogijs/` | `/droogijs/` |
| `/shop/` | Disabled (redirect to homepage or category) |

## Required WordPress Settings

These settings are **automatically configured** by the `FlatPermalinks` class on every page load. You don't need to set them manually.

### Settings → Permalinks

| Setting | Value | Auto-set |
|---------|-------|----------|
| Permalink structure | **Post name** (`/%postname%/`) | Yes |

### WooCommerce Permalinks (same page, scroll down)

| Setting | Value | Auto-set |
|---------|-------|----------|
| Product category base | `product-category` (default) | Yes |
| Product permalinks | **Custom base** with `product/` | Yes |

The `ensureCorrectSettings()` method checks and fixes these settings on every `init` action. If settings are changed, rewrite rules are automatically flushed.

### Branch Switching

When switching branches:
- **To flat-permalinks branch:** Settings auto-configure, URLs work immediately
- **Back to main branch:** Import your database backup from `branch_dbs/main/` to restore original settings

## How It Works

### 1. URL Generation (`post_type_link`, `term_link` filters)

When WordPress generates a product or category URL, the class strips the base:

```
/product/my-product/ → /my-product/
/product-category/my-category/ → /my-category/
```

### 2. Request Handling (`request` filter, `wp` action)

When a request comes in for `/my-product/`:

1. WordPress initially thinks it's a post (because of `/%postname%/` structure)
2. `handleRequest()` checks if a product exists with that slug
3. `maybeRedirectToProduct()` sets up the WP_Query for the product
4. `maybeForceProductTemplate()` loads the correct Blade template

### 3. Old URL Redirects (`template_redirect` action)

Requests to old URLs are 301 redirected:

```
/product/my-product/ → /my-product/ (301)
/product-category/my-category/ → /my-category/ (301)
```

### 4. Slug Collision Prevention

Prevents products and categories from having the same slug:

- Creating a product with slug `droogijs` when category `droogijs` exists → renamed to `droogijs-product`
- Creating a category with slug `my-product` when product `my-product` exists → renamed to `my-product-category`
- Admin notice shown in Dutch: "Slug conflict: De slug X is al in gebruik..."

## File Location

```
web/app/themes/droogijs/app/WooCommerce/FlatPermalinks.php
```

Registered in `app/Providers/ThemeServiceProvider.php`.

## Class Methods

### URL Rewriting

| Method | Hook | Purpose |
|--------|------|---------|
| `removeProductBase()` | `post_type_link` | Strips `/product/` from product URLs |
| `removeCategoryBase()` | `term_link` | Strips `/product-category/` from category URLs |
| `addRewriteRules()` | `init` | Registers rewrite rules for categories |
| `handleRequest()` | `request` | Routes flat slugs to correct post type |
| `maybeRedirectToProduct()` | `wp` | Sets up WP_Query for flat product URLs |
| `maybeForceProductTemplate()` | `template_include` | Loads Blade single-product template |
| `redirectOldUrls()` | `template_redirect` | 301 redirects old URLs to flat URLs |

### Slug Collision

| Method | Hook | Purpose |
|--------|------|---------|
| `preventSlugCollision()` | `wp_unique_post_slug` | Prevents product slugs matching categories/pages |
| `checkCategorySlugOnCreate()` | `created_product_cat` | Checks new category slugs |
| `checkCategorySlugOnEdit()` | `edited_product_cat` | Checks edited category slugs |
| `validateCategorySlug()` | (internal) | Renames conflicting category slugs |
| `showSlugCollisionNotice()` | `admin_notices` | Shows warning when slug was renamed |

### Utilities

| Method | Purpose |
|--------|---------|
| `flushRules()` | Manually flush rewrite rules (use after config changes) |

## Flushing Rewrite Rules

After any permalink-related changes, flush rewrite rules:

**Option 1:** Visit Settings → Permalinks and click "Save Changes"

**Option 2:** WP-CLI
```bash
wp rewrite flush
```

**Option 3:** Code (for migrations/deployments)
```php
app(\App\WooCommerce\FlatPermalinks::class)->flushRules();
```

## Troubleshooting

### Products showing 404

1. Check permalink settings match the required configuration above
2. Flush rewrite rules
3. Verify product status is "publish"
4. Check for slug conflicts with pages or categories

### Category pages not working

1. Flush rewrite rules after creating new categories
2. Categories need rewrite rules registered - happens on `init`

### Old URLs not redirecting

The redirect only works for exact matches. Nested paths like `/product/category/product/` won't redirect.

### Slug collision notice not showing

Transients are used - notice only shows once per collision. Check if the slug was actually renamed in the database.

## Multisite Considerations

Each site in the multisite has its own:
- Permalink settings (stored in `wp_X_options`)
- Products and categories
- Rewrite rules

The FlatPermalinks class works per-site automatically.

## Performance Notes

- `get_page_by_path()` is called on every frontend request to check if slug is a product
- WordPress caches this query, so impact is minimal
- Category rewrite rules are registered on every `init` - consider caching if you have many categories

## Auto-Configuration

The `FlatPermalinks` class automatically ensures correct settings via `ensureCorrectSettings()`:

```php
protected const REQUIRED_PERMALINK_STRUCTURE = '/%postname%/';

protected const REQUIRED_WOOCOMMERCE_PERMALINKS = [
    'product_base' => 'product',
    'category_base' => 'product-category',
    'attribute_base' => '',
    'use_verbose_page_rules' => false,
];

public function ensureCorrectSettings(): void
{
    // Checks and fixes settings on every init
    // Flushes rewrite rules if settings changed
}
```

This means:
- Settings auto-correct if someone changes them in the admin
- Switching to this branch automatically configures permalinks
- No manual setup required

## Disabling Flat Permalinks

To disable without removing the code:

1. Remove from `ThemeServiceProvider.php`:
   ```php
   // $this->app->singleton(FlatPermalinks::class);
   // $this->app->make(FlatPermalinks::class)->boot();
   ```

2. Flush rewrite rules

3. Old flat URLs will 404 - consider adding redirects if site was live
