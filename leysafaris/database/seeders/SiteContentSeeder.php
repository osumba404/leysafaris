<?php

namespace Database\Seeders;

use App\Models\FooterLink;
use App\Models\HeroSlide;
use App\Models\NavItem;
use Illuminate\Database\Seeder;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedHeroSlides();
        $this->seedNavItems();
        $this->seedFooterLinks();
    }

    private function seedHeroSlides(): void
    {
        if (HeroSlide::exists()) {
            return;
        }

        $slides = [
            [
                'image' => 'images/savannah_sunset_tree.jpg',
                'eyebrow' => 'Tailor-made safaris · Nairobi experts',
                'title' => "Let's plan your dream trip together",
                'subtitle' => 'Private jeeps, world-class guides, and itineraries crafted around your dates, budget, and sense of adventure.',
                'sort_order' => 1,
            ],
            [
                'image' => 'images/pond_view.jpg',
                'eyebrow' => 'Maasai Mara · Great Migration',
                'title' => 'Witness the river crossings',
                'subtitle' => 'Peak season departures with expert guides who know every crossing point along the Mara.',
                'sort_order' => 2,
            ],
            [
                'image' => 'images/hot_air_baloon_and_zebras.jpg',
                'eyebrow' => 'Balloon safaris · Amboseli · Coast',
                'title' => 'From savannah to sea',
                'subtitle' => 'Combine bush camps, balloon flights, and Indian Ocean relaxation in one seamless journey.',
                'sort_order'  => 3,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create([...$slide, 'is_active' => true]);
        }
    }

    private function seedNavItems(): void
    {
        if (NavItem::exists()) {
            return;
        }

        $items = [
            ['label' => 'Safaris', 'route_name' => 'packages.index', 'sort_order' => 1],
            ['label' => 'Destinations', 'route_name' => 'destinations.index', 'sort_order' => 2],
            ['label' => 'Experiences', 'route_name' => 'experiences.index', 'sort_order' => 3],
            ['label' => 'About', 'route_name' => 'about', 'sort_order' => 4],
            ['label' => 'Journal', 'route_name' => 'blog.index', 'sort_order' => 5],
            ['label' => 'FAQ', 'route_name' => 'faq.index', 'sort_order' => 6],
            ['label' => 'Travel Quiz', 'route_name' => 'travel-quiz.show', 'sort_order' => 7],
            ['label' => 'Contact', 'route_name' => 'contact', 'sort_order' => 8, 'is_highlight' => true],
        ];

        foreach ($items as $item) {
            NavItem::create([
                ...$item,
                'is_active' => true,
                'is_highlight' => $item['is_highlight'] ?? false,
            ]);
        }
    }

    private function seedFooterLinks(): void
    {
        if (FooterLink::exists()) {
            return;
        }

        $links = [
            ['group' => 'explore', 'label' => 'Our Safaris', 'route_name' => 'packages.index', 'sort_order' => 1],
            ['group' => 'explore', 'label' => 'Destinations', 'route_name' => 'destinations.index', 'sort_order' => 2],
            ['group' => 'explore', 'label' => 'Experiences', 'route_name' => 'experiences.index', 'sort_order' => 3],
            ['group' => 'explore', 'label' => 'About Us', 'route_name' => 'about', 'sort_order' => 4],
            ['group' => 'explore', 'label' => 'Journal', 'route_name' => 'blog.index', 'sort_order' => 5],
            ['group' => 'explore', 'label' => 'Inquire', 'route_name' => 'contact', 'sort_order' => 6],
            ['group' => 'travel_info', 'label' => 'Practical Information', 'route_name' => 'practical.index', 'sort_order' => 1],
            ['group' => 'travel_info', 'label' => 'FAQ', 'route_name' => 'faq.index', 'sort_order' => 2],
            ['group' => 'travel_info', 'label' => 'Travel Quiz', 'route_name' => 'travel-quiz.show', 'sort_order' => 3],
        ];

        foreach ($links as $link) {
            FooterLink::create([...$link, 'is_active' => true]);
        }
    }
}
