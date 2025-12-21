# Inspiratie (Blog) Page

## Overview

The "Inspiratie" page serves as the blog archive. Uses a page template instead of the default posts archive to get `/inspiratie/` URL.

## Setup

1. Create a page called "Inspiratie" in WP Admin
2. Set template to "Inspiratie" in Page Attributes
3. Make sure it's NOT set as the Posts Page in Settings > Reading

## Files

| File | Purpose |
|------|---------|
| `template-inspiratie.blade.php` | Page template |
| `partials/blog-card.blade.php` | Blog card component |
| `View/Composers/Inspiratie.php` | Fetches posts, provides data |

## View Composer

`Inspiratie.php` provides:

- `$title` - Brand-specific page title
- `$subtitle` - Brand-specific description
- `$posts` - Collection of posts with:
  - `id`, `title`, `excerpt`, `url`, `image`, `category`, `size`

Posts are assigned sizes for grid layout:
- `large` (2x2) - index 0 and 5
- `small` (1x1) - every 3rd item
- `medium` (1x1) - default

## Blog Card

The `blog-card.blade.php` partial renders each post card with:

- Featured image (or brand-colored fallback)
- Category badge
- Title
- Excerpt (stripped HTML, 25 words max)
- "Lees meer" link

Sizes control grid span and min-height:
- `large`: `md:col-span-2 md:row-span-2 min-h-[500px]`
- `medium`: `md:col-span-1 md:row-span-1 min-h-[320px]`
- `small`: `md:col-span-1 md:row-span-1 min-h-[280px]`

## Use-Cases Section (Homepage)

The use-cases section on the front page also pulls from blog posts:

- Fetches latest 4 posts
- Shows featured image if available
- Links to actual post
- FrontPage composer handles the query

## URLs

- `/inspiratie/` - Blog archive (Inspiratie page)
- `/post-slug/` - Individual posts (single.blade.php)
