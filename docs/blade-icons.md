# Blade Icons

## Setup

Installed in the theme (not root):

```bash
cd web/app/themes/droogijs
composer require blade-ui-kit/blade-icons
wp acorn vendor:publish --tag=blade-icons
```

## Configuration

`config/blade-icons.php`:

```php
'sets' => [
    'default' => [
        'path' => 'resources/icons',
        'prefix' => 'icon',
    ],
],
```

## Icon Location

SVG files go in `resources/icons/`. No `.blade.php` extension needed - just raw `.svg` files.

## Usage in Blade

```blade
{{-- Directive syntax --}}
@svg('icon-arrow-right', 'w-5 h-5')

{{-- With attributes array --}}
@svg('icon-shield', ['class' => 'w-7 h-7', 'width' => '28', 'height' => '28'])

{{-- Component syntax --}}
<x-icon-arrow-right class="w-5 h-5" />
```

## Available Icons

```
resources/icons/
├── arrow-right.svg
├── award.svg
├── beaker.svg
├── box.svg
├── clock.svg
├── factory.svg
├── info-circle.svg
├── settings.svg
├── shield.svg
├── sparkles.svg
├── thermometer.svg
├── truck.svg
├── wine.svg
└── zap.svg
```

## Brand Logo

The logo is not a Blade Icon - it's a Blade partial at `partials/logo.blade.php` because it:
- Has text content
- Uses brand-specific colors
- Contains SVG animations
- Varies by brand (house/cocktail/container icons)

Usage:
```blade
@include('partials.logo', ['class' => 'h-12'])
```

## Production

Cache icons during deploy:

```bash
wp acorn icons:cache
```
