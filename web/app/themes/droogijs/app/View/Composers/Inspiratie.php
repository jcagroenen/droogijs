<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use WP_Query;
use function App\get_current_brand;

class Inspiratie extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'template-inspiratie',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with(): array
    {
        return [
            'title' => $this->title(),
            'subtitle' => $this->subtitle(),
            'posts' => $this->posts(),
        ];
    }

    /**
     * Page title per brand.
     */
    public function title(): string
    {
        return match(get_current_brand()) {
            'thuis' => 'Inspiratie voor thuis',
            'horeca' => 'Inspiratie voor horeca',
            'industrie' => 'Inspiratie voor industrie',
        };
    }

    /**
     * Page subtitle per brand.
     */
    public function subtitle(): string
    {
        return match(get_current_brand()) {
            'thuis' => 'Ontdek creatieve manieren om droogijs te gebruiken voor feesten, experimenten en speciale momenten thuis.',
            'horeca' => 'Ontdek hoe u droogijs kunt inzetten om uw gasten een onvergetelijke ervaring te bieden.',
            'industrie' => 'Praktische toepassingen en case studies voor industriële droogijs oplossingen.',
        };
    }

    /**
     * Get blog posts with size assignments for grid layout.
     */
    public function posts(): \Illuminate\Support\Collection
    {
        $query = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 12,
            'post_status' => 'publish',
        ]);

        $posts = collect($query->posts)->map(function ($post, $index) {
            // Assign sizes for visual variety: first and every 6th are large
            $size = match(true) {
                $index === 0 || $index === 5 => 'large',
                $index % 3 === 2 => 'small',
                default => 'medium',
            };

            // Get primary category
            $categories = get_the_category($post->ID);
            $category = !empty($categories) ? $categories[0]->name : 'Algemeen';

            // Get excerpt - strip HTML and limit length
            $excerpt = get_the_excerpt($post->ID);
            $excerpt = wp_strip_all_tags($excerpt);
            $excerpt = wp_trim_words($excerpt, 25, '...');

            return [
                'id' => $post->ID,
                'title' => get_the_title($post->ID),
                'excerpt' => $excerpt,
                'url' => get_permalink($post->ID),
                'image' => get_the_post_thumbnail_url($post->ID, 'large'),
                'category' => $category,
                'size' => $size,
            ];
        });

        wp_reset_postdata();

        return $posts;
    }
}
