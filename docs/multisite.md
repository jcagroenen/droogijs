# WordPress Multisite with Bedrock

## Setup Steps

1. Add to `.env`:
   ```
   WP_ALLOW_MULTISITE=true
   ```

2. Add to `config/application.php`:
   ```php
   Config::define('WP_ALLOW_MULTISITE', env('WP_ALLOW_MULTISITE') ?: false);
   ```

3. Visit Tools > Network Setup in WP Admin

4. After network creation, add to `config/application.php`:
   ```php
   Config::define('MULTISITE', true);
   Config::define('SUBDOMAIN_INSTALL', true);
   Config::define('DOMAIN_CURRENT_SITE', env('DOMAIN_CURRENT_SITE') ?: 'droogijs.test');
   Config::define('PATH_CURRENT_SITE', '/');
   Config::define('SITE_ID_CURRENT_SITE', 1);
   Config::define('BLOG_ID_CURRENT_SITE', 1);
   ```

5. Install URL fixer:
   ```bash
   composer require roots/multisite-url-fixer
   ```

## Required Package

`roots/multisite-url-fixer` - Fixes asset URLs in Bedrock's non-standard structure where `wp-content` is at `/app` instead of `/wp/wp-content`.

## Creating Sites

Network Admin > Sites > Add New

Sites created:
- thuis.droogijs.test (ID: 2)
- horeca.droogijs.test (ID: 3)
- industrie.droogijs.test (ID: 4)

## Theme Activation

Must activate theme separately on each site via Network Admin or per-site Appearance > Themes.
