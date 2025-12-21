# View Composers

## What They Do

Pass data to Blade views. Used for brand-specific content without ACF.

## Location

`app/View/Composers/`

## FrontPage Composer

`app/View/Composers/FrontPage.php`

Provides data for `front-page.blade.php`:
- `$hero` - Hero section content
- `$features` - Features section with icons
- `$useCases` - Use cases grid
- `$cta` - Call to action text

Uses PHP `match()` on `get_current_brand()` to return different content per brand.

## Example Structure

```php
class FrontPage extends Composer
{
    protected static $views = ['front-page'];

    public function with(): array
    {
        return [
            'hero' => $this->heroData(),
            'features' => $this->featuresData(),
        ];
    }

    public function heroData(): array
    {
        return match(get_current_brand()) {
            'thuis' => [
                'title' => 'Consumer title',
                'subtitle' => 'Consumer subtitle',
            ],
            'horeca' => [
                'title' => 'Horeca title',
                'subtitle' => 'Horeca subtitle',
            ],
            'industrie' => [
                'title' => 'Industry title',
                'subtitle' => 'Industry subtitle',
            ],
        };
    }
}
```

## Brand Detection

Defined in `app/filters.php`:

```php
function get_current_brand(): string
{
    if (!is_multisite()) return 'thuis';

    $brands = [
        1 => 'thuis',
        2 => 'thuis',
        3 => 'horeca',
        4 => 'industrie',
    ];

    return $brands[get_current_blog_id()] ?? 'thuis';
}
```

Also adds body class via filter:
```php
add_filter('body_class', fn($classes) => [...$classes, 'brand-' . get_current_brand()]);
```
