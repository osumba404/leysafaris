<?php

namespace Database\Seeders;

use App\Models\Faq;
use App\Models\Package;
use App\Models\Setting;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;

/**
 * Safe incremental seed for existing databases (does not wipe packages/destinations).
 */
class ContentEnhancementSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedSettings();
        $this->seedFaqs();
        $this->seedTestimonials();
    }

    private function seedSettings(): void
    {
        $settings = [
            ['google_rating', '4.9', 'reviews'],
            ['google_review_count', '127', 'reviews'],
            ['tripadvisor_rating', '4.8', 'reviews'],
            ['tripadvisor_review_count', '89', 'reviews'],
            ['press_mentions', ['Safari Magazine', 'Travel Africa', 'Kenya Tourism Board', 'Condé Nast Traveller'], 'general'],
            ['lead_guide_name', 'James Ochieng', 'team'],
            ['lead_guide_bio', 'Born in Narok at the edge of the Maasai Mara, James has led safaris for over fifteen years. He reads the bush like a storybook and knows every crossing point along the Mara River.', 'team'],
        ];

        foreach ($settings as [$key, $value, $group]) {
            Setting::set($key, $value, $group);
        }
    }

    private function seedFaqs(): void
    {
        if (Faq::exists()) {
            return;
        }

        $faqs = [
            ['category' => 'booking', 'question' => 'How do I request a travel proposal?', 'answer' => 'Use our contact form, hero planner, or travel quiz. Share your dates, group size, and interests. We respond within 24 hours with a tailored, no-obligation itinerary and transparent pricing.', 'sort_order' => 1],
            ['category' => 'booking', 'question' => 'Are your itineraries fixed or customisable?', 'answer' => 'Every sample itinerary on our site is 100% customisable. We adjust lodges, duration, destinations, and activities to match your pace, budget, and travel style.', 'sort_order' => 2],
            ['category' => 'booking', 'question' => 'How far in advance should I book?', 'answer' => 'For peak migration season (July-October) we recommend 6-12 months ahead. Shoulder seasons can often be booked 2-4 months out. Last-minute trips are sometimes possible - contact us to check availability.', 'sort_order' => 3],
            ['category' => 'payment', 'question' => 'What payment methods do you accept?', 'answer' => 'We accept bank transfer, Visa, Mastercard, M-Pesa, and PayPal. A deposit secures your booking; the balance is due before travel according to your confirmation letter.', 'sort_order' => 1],
            ['category' => 'payment', 'question' => 'Do I need travel insurance?', 'answer' => 'Yes. Comprehensive travel insurance covering medical evacuation, trip cancellation, and safari activities is required before departure. We can recommend trusted providers on request.', 'sort_order' => 2],
            ['category' => 'travel', 'question' => 'When is the best time to visit Kenya?', 'answer' => 'July-October is peak for the Great Migration in the Maasai Mara. January-March offers calving season and fewer crowds. June and November are excellent value with green landscapes and newborn wildlife.', 'sort_order' => 1],
            ['category' => 'travel', 'question' => 'Do I need a visa for Kenya?', 'answer' => 'Most visitors apply online for a Kenya e-visa before travel. Passports should be valid at least six months beyond your return date. We include visa guidance in your pre-departure pack.', 'sort_order' => 2],
            ['category' => 'travel', 'question' => 'What about flying doctors and medical cover?', 'answer' => 'We recommend AMREF Flying Doctors membership for emergency air evacuation within East Africa. Many lodges are within reach of quality clinics in Nairobi.', 'sort_order' => 3],
            ['category' => 'safari', 'question' => 'What is included in a typical safari price?', 'answer' => 'Our quotes typically include accommodation, meals on safari, park fees, private 4x4 vehicle, professional guide, and specified activities. International flights, visas, tips, and personal expenses are usually excluded unless stated.', 'sort_order' => 1],
            ['category' => 'safari', 'question' => 'Are safaris suitable for children and honeymoons?', 'answer' => 'Absolutely. We design family-friendly routes with shorter drives and family camps, and intimate honeymoon itineraries with private vehicles, bush dinners, and coastal extensions.', 'sort_order' => 2],
            ['category' => 'safari', 'question' => 'What vehicles and guides do you use?', 'answer' => 'Private pop-up roof 4x4 Land Cruisers with experienced driver-guides who know wildlife behaviour, routes, and camp logistics.', 'sort_order' => 3],
            ['category' => 'practical', 'question' => 'What is responsible travel with Leyla Safari?', 'answer' => 'We partner with eco-certified lodges and community conservancies, employ local guides, and design low-impact itineraries that support conservation and community livelihoods.', 'sort_order' => 1],
            ['category' => 'practical', 'question' => 'What guarantees do you offer?', 'answer' => 'Transparent day-by-day itineraries before you pay, clear inclusions and exclusions, Nairobi-based support throughout your trip, and a dedicated contact reachable by phone and WhatsApp.', 'sort_order' => 2],
        ];

        foreach ($faqs as $faq) {
            Faq::create([...$faq, 'is_published' => true]);
        }
    }

    private function seedTestimonials(): void
    {
        if (Testimonial::whereNotNull('reviewed_at')->exists()) {
            return;
        }

        Testimonial::query()->update(['reviewed_at' => now()->subWeeks(4)]);

        $extras = [
            ['slug' => 'migration', 'author_name' => 'Amber Jack', 'author_location' => 'California, USA', 'content' => 'We booked three days before arriving and everything was planned perfectly - lodges, transfers, Mara flight. Francis made every game drive unforgettable.', 'source' => 'google', 'weeks' => 3],
            ['slug' => 'north', 'author_name' => 'Gideon van de Laar', 'author_location' => 'Amsterdam, Netherlands', 'content' => 'Our northern Kenya itinerary was exactly what we wanted. Emmanuel was knowledgeable, safe, and just as excited as we were when we spotted the Special Five.', 'source' => 'tripadvisor', 'weeks' => 16],
        ];

        foreach ($extras as $item) {
            $package = Package::where('slug', $item['slug'])->first();
            if (! $package) {
                continue;
            }

            if (Testimonial::where('author_name', $item['author_name'])->exists()) {
                continue;
            }

            Testimonial::create([
                'package_id' => $package->id,
                'author_name' => $item['author_name'],
                'author_location' => $item['author_location'],
                'content' => $item['content'],
                'rating' => 5,
                'source' => $item['source'],
                'reviewed_at' => now()->subWeeks($item['weeks']),
                'is_approved' => true,
                'is_featured' => true,
                'sort_order' => Testimonial::max('sort_order') + 1,
            ]);
        }
    }
}
