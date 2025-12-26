# Deployment Guide (Bedrock + Sage Multisite on Ploi)

Complete guide for deploying WordPress multisite with Bedrock and Sage to Ploi.

## Prerequisites

- Ploi account with server
- Domain pointed to server
- SSH access
- Git repository (GitHub/GitLab)

## 1. Ploi Site Setup

1. Create new site in Ploi with your domain (e.g., `droogijs.groenen-webdev.nl`)
2. Connect to your Git repository
3. Set web directory to `/current/web` (Deployer uses releases)

## 2. Deployer Configuration

`deploy.php` in project root:

```php
<?php
namespace Deployer;

require 'recipe/wordpress.php';

set('repository', 'git@github.com:username/repo.git');

add('shared_files', ['.env']);
add('shared_dirs', []);

set('shared_dirs', [
    'web/app/uploads',
    'web/app/uploads-webpc'
]);

set('writable_dirs', [
    'web/app/uploads',
    'web/app/uploads-webpc'
]);

host('droogijs.groenen-webdev.nl')
    ->set('remote_user', 'username')
    ->set('deploy_path', '/home/username/droogijs.groenen-webdev.nl')
    ->set('branch', 'main');

set('keep_releases', 3);

after('deploy:failed', 'deploy:unlock');

task('deploy:theme:composer-install', function () {
    run('cd {{release_path}}/web/app/themes/droogijs && composer install --no-dev --optimize-autoloader');
});

task('deploy:theme:build', function () {
    run('cd {{release_path}}/web/app/themes/droogijs && npm ci && npm run build');
});

desc('Deploys project');
task('deploy', [
    'deploy:prepare',
    'deploy:vendors',
    'deploy:theme:composer-install',
    'deploy:theme:build',
    'deploy:publish',
]);
```

## 3. Environment Configuration (.env on server)

Create `.env` in the `shared` folder on the server:

```env
DB_NAME='database_name'
DB_USER='database_user'
DB_PASSWORD='database_password'
DB_HOST='127.0.0.1'
DB_PREFIX='wp_'

WP_ENV='staging'
WP_HOME='https://droogijs.groenen-webdev.nl'
WP_SITEURL="${WP_HOME}/wp"

# IMPORTANT: Set this for multisite
DOMAIN_CURRENT_SITE='droogijs.groenen-webdev.nl'

# Generate at https://roots.io/salts.html
AUTH_KEY='...'
SECURE_AUTH_KEY='...'
LOGGED_IN_KEY='...'
NONCE_KEY='...'
AUTH_SALT='...'
SECURE_AUTH_SALT='...'
LOGGED_IN_SALT='...'
NONCE_SALT='...'
```

## 4. Multisite Installation (Fresh Database)

### Problem: Redirect loop on fresh install

WordPress multisite constants in `config/application.php` cause redirect loops when the database is empty.

### Solution:

1. **Temporarily comment out multisite constants** in `config/application.php` on the server:

```php
// Config::define('MULTISITE', true);
// Config::define('SUBDOMAIN_INSTALL', true);
// Config::define('DOMAIN_CURRENT_SITE', env('DOMAIN_CURRENT_SITE') ?: 'droogijs.test');
// Config::define('PATH_CURRENT_SITE', '/');
// Config::define('SITE_ID_CURRENT_SITE', 1);
// Config::define('BLOG_ID_CURRENT_SITE', 1);
```

2. Visit `https://yourdomain.com/wp/wp-admin/install.php` and run the installer

3. Log in to wp-admin, go to **Tools → Network Setup**

4. Choose **Sub-domains** (for domain-based multisite)

5. Complete the wizard - it shows code to add to wp-config.php (ignore this, we use Bedrock)

6. **Uncomment the multisite constants** in `config/application.php`

7. **Uncomment `DOMAIN_CURRENT_SITE`** in `.env`

8. Click "Log In" - multisite should now be active

## 5. DNS Configuration (Subdomains)

For subdomain multisite, add DNS records:

**Option A: Wildcard (recommended)**
```
*.droogijs    A    your-server-ip
```

**Option B: Individual records**
```
thuis.droogijs      A    your-server-ip
horeca.droogijs     A    your-server-ip
industrie.droogijs  A    your-server-ip
```

## 6. SSL Certificate (Ploi)

### Problem: SSL doesn't cover subdomains

The default certificate only covers the main domain.

### Solution:

1. Go to Ploi → Site → **SSL**
2. **Delete the existing certificate** first (prevents conflicts)
3. Add **Site Aliases** for all subdomains in Ploi
4. Request new certificate with all domains (comma-separated, no spaces):

```
droogijs.groenen-webdev.nl,thuis.droogijs.groenen-webdev.nl,horeca.droogijs.groenen-webdev.nl,industrie.droogijs.groenen-webdev.nl
```

### Common SSL Error: "empty label"

This means there was an extra comma or space in the domain list. Clean it up and try again.

### Common SSL Error: nginx config test failed

The old certificate is conflicting. Delete it first, then request the new one.

## 7. Create Multisite Sites

1. Go to **Network Admin → Sites → Add New**
2. Create each site with subdomain:
   - `thuis.droogijs.groenen-webdev.nl`
   - `horeca.droogijs.groenen-webdev.nl`
   - `industrie.droogijs.groenen-webdev.nl`

## 8. Theme & Plugin Activation

1. **Network Admin → Themes** → Network Enable your theme
2. **Network Admin → Plugins** → Network Activate WooCommerce
3. Visit each site's dashboard to activate the theme

## 9. Debugging on Staging

Add to `config/environments/staging.php`:

```php
Config::define('WP_DEBUG', true);
Config::define('WP_DEBUG_DISPLAY', true);
ini_set('display_errors', '1');
```

This shows PHP errors directly in the browser (like locally).

## Common Errors & Solutions

### "Call to undefined function wc_get_page_permalink()"

WooCommerce is not activated. Network Activate it in Plugins.

For templates, always check if WooCommerce is active:
```php
{{ function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '/shop/' }}
```

### "Call to undefined function get_current_brand()"

Use the namespaced function in Blade templates:
```php
@php $brand = \App\get_current_brand(); @endphp
```

### Redirect to wp-signup.php

The site doesn't exist in `wp_blogs` table. Create it in Network Admin → Sites.

### "You do not have permissions to install plugins"

This is normal - `DISALLOW_FILE_MODS` is `true` in Bedrock (correct for staging/production). Manage plugins via Composer.

### DOMAIN_CURRENT_SITE redirect loop

The `.env` is missing `DOMAIN_CURRENT_SITE` or it's commented out. Uncomment and set the correct domain.

## Environment-Specific Config

Bedrock loads config based on `WP_ENV`:

- `development` → `config/environments/development.php`
- `staging` → `config/environments/staging.php`
- `production` → `config/environments/production.php`

Local `.env` has `WP_ENV='development'`, server has `WP_ENV='staging'` or `'production'`.

## Deployment Commands

```bash
# Deploy to server
dep deploy

# Deploy with verbose output
dep deploy -vvv

# Rollback to previous release
dep rollback
```

## Production Domains

When going to production, update:

1. DNS for production domains (`droogijsvoorthuis.nl`, etc.)
2. `.env` with `WP_ENV='production'` and correct `WP_HOME`
3. SSL certificate for production domains
4. Domain mapping in WordPress if needed
