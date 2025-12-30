<?php

namespace App\WooCommerce;

/**
 * Flat Permalinks for WooCommerce
 *
 * Removes /product/ and /product-category/ prefixes from URLs.
 * Also prevents slug collisions between products, categories, and pages.
 *
 * Result:
 * - /droogijs/ → Category page
 * - /droogijs-dry-ice-6-kg/ → Product page
 */
class FlatPermalinks
{
    /**
     * Required permalink settings for flat URLs to work.
     */
    protected const REQUIRED_PERMALINK_STRUCTURE = '/%postname%/';

    protected const REQUIRED_WOOCOMMERCE_PERMALINKS = [
        'product_base' => 'product',
        'category_base' => 'product-category',
        'attribute_base' => '',
        'use_verbose_page_rules' => false,
    ];

    /**
     * Bootstrap the flat permalinks functionality.
     */
    public function boot(): void
    {
        // Auto-configure permalink settings on init
        add_action('init', [$this, 'ensureCorrectSettings'], 1);

        // URL Rewriting
        add_filter('post_type_link', [$this, 'removeProductBase'], 10, 2);
        add_filter('term_link', [$this, 'removeCategoryBase'], 10, 3);
        add_action('init', [$this, 'addRewriteRules'], 99);
        add_filter('request', [$this, 'handleRequest']);

        // Intercept at wp action to handle flat product URLs
        add_action('wp', [$this, 'maybeRedirectToProduct'], 1);

        // Force the correct template - run before sage-woocommerce (priority 10)
        add_filter('template_include', [$this, 'maybeForceProductTemplate'], 5);

        // Slug Collision Prevention
        add_filter('wp_unique_post_slug', [$this, 'preventSlugCollision'], 10, 6);
        add_action('created_product_cat', [$this, 'checkCategorySlugOnCreate'], 10, 2);
        add_action('edited_product_cat', [$this, 'checkCategorySlugOnEdit'], 10, 2);
        add_action('admin_notices', [$this, 'showSlugCollisionNotice']);

        // Redirect old URLs to new flat URLs
        add_action('template_redirect', [$this, 'redirectOldUrls']);
    }

    /**
     * Ensure permalink settings are correct for flat URLs.
     * Automatically fixes settings if they're wrong.
     */
    public function ensureCorrectSettings(): void
    {
        $needs_flush = false;

        // Check WordPress permalink structure
        $current_structure = get_option('permalink_structure');
        if ($current_structure !== self::REQUIRED_PERMALINK_STRUCTURE) {
            update_option('permalink_structure', self::REQUIRED_PERMALINK_STRUCTURE);
            $needs_flush = true;
        }

        // Check WooCommerce permalink settings
        $current_wc = get_option('woocommerce_permalinks', []);
        if ($current_wc !== self::REQUIRED_WOOCOMMERCE_PERMALINKS) {
            update_option('woocommerce_permalinks', self::REQUIRED_WOOCOMMERCE_PERMALINKS);
            $needs_flush = true;
        }

        // Flush rewrite rules if settings changed
        if ($needs_flush) {
            // Use a transient to flush rules only once after settings change
            if (! get_transient('flat_permalinks_flushed')) {
                flush_rewrite_rules();
                set_transient('flat_permalinks_flushed', true, 60);
            }
        }
    }

    /**
     * Remove /product/ from product URLs.
     */
    public function removeProductBase(string $permalink, \WP_Post $post): string
    {
        if ($post->post_type !== 'product') {
            return $permalink;
        }

        return str_replace('/product/', '/', $permalink);
    }

    /**
     * Remove /product-category/ from category URLs.
     */
    public function removeCategoryBase(string $termlink, \WP_Term $term, string $taxonomy): string
    {
        if ($taxonomy !== 'product_cat') {
            return $termlink;
        }

        return str_replace('/product-category/', '/', $termlink);
    }

