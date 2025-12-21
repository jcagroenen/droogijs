<?php

namespace App\View\Composers;

use Roots\Acorn\View\Composer;
use function App\get_current_brand;

class FrontPage extends Composer
{
    /**
     * List of views served by this composer.
     *
     * @var array
     */
    protected static $views = [
        'front-page',
    ];

    /**
     * Data to be passed to view before rendering.
     *
     * @return array
     */
    public function with(): array
    {
        return [
            'brand' => get_current_brand(),
            'hero' => $this->heroData(),
            'features' => $this->featuresData(),
            'useCases' => $this->useCasesData(),
            'cta' => $this->ctaData(),
        ];
    }

    /**
     * Hero section data per brand.
     */
    public function heroData(): array
    {
        return match(get_current_brand()) {
            'thuis' => [
                'title' => 'Maak je feestje magisch met droogijs',
                'subtitle' => 'Bestel eenvoudig kleine hoeveelheden droogijs voor thuis. Perfect voor cocktails, Halloween of experimenten. Veilig bezorgd in heel Nederland.',
                'ctaText' => 'Bestel Nu',
                'secondaryCtaText' => 'Meer informatie',
            ],
            'horeca' => [
                'title' => 'De ultieme beleving voor uw gasten',
                'subtitle' => 'Premium droogijs service voor de horeca. Van spectaculaire cocktailpresentaties tot betrouwbare noodkoeling. 7 dagen per week leverbaar.',
                'ctaText' => 'Zakelijk Account',
                'secondaryCtaText' => 'Bekijk Prijzen',
            ],
            'industrie' => [
                'title' => 'Industriële koudetechniek & logistiek',
                'subtitle' => 'Uw partner voor grootschalige droogijs leveringen, droogijsstralen en geconditioneerd transport. Betrouwbaar, gecertificeerd en schaalbaar.',
                'ctaText' => 'Offerte Aanvragen',
                'secondaryCtaText' => 'Specificaties',
            ],
        };
    }

    /**
     * Features section data per brand.
     */
    public function featuresData(): array
    {
        return match(get_current_brand()) {
            'thuis' => [
                'title' => 'Waarom Droogijs voor Thuis?',
                'subtitle' => 'Professionele effecten, gewoon in je eigen woonkamer.',
                'items' => [
                    [
                        'title' => 'Magisch Effect',
                        'description' => 'Creëer direct sfeer met een spectaculaire mistlaag over je drankjes of buffet.',
                        'icon' => 'sparkles',
                    ],
                    [
                        'title' => 'Veilig Thuisbezorgd',
                        'description' => 'Speciale geïsoleerde verpakking zorgt dat het droogijs perfect aankomt.',
                        'icon' => 'truck',
                    ],
                    [
                        'title' => 'Kleinverpakking',
                        'description' => 'Bestel precies wat je nodig hebt, vanaf 3kg. Ideaal voor feestjes.',
                        'icon' => 'thermometer',
                    ],
                    [
                        'title' => 'Veiligheidsinstructies',
                        'description' => 'Inclusief duidelijke handleiding en veiligheidshandschoenen.',
                        'icon' => 'shield',
                    ],
                ],
            ],
            'horeca' => [
                'title' => 'Speciaal voor de Horeca',
                'subtitle' => 'Wij leveren niet alleen ijs, maar een complete beleving voor uw zaak.',
                'items' => [
                    [
                        'title' => 'De Wow-Factor',
                        'description' => 'Onderscheid uw zaak met spectaculaire presentaties van gerechten en cocktails.',
                        'icon' => 'wine',
                    ],
                    [
                        'title' => '24/7 Service',
                        'description' => 'Spoedlevering mogelijk. Wij begrijpen dat de horeca nooit stilstaat.',
                        'icon' => 'clock',
                    ],
                    [
                        'title' => 'HACCP Gecertificeerd',
                        'description' => 'Voedselveilig droogijs dat voldoet aan alle horeca-eisen en normen.',
                        'icon' => 'award',
                    ],
                    [
                        'title' => 'Noodkoeling',
                        'description' => 'Directe oplossing bij uitval van uw vriescel of koeling.',
                        'icon' => 'zap',
                    ],
                ],
            ],
            'industrie' => [
                'title' => 'Industriële Oplossingen',
                'subtitle' => 'Robuuste service voor veeleisende omgevingen en processen.',
                'items' => [
                    [
                        'title' => 'Bulk Levering',
                        'description' => 'Containers van 100kg tot 500kg. Just-in-time levering voor uw productieproces.',
                        'icon' => 'box',
                    ],
                    [
                        'title' => 'Transport Koeling',
                        'description' => 'Betrouwbare koelketen bewaking voor medisch en voedingstransport.',
                        'icon' => 'truck',
                    ],
                    [
                        'title' => 'Industriële Reiniging',
                        'description' => 'Droogijsstralen: effectief reinigen zonder water of chemicaliën.',
                        'icon' => 'factory',
                    ],
                    [
                        'title' => 'Maatwerk Oplossingen',
                        'description' => 'Pellets, blokken of schijven op maat gemaakt voor uw toepassing.',
                        'icon' => 'settings',
                    ],
                ],
            ],
        };
    }

    /**
     * Use cases section data - fetches latest posts.
     */
    public function useCasesData(): array
    {
        $title = match(get_current_brand()) {
            'thuis' => 'Inspiratie voor thuis',
            'horeca' => 'Toepassingen in de Horeca',
            'industrie' => 'Sectoren',
        };

        // Fetch latest 4 posts
        $query = new \WP_Query([
            'post_type' => 'post',
            'posts_per_page' => 4,
            'post_status' => 'publish',
        ]);

        $items = collect($query->posts)->map(function ($post) {
            $categories = get_the_category($post->ID);
            $category = !empty($categories) ? $categories[0]->name : 'Algemeen';

            $description = get_the_excerpt($post->ID);
            $description = wp_strip_all_tags($description);
            $description = wp_trim_words($description, 15, '...');

            return [
                'title' => get_the_title($post->ID),
                'category' => $category,
                'description' => $description,
                'url' => get_permalink($post->ID),
                'image' => get_the_post_thumbnail_url($post->ID, 'medium_large'),
            ];
        })->toArray();

        wp_reset_postdata();

        return [
            'title' => $title,
            'items' => $items,
        ];
    }

    /**
     * CTA section data per brand.
     */
    public function ctaData(): array
    {
        return match(get_current_brand()) {
            'thuis' => [
                'title' => 'Klaar om indruk te maken?',
                'subtitle' => 'Bestel vandaag voor 15:00, morgen in huis. Inclusief gratis veiligheidshandschoenen.',
            ],
            'horeca' => [
                'title' => 'Verhoog uw gastbeleving',
                'subtitle' => 'Vraag een zakelijk account aan en profiteer van staffelkorting en achteraf betalen.',
            ],
            'industrie' => [
                'title' => 'Optimaliseer uw proces',
                'subtitle' => 'Neem contact op met onze technische dienst voor een oplossing op maat.',
            ],
        };
    }
}