    /**
     * Add rewrite rules to handle flat URLs.
     */
    public function addRewriteRules(): void
    {
        // Get all product category slugs
        $categories = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
            'fields' => 'slugs',
        ]);

        if (! is_wp_error($categories) && ! empty($categories)) {
            foreach ($categories as $slug) {
                // Category archive: /category-slug/
                add_rewrite_rule(
                    '^' . preg_quote($slug) . '/?$',
                    'index.php?product_cat=' . $slug,
                    'top'
                );

                // Category pagination: /category-slug/page/2/
                add_rewrite_rule(
                    '^' . preg_quote($slug) . '/page/([0-9]+)/?$',
                    'index.php?product_cat=' . $slug . '&paged=$matches[1]',
                    'top'
                );
            }
        }

        // Products: Match any slug that's a product
        // This is a catch-all, so it goes after category rules
        add_rewrite_rule(
            '^([^/]+)/?$',
            'index.php?product=$matches[1]',
            'bottom'
        );
    }

    /**
     * Handle the request to determine if it's a product or let WordPress handle it.
     */
    public function handleRequest(array $query_vars): array
    {
        // Skip if we already have a specific query
        if (isset($query_vars['product_cat']) || isset($query_vars['pagename'])) {
            return $query_vars;
        }

        // Check if this is a 'name' query (WordPress post slug) - might be a product
        if (isset($query_vars['name']) && ! isset($query_vars['post_type'])) {
            $slug = $query_vars['name'];

            // Check if it's actually a product
            $product = get_page_by_path($slug, OBJECT, 'product');

            if ($product && $product->post_status === 'publish') {
                // It's a valid product - switch to product query
                unset($query_vars['name']);
                $query_vars['product'] = $slug;
                $query_vars['post_type'] = 'product';
                return $query_vars;
            }
        }

        // If we have a product query var, verify it exists
        if (isset($query_vars['product'])) {
            $slug = $query_vars['product'];

            // Check if it's actually a product
            $product = get_page_by_path($slug, OBJECT, 'product');

            if ($product && $product->post_status === 'publish') {
                // It's a valid product - use 'product' query var which WooCommerce expects
                $query_vars['product'] = $slug;
                $query_vars['post_type'] = 'product';
                return $query_vars;
            }

            // Not a product, remove the query var so WordPress can try other matches
            unset($query_vars['product']);

            // Check if it's a page
            $page = get_page_by_path($slug);
            if ($page) {
                $query_vars['pagename'] = $slug;
            }
        }

        return $query_vars;
    }

    /**
     * Check if this is a flat product URL and internally redirect to proper product URL.
     * This runs after WP has parsed the request but before template is loaded.
     */
    public function maybeRedirectToProduct(): void
    {
        // Only on frontend
        if (is_admin()) {
            return;
        }

        global $wp_query, $wp, $post;

        // Get the request path (without query string)
        $request = trim($wp->request, '/');

        // Skip if empty or has slashes (nested path)
        if (empty($request) || strpos($request, '/') !== false) {
            return;
        }

        // Check if it's a product
        $product_post = get_page_by_path($request, OBJECT, 'product');

        if ($product_post && $product_post->post_status === 'publish') {
            // Store for template_include filter
            $GLOBALS['flat_permalink_product_id'] = $product_post->ID;

            // Reset and set up the main query for this product
            $wp_query->init();
            $wp_query->query([
                'post_type' => 'product',
                'p' => $product_post->ID,
                'post_status' => 'publish',
            ]);

            // Set global post
            $post = $product_post;
            setup_postdata($post);

            // Set WooCommerce product global
            $GLOBALS['product'] = wc_get_product($product_post->ID);
        }
    }

    /**
     * Force single-product template when we've set up a flat product URL.
     */
    public function maybeForceProductTemplate(string $template): string
    {
        if (! isset($GLOBALS['flat_permalink_product_id'])) {
            return $template;
        }

        // Check if this is already a single-product template
        if (strpos($template, 'single-product') !== false) {
            return $template;
        }

        // Look for the blade template and create a loader
        // This mimics what sage-woocommerce does
        $blade_template = get_theme_file_path('resources/views/woocommerce/single-product.blade.php');

        if (file_exists($blade_template)) {
            // Use Roots view to create a loader
            return \Roots\view('woocommerce.single-product')->makeLoader();
        }

        // Fallback to WooCommerce plugin template
        return WC()->plugin_path() . '/templates/single-product.php';
    }

    /**
     * Redirect old /product/ and /product-category/ URLs to flat URLs.
     */
    public function redirectOldUrls(): void
    {
        global $wp;

        // Redirect /product/slug/ to /slug/
        if (preg_match('#^product/([^/]+)/?$#', $wp->request, $matches)) {
            $product = get_page_by_path($matches[1], OBJECT, 'product');
            if ($product) {
                wp_redirect(home_url('/' . $matches[1] . '/'), 301);
                exit;
            }
        }

        // Redirect /product-category/slug/ to /slug/
        if (preg_match('#^product-category/([^/]+)/?$#', $wp->request, $matches)) {
            $term = get_term_by('slug', $matches[1], 'product_cat');
            if ($term) {
                wp_redirect(home_url('/' . $matches[1] . '/'), 301);
                exit;
            }
        }
    }

    /**
     * Prevent products from using slugs that belong to categories or pages.
     */
    public function preventSlugCollision(
        string $slug,
        int $post_ID,
        string $post_status,
        string $post_type,
        int $post_parent,
        string $original_slug
    ): string {
        if ($post_type !== 'product') {
            return $slug;
        }

        // Check if slug exists as a product category
        $category = get_term_by('slug', $slug, 'product_cat');
        if ($category) {
            // Store notice for admin
            set_transient('flat_permalinks_slug_collision_' . get_current_user_id(), [
                'type' => 'category',
                'slug' => $slug,
                'name' => $category->name,
            ], 30);

            return $slug . '-product';
        }

        // Check if slug exists as a page
        $page = get_page_by_path($slug);
        if ($page && $page->ID !== $post_ID) {
            set_transient('flat_permalinks_slug_collision_' . get_current_user_id(), [
                'type' => 'page',
                'slug' => $slug,
                'name' => $page->post_title,
            ], 30);

            return $slug . '-product';
        }

        return $slug;
    }

    /**
     * Check category slug on creation.
     */
    public function checkCategorySlugOnCreate(int $term_id, int $tt_id): void
    {
        $this->validateCategorySlug($term_id);
    }

    /**
     * Check category slug on edit.
     */
    public function checkCategorySlugOnEdit(int $term_id, int $tt_id): void
    {
        $this->validateCategorySlug($term_id);
    }

    /**
     * Validate that a category slug doesn't conflict with products or pages.
     */
    protected function validateCategorySlug(int $term_id): void
    {
        $term = get_term($term_id, 'product_cat');
        if (! $term || is_wp_error($term)) {
            return;
        }

        $slug = $term->slug;

        // Check if slug exists as a product
        $product = get_page_by_path($slug, OBJECT, 'product');
        if ($product) {
            // Update the term slug
            wp_update_term($term_id, 'product_cat', [
                'slug' => $slug . '-category',
            ]);

            set_transient('flat_permalinks_slug_collision_' . get_current_user_id(), [
                'type' => 'product',
                'slug' => $slug,
                'name' => $product->post_title,
            ], 30);
        }

        // Check if slug exists as a page
        $page = get_page_by_path($slug);
        if ($page) {
            wp_update_term($term_id, 'product_cat', [
                'slug' => $slug . '-category',
            ]);

            set_transient('flat_permalinks_slug_collision_' . get_current_user_id(), [
                'type' => 'page',
                'slug' => $slug,
                'name' => $page->post_title,
            ], 30);
        }
    }

    /**
     * Show admin notice when a slug collision was detected.
     */
    public function showSlugCollisionNotice(): void
    {
        $collision = get_transient('flat_permalinks_slug_collision_' . get_current_user_id());

        if (! $collision) {
            return;
        }

        delete_transient('flat_permalinks_slug_collision_' . get_current_user_id());

        $type_labels = [
            'category' => 'categorie',
            'product' => 'product',
            'page' => 'pagina',
        ];

        $type_label = $type_labels[$collision['type']] ?? $collision['type'];

        printf(
            '<div class="notice notice-warning is-dismissible"><p><strong>Slug conflict:</strong> De slug "%s" is al in gebruik door de %s "%s". De slug is automatisch aangepast.</p></div>',
            esc_html($collision['slug']),
            esc_html($type_label),
            esc_html($collision['name'])
        );
    }

    /**
     * Flush rewrite rules (call once after activating).
     * Can be triggered via: wp acorn tinker -> app(App\WooCommerce\FlatPermalinks::class)->flushRules()
     */
    public function flushRules(): void
    {
        $this->addRewriteRules();
        flush_rewrite_rules();
    }
}
