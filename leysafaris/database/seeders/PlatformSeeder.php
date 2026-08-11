<?php

namespace Database\Seeders;

use App\Models\AnnualEvent;
use App\Models\BlogPost;
use App\Models\Destination;
use App\Models\Experience;
use App\Models\Package;
use App\Models\PackageDay;
use App\Models\Setting;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PlatformSeeder extends Seeder
{
    public function run(): void
    {
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Leyla Safari Admin',
            'email' => 'admin@leylasafaritours.com',
            'phone' => '+254712345678',
            'role' => 'super_admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $admin = User::findOrFail($adminId);

        $destinations = $this->seedDestinations();
        $experiences = $this->seedExperiences();
        $packages = $this->seedPackages($destinations, $experiences);

        $this->seedTestimonials($packages);
        $this->seedBlogPosts($admin);
        $this->seedAnnualEvent($packages['migration']);
        $this->seedSettings();
    }

    /**
     * @return array<string, Destination>
     */
    private function seedDestinations(): array
    {
        $data = [
            'maasai-mara' => [
                'name' => 'Maasai Mara',
                'country' => 'Kenya',
                'region' => 'Rift Valley',
                'excerpt' => 'Kenya\'s most celebrated reserve, home to the Great Migration and extraordinary big cat viewing.',
                'description' => 'The Maasai Mara National Reserve spans 1,510 km² of rolling savannah along the Tanzanian border. From July through October, millions of wildebeest and zebra cross the Mara River in one of nature\'s greatest spectacles. Year-round, the Mara delivers exceptional predator sightings, balloon safaris, and authentic Maasai cultural encounters.',
                'best_time' => 'July – October (Great Migration); December – March (calving season nearby)',
                'signature_wildlife' => 'Lions, cheetahs, leopards, wildebeest, elephants, hippos, crocodiles',
                'hero_image' => 'images/pond_view.jpg',
                'gallery' => ['images/pond_view.jpg', 'images/savannah_sunset_tree.jpg', 'images/hot_air_baloon_and_zebras.jpg'],
                'facts' => [
                    ['icon' => 'plane', 'label' => 'Daily flights from Wilson Airport'],
                    ['icon' => 'sun', 'label' => 'Peak season: Jul – Oct'],
                    ['icon' => 'thermometer', 'label' => 'Avg. temp: 12 – 28 °C'],
                ],
                'latitude' => -1.4061,
                'longitude' => 35.0017,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            'amboseli' => [
                'name' => 'Amboseli',
                'country' => 'Kenya',
                'region' => 'Kajiado County',
                'excerpt' => 'Land of giants - over 1,600 elephants with Mount Kilimanjaro as your backdrop.',
                'description' => 'Amboseli National Park sits at the foot of Africa\'s highest peak, offering flat, open terrain that makes it one of the continent\'s most photogenic parks. Large elephant herds gather around permanent swamps fed by Kilimanjaro\'s melting snows, while lions, cheetahs, and over 400 bird species thrive across the 392 km² reserve.',
                'best_time' => 'June – October; January – February',
                'signature_wildlife' => 'African elephants, lions, cheetahs, buffaloes, giraffes, flamingos',
                'hero_image' => 'images/outside_open_tent.jpg',
                'gallery' => ['images/outside_open_tent.jpg', 'images/blacknwhite_safari_banner.jpg'],
                'facts' => [
                    ['icon' => 'car', 'label' => '4 hrs drive from Nairobi'],
                    ['icon' => 'camera', 'label' => 'Best for photography'],
                    ['icon' => 'droplets', 'label' => 'Year-round water sources'],
                ],
                'latitude' => -2.6527,
                'longitude' => 37.2606,
                'is_featured' => true,
                'sort_order' => 2,
            ],
            'samburu' => [
                'name' => 'Samburu',
                'country' => 'Kenya',
                'region' => 'Northern Frontier',
                'excerpt' => 'Kenya\'s arid north - rare species, Samburu culture, and landscapes untouched by time.',
                'description' => 'Samburu National Reserve lies in Kenya\'s semi-arid north along the Ewaso Ng\'iro River. The Special Five - Grevy\'s zebra, reticulated giraffe, Somali ostrich, gerenuk, and Beisa oryx - are found nowhere else in such concentration. Boutique bush camps and Samburu warrior guides make this ideal for repeat safari travellers.',
                'best_time' => 'June – October; December – March',
                'signature_wildlife' => 'Grevy\'s zebra, reticulated giraffe, gerenuk, Beisa oryx, elephants, leopards',
                'hero_image' => 'images/sunset_raised_campsite_view_in_desert.jpg',
                'gallery' => ['images/sunset_raised_campsite_view_in_desert.jpg', 'images/modern_grass_thached_huts.jpg'],
                'facts' => [
                    ['icon' => 'eye', 'label' => 'Unique wildlife species'],
                    ['icon' => 'users', 'label' => 'Rich cultural encounters'],
                    ['icon' => 'tent', 'label' => 'Boutique bush camps'],
                ],
                'latitude' => 0.5695,
                'longitude' => 37.5340,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            'serengeti' => [
                'name' => 'Serengeti',
                'country' => 'Tanzania',
                'region' => 'Mara Region',
                'excerpt' => 'Endless plains where the migration begins and Africa\'s greatest wildlife drama unfolds.',
                'description' => 'The Serengeti National Park covers 14,750 km² of pristine savannah in northern Tanzania. As the southern anchor of the Great Migration, the Serengeti hosts calving season from January to March and serves as the starting point for the herds\' epic journey north into the Maasai Mara. Exceptional predator populations and luxury mobile camps define the experience.',
                'best_time' => 'January – March (calving); June – October (river crossings in north)',
                'signature_wildlife' => 'Wildebeest, zebras, lions, cheetahs, leopards, elephants, rhinos',
                'hero_image' => 'images/savannah_sunset_tree.jpg',
                'gallery' => ['images/savannah_sunset_tree.jpg', 'images/hot_air_baloon_and_zebras.jpg'],
                'facts' => [
                    ['icon' => 'globe', 'label' => 'UNESCO World Heritage Site'],
                    ['icon' => 'calendar', 'label' => 'Year-round wildlife'],
                    ['icon' => 'binoculars', 'label' => 'Migration anchor point'],
                ],
                'latitude' => -2.3333,
                'longitude' => 34.8333,
                'is_featured' => false,
                'sort_order' => 4,
            ],
            'bwindi' => [
                'name' => 'Bwindi',
                'country' => 'Uganda',
                'region' => 'Southwestern Uganda',
                'excerpt' => 'Ancient rainforest sanctuary for half the world\'s remaining mountain gorillas.',
                'description' => 'Bwindi Impenetrable National Park protects roughly 459 mountain gorillas across 331 km² of mist-shrouded montane forest. Gorilla trekking permits are limited and highly sought after, offering an intimate hour with habituated family groups. The park also supports chimpanzees, forest elephants, and over 350 bird species.',
                'best_time' => 'June – August; December – February',
                'signature_wildlife' => 'Mountain gorillas, chimpanzees, forest elephants, L\'Hoest\'s monkeys',
                'hero_image' => 'images/modern_grass_thached_huts.jpg',
                'gallery' => ['images/modern_grass_thached_huts.jpg', 'images/pond_view.jpg'],
                'facts' => [
                    ['icon' => 'footprints', 'label' => 'Gorilla trekking permits required'],
                    ['icon' => 'mountain', 'label' => 'Altitude: 1,160 – 2,607 m'],
                    ['icon' => 'shield', 'label' => 'Conservation success story'],
                ],
                'latitude' => -1.0571,
                'longitude' => 29.6619,
                'is_featured' => false,
                'sort_order' => 5,
            ],
            'diani-beach' => [
                'name' => 'Diani Beach',
                'country' => 'Kenya',
                'region' => 'Kwale County',
                'excerpt' => 'Turquoise Indian Ocean waters and white sand - the perfect safari finale.',
                'description' => 'Diani Beach stretches 17 km along Kenya\'s south coast, consistently ranked among Africa\'s finest beaches. Crystal-clear waters, coral reefs, and luxury beach resorts make it the ideal complement to a bush safari. Activities include snorkelling at Kisite-Mpunguti Marine Park, dhow sailing, and forest walks in nearby Shimba Hills.',
                'best_time' => 'July – October; December – March',
                'signature_wildlife' => 'Dolphins, whale sharks (seasonal), sea turtles, colobus monkeys in Shimba Hills',
                'hero_image' => 'images/sunset_raised_campsite_view_in_desert.jpg',
                'gallery' => ['images/sunset_raised_campsite_view_in_desert.jpg', 'images/outside_open_tent.jpg'],
                'facts' => [
                    ['icon' => 'plane', 'label' => '45 min flight from Nairobi'],
                    ['icon' => 'waves', 'label' => 'World-class snorkelling'],
                    ['icon' => 'sun', 'label' => 'Warm year-round climate'],
                ],
                'latitude' => -4.3224,
                'longitude' => 39.5795,
                'is_featured' => true,
                'sort_order' => 6,
            ],
        ];

        $destinations = [];

        foreach ($data as $slug => $attributes) {
            $destinations[$slug] = Destination::create([
                ...$attributes,
                'slug' => $slug,
                'seo_title' => $attributes['name'] . ' Safari Guide | Leyla Safari Tours',
                'seo_description' => $attributes['excerpt'],
                'is_published' => true,
            ]);
        }

        return $destinations;
    }

    /**
     * @return array<string, Experience>
     */
    private function seedExperiences(): array
    {
        $data = [
            'game-drive' => [
                'name' => 'Game Drive',
                'type' => 'wildlife',
                'excerpt' => 'Dawn and dusk game drives in open 4×4 vehicles with expert local guides.',
                'description' => 'Our signature game drives take place at the optimal hours when wildlife is most active. Each vehicle carries a maximum of six guests with window seats guaranteed, a trained spotter, and a cooler stocked with refreshments. Guides share deep knowledge of animal behaviour, tracks, and the ecosystems you traverse.',
                'image' => 'images/blacknwhite_safari_banner.jpg',
                'duration_hours' => 4,
                'starting_price' => 85.00,
                'sort_order' => 1,
            ],
            'hot-air-balloon' => [
                'name' => 'Hot Air Balloon Safari',
                'type' => 'adventure',
                'excerpt' => 'Float silently above the plains at sunrise, followed by champagne bush breakfast.',
                'description' => 'A pre-dawn balloon flight over the Maasai Mara or Amboseli offers a perspective no game drive can match. Watch the savannah wake below as your pilot navigates the morning winds. Upon landing, a full champagne breakfast is served in the bush - a highlight of any Kenyan safari.',
                'image' => 'images/hot_air_baloon_and_zebras.jpg',
                'duration_hours' => 3,
                'starting_price' => 450.00,
                'sort_order' => 2,
            ],
            'cultural-visit' => [
                'name' => 'Cultural Village Visit',
                'type' => 'culture',
                'excerpt' => 'Authentic encounters with Maasai and Samburu communities supporting local livelihoods.',
                'description' => 'Visit a Maasai or Samburu manyatta with a guide from the community itself. Learn about traditional pastoral life, beadwork, and conservation partnerships. These visits are arranged with respect - no staged performances - and a portion of fees goes directly to the host community.',
                'image' => 'images/modern_grass_thached_huts.jpg',
                'duration_hours' => 2,
                'starting_price' => 35.00,
                'sort_order' => 3,
            ],
            'gorilla-trekking' => [
                'name' => 'Gorilla Trekking',
                'type' => 'wildlife',
                'excerpt' => 'Track habituated mountain gorilla families through Bwindi\'s ancient rainforest.',
                'description' => 'Gorilla trekking in Bwindi Impenetrable Forest is a once-in-a-lifetime experience. After a briefing at park headquarters, you hike with rangers and trackers to locate a habituated gorilla family, then spend a magical hour observing them in their natural habitat. Permits are included in our Uganda extension packages.',
                'image' => 'images/pond_view.jpg',
                'duration_hours' => 6,
                'starting_price' => 800.00,
                'sort_order' => 4,
            ],
        ];

        $experiences = [];

        foreach ($data as $slug => $attributes) {
            $experiences[$slug] = Experience::create([
                ...$attributes,
                'slug' => $slug,
                'currency' => 'USD',
                'is_published' => true,
            ]);
        }

        return $experiences;
    }

    /**
     * @param  array<string, Destination>  $destinations
     * @param  array<string, Experience>  $experiences
     * @return array<string, Package>
     */
    private function seedPackages(array $destinations, array $experiences): array
    {
        $packages = [];

        $packages['migration'] = Package::create([
            'title' => 'Great Migration Safari',
            'slug' => 'great-migration-safari',
            'tagline' => 'Witness nature\'s greatest spectacle',
            'short_description' => 'Witness millions of wildebeest crossing the Mara River, with optional hot air balloon sunrise flights and bush dinners under the stars.',
            'long_description' => 'This signature seven-day journey positions you at the heart of the Great Migration in the Maasai Mara. From private river-crossing stakeouts to an optional dawn balloon flight, every day is designed around peak wildlife activity. Your lead guide has spent decades in the Mara and knows exactly where the herds will move next.',
            'duration_days' => 7,
            'starting_price' => 4850.00,
            'currency' => 'USD',
            'price_note' => 'From USD 4,850 per person sharing',
            'experience_types' => ['wildlife', 'adventure', 'culture'],
            'traveler_types' => ['couples', 'families', 'photographers'],
            'departure_style' => 'private',
            'highlights' => [
                'Mara River crossing stakeouts',
                'Optional hot air balloon safari',
                'Private 4×4 with expert guide',
                'Bush dinners under the stars',
                'Maasai village visit',
            ],
            'inclusions' => [
                'All park fees and conservancy levies',
                'Private 4×4 safari vehicle and guide',
                'Accommodation as per itinerary',
                'All meals on safari days',
                'Airport transfers in Nairobi',
                'Bottled water in vehicle',
            ],
            'exclusions' => [
                'International flights',
                'Hot air balloon safari (optional add-on)',
                'Travel insurance',
                'Personal expenses and gratuities',
                'Visa fees',
            ],
            'gallery' => [
                'images/hot_air_baloon_and_zebras.jpg',
                'images/savannah_sunset_tree.jpg',
                'images/pond_view.jpg',
            ],
            'hero_image' => 'images/hot_air_baloon_and_zebras.jpg',
            'pricing_notes' => 'Balloon safari add-on: USD 450 per person. Single supplement applies.',
            'practical_info' => 'Best travelled July – October. Light layers recommended for early morning drives.',
            'seo_title' => '7-Day Great Migration Safari | Leyla Safari Tours',
            'seo_description' => 'Witness the Great Migration in the Maasai Mara with Leyla Safari Tours. 7 days, from USD 4,850 per person.',
            'is_featured' => true,
            'status' => 'published',
            'sort_order' => 1,
        ]);

        $packages['migration']->destinations()->attach([
            $destinations['maasai-mara']->id,
            $destinations['serengeti']->id,
        ]);
        $packages['migration']->experiences()->attach([
            $experiences['game-drive']->id,
            $experiences['hot-air-balloon']->id,
            $experiences['cultural-visit']->id,
        ]);

        $this->seedMigrationDays($packages['migration']);

        $packages['amboseli'] = Package::create([
            'title' => 'Amboseli Elephant Trail',
            'slug' => 'amboseli-elephant-trail',
            'tagline' => 'Giants beneath Kilimanjaro',
            'short_description' => 'Track Africa\'s largest elephant herds with Kilimanjaro as your backdrop, through open plains and ancient lava flows.',
            'long_description' => 'Five days focused on Amboseli\'s legendary elephant populations and the dramatic landscapes of southern Kenya. Kilimanjaro\'s snow-capped peak frames every game drive, while a transit through Tsavo East adds lion and gerenuk sightings to an already rich itinerary.',
            'duration_days' => 5,
            'starting_price' => 3200.00,
            'currency' => 'USD',
            'price_note' => 'From USD 3,200 per person sharing',
            'experience_types' => ['wildlife'],
            'traveler_types' => ['couples', 'families', 'first-timers'],
            'departure_style' => 'private',
            'highlights' => [
                'Elephant herds with Kilimanjaro views',
                'Amboseli swamp photography',
                'Tsavo East transit drive',
                'Lodge with pool and spa',
            ],
            'inclusions' => [
                'All park fees',
                'Private 4×4 and guide',
                'Accommodation and meals as per itinerary',
                'Nairobi transfers',
            ],
            'exclusions' => [
                'International flights',
                'Travel insurance',
                'Personal expenses',
            ],
            'gallery' => [
                'images/blacknwhite_safari_banner.jpg',
                'images/outside_open_tent.jpg',
            ],
            'hero_image' => 'images/blacknwhite_safari_banner.jpg',
            'seo_title' => '5-Day Amboseli Elephant Trail | Leyla Safari Tours',
            'seo_description' => 'Track elephant herds in Amboseli with Mount Kilimanjaro as your backdrop. 5 days from USD 3,200.',
            'is_featured' => true,
            'status' => 'published',
            'sort_order' => 2,
        ]);

        $packages['amboseli']->destinations()->attach($destinations['amboseli']->id);
        $packages['amboseli']->experiences()->attach($experiences['game-drive']->id);
        $this->seedAmboseliDays($packages['amboseli']);

        $packages['northern'] = Package::create([
            'title' => 'Northern Frontier Expedition',
            'slug' => 'northern-frontier-expedition',
            'tagline' => 'Kenya\'s wild and rare north',
            'short_description' => 'Discover the rare Grevy\'s zebra and reticulated giraffe in Kenya\'s arid north, staying at boutique wilderness lodges.',
            'long_description' => 'Eight days exploring Samburu and Laikipia - Kenya\'s most distinctive ecosystems. The Special Five, night game drives, camel trekking, and Samburu cultural visits combine in an itinerary designed for travellers who have already experienced the classic parks and crave something extraordinary.',
            'duration_days' => 8,
            'starting_price' => 5600.00,
            'currency' => 'USD',
            'price_note' => 'From USD 5,600 per person sharing',
            'experience_types' => ['wildlife', 'culture', 'adventure'],
            'traveler_types' => ['couples', 'repeat-safari-goers', 'photographers'],
            'departure_style' => 'private',
            'highlights' => [
                'Grevy\'s zebra and reticulated giraffe',
                'Samburu warrior-guided walks',
                'Laikipia conservancy game drives',
                'Boutique wilderness lodges',
                'Night game drives',
            ],
            'inclusions' => [
                'All park and conservancy fees',
                'Private guide and 4×4',
                'Full-board accommodation',
                'Internal transfers',
                'Cultural visit fees',
            ],
            'exclusions' => [
                'International flights',
                'Travel insurance',
                'Premium beverages',
            ],
            'gallery' => [
                'images/modern_grass_thached_huts.jpg',
                'images/sunset_raised_campsite_view_in_desert.jpg',
            ],
            'hero_image' => 'images/modern_grass_thached_huts.jpg',
            'seo_title' => '8-Day Northern Frontier Expedition | Leyla Safari Tours',
            'seo_description' => 'Explore Samburu and Laikipia with rare wildlife and boutique lodges. 8 days from USD 5,600.',
            'is_featured' => true,
            'status' => 'published',
            'sort_order' => 3,
        ]);

        $packages['northern']->destinations()->attach($destinations['samburu']->id);
        $packages['northern']->experiences()->attach([
            $experiences['game-drive']->id,
            $experiences['cultural-visit']->id,
        ]);
        $this->seedNorthernDays($packages['northern']);

        $packages['bush-beach'] = Package::create([
            'title' => 'Bush & Beach Escape',
            'slug' => 'bush-beach-escape',
            'tagline' => 'Savannah to sea',
            'short_description' => 'Combine world-class game drives with turquoise Indian Ocean relaxation - the quintessential Kenya experience.',
            'long_description' => 'Ten days that capture the best of Kenya: five full days in the Maasai Mara followed by four nights on the white sands of Diani Beach. A scenic flight connects bush and coast, so you maximise time in each environment without long road transfers.',
            'duration_days' => 10,
            'starting_price' => 6950.00,
            'currency' => 'USD',
            'price_note' => 'From USD 6,950 per person sharing',
            'experience_types' => ['wildlife', 'adventure', 'relaxation'],
            'traveler_types' => ['couples', 'honeymooners', 'families'],
            'departure_style' => 'private',
            'highlights' => [
                'Five full days in the Maasai Mara',
                'Optional balloon safari',
                'Scenic flight Mara to coast',
                'Diani Beach resort stay',
                'Snorkelling and dhow sailing',
            ],
            'inclusions' => [
                'All park fees',
                'Mara–Diani domestic flight',
                'Private guide and 4×4 in Mara',
                'Full-board safari + half-board beach',
                'Airport transfers',
            ],
            'exclusions' => [
                'International flights',
                'Balloon safari add-on',
                'Marine park fees',
                'Travel insurance',
            ],
            'gallery' => [
                'images/sunset_raised_campsite_view_in_desert.jpg',
                'images/hot_air_baloon_and_zebras.jpg',
                'images/outside_open_tent.jpg',
            ],
            'hero_image' => 'images/sunset_raised_campsite_view_in_desert.jpg',
            'seo_title' => '10-Day Bush & Beach Escape | Leyla Safari Tours',
            'seo_description' => 'Maasai Mara safari plus Diani Beach relaxation. 10 days from USD 6,950 per person.',
            'is_featured' => true,
            'status' => 'published',
            'sort_order' => 4,
        ]);

        $packages['bush-beach']->destinations()->attach([
            $destinations['maasai-mara']->id,
            $destinations['diani-beach']->id,
        ]);
        $packages['bush-beach']->experiences()->attach([
            $experiences['game-drive']->id,
            $experiences['hot-air-balloon']->id,
        ]);
        $this->seedBushBeachDays($packages['bush-beach']);

        return $packages;
    }

    private function seedMigrationDays(Package $package): void
    {
        $days = [
            [
                'day_number' => 1,
                'title' => 'Nairobi Arrival',
                'location' => 'Nairobi',
                'narrative' => 'Private airport transfer to your Nairobi hotel. Evening briefing with your lead guide over welcome dinner.',
                'meals' => ['dinner'],
                'accommodation' => 'Nairobi city hotel',
                'sort_order' => 1,
            ],
            [
                'day_number' => 2,
                'title' => 'Nairobi to Maasai Mara',
                'location' => 'Maasai Mara',
                'narrative' => 'Scenic drive or light aircraft flight to the Mara. Afternoon game drive en route to your tented camp.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Mara tented camp',
                'activities' => ['game drive'],
                'sort_order' => 2,
            ],
            [
                'day_number' => 3,
                'title' => 'Full Mara Exploration',
                'location' => 'Maasai Mara',
                'narrative' => 'Dawn and dusk game drives tracking the migration herds. Optional village visit and bush breakfast setup.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Mara tented camp',
                'activities' => ['game drive', 'cultural visit'],
                'wildlife_highlights' => 'Wildebeest herds, lions, cheetahs',
                'sort_order' => 3,
            ],
            [
                'day_number' => 4,
                'title' => 'Full Mara Exploration',
                'location' => 'Maasai Mara',
                'narrative' => 'Continue tracking migration corridors. Your guide repositions based on overnight herd movements and ranger reports.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Mara tented camp',
                'activities' => ['game drive'],
                'wildlife_highlights' => 'River approach points, predator activity',
                'sort_order' => 4,
            ],
            [
                'day_number' => 5,
                'title' => 'Hot Air Balloon Safari',
                'location' => 'Maasai Mara',
                'narrative' => 'Pre-dawn balloon flight over the plains, followed by champagne breakfast in the bush. Relaxed afternoon at camp.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Mara tented camp',
                'activities' => ['hot air balloon', 'bush breakfast'],
                'sort_order' => 5,
            ],
            [
                'day_number' => 6,
                'title' => 'Mara River Crossing',
                'location' => 'Maasai Mara',
                'narrative' => 'Full day positioned at prime river crossing points - the most dramatic wildlife spectacle on earth.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Mara tented camp',
                'activities' => ['game drive'],
                'wildlife_highlights' => 'River crossings, crocodiles, wildebeest',
                'sort_order' => 6,
            ],
            [
                'day_number' => 7,
                'title' => 'Departure',
                'location' => 'Nairobi',
                'narrative' => 'Morning game drive, then transfer to Nairobi for your onward flight. Farewell lunch included.',
                'meals' => ['breakfast', 'lunch'],
                'activities' => ['game drive'],
                'travel_notes' => 'Allow 5–6 hours for road transfer or 45 minutes by air.',
                'sort_order' => 7,
            ],
        ];

        foreach ($days as $day) {
            PackageDay::create([...$day, 'package_id' => $package->id]);
        }
    }

    private function seedAmboseliDays(Package $package): void
    {
        $days = [
            [
                'day_number' => 1,
                'title' => 'Nairobi to Amboseli',
                'location' => 'Amboseli',
                'narrative' => 'Drive south through Maasai country. Evening game drive with Kilimanjaro views at sunset.',
                'meals' => ['lunch', 'dinner'],
                'accommodation' => 'Amboseli lodge',
                'activities' => ['game drive'],
                'sort_order' => 1,
            ],
            [
                'day_number' => 2,
                'title' => 'Amboseli National Park',
                'location' => 'Amboseli',
                'narrative' => 'Full day tracking elephant families across the swamp plains. Midday rest at lodge with pool and spa.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Amboseli lodge',
                'activities' => ['game drive'],
                'wildlife_highlights' => 'Elephant herds, Kilimanjaro backdrop',
                'sort_order' => 2,
            ],
            [
                'day_number' => 3,
                'title' => 'Amboseli National Park',
                'location' => 'Amboseli',
                'narrative' => 'Dawn drive focusing on predators and birdlife around the permanent swamps. Afternoon at leisure.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Amboseli lodge',
                'activities' => ['game drive'],
                'sort_order' => 3,
            ],
            [
                'day_number' => 4,
                'title' => 'Tsavo East Transit',
                'location' => 'Tsavo East',
                'narrative' => 'Transfer through Tsavo\'s red-dust landscapes. Afternoon game drive spotting lions and gerenuk.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Tsavo lodge',
                'activities' => ['game drive'],
                'wildlife_highlights' => 'Lions, gerenuk, red elephants',
                'sort_order' => 4,
            ],
            [
                'day_number' => 5,
                'title' => 'Return to Nairobi',
                'location' => 'Nairobi',
                'narrative' => 'Final morning drive, then scenic return. Drop-off at airport or city hotel.',
                'meals' => ['breakfast', 'lunch'],
                'activities' => ['game drive'],
                'sort_order' => 5,
            ],
        ];

        foreach ($days as $day) {
            PackageDay::create([...$day, 'package_id' => $package->id]);
        }
    }

    private function seedNorthernDays(Package $package): void
    {
        $days = [
            [
                'day_number' => 1,
                'title' => 'Nairobi to Samburu',
                'location' => 'Samburu',
                'narrative' => 'Fly or drive north to Samburu National Reserve. Afternoon game drive along the Ewaso Ng\'iro River.',
                'meals' => ['lunch', 'dinner'],
                'accommodation' => 'Samburu bush camp',
                'activities' => ['game drive'],
                'sort_order' => 1,
            ],
            [
                'day_number' => 2,
                'title' => 'Samburu Game Drives',
                'location' => 'Samburu',
                'narrative' => 'Search for the Special Five - Grevy\'s zebra, reticulated giraffe, gerenuk, Beisa oryx, and Somali ostrich.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Samburu bush camp',
                'activities' => ['game drive'],
                'wildlife_highlights' => 'Special Five species',
                'sort_order' => 2,
            ],
            [
                'day_number' => 3,
                'title' => 'Samburu Cultural Day',
                'location' => 'Samburu',
                'narrative' => 'Morning game drive, afternoon visit to a Samburu manyatta with a community guide. Optional night game drive.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Samburu bush camp',
                'activities' => ['game drive', 'cultural visit', 'night drive'],
                'sort_order' => 3,
            ],
            [
                'day_number' => 4,
                'title' => 'Samburu to Laikipia',
                'location' => 'Laikipia',
                'narrative' => 'Transfer to a private conservancy in Laikipia. Afternoon game drive with a focus on rhino and wild dog.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Laikipia wilderness lodge',
                'activities' => ['game drive'],
                'sort_order' => 4,
            ],
            [
                'day_number' => 5,
                'title' => 'Laikipia Exploration',
                'location' => 'Laikipia',
                'narrative' => 'Full day on the conservancy with walking safari option. Bush lunch under acacia shade.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Laikipia wilderness lodge',
                'activities' => ['game drive', 'walking safari'],
                'sort_order' => 5,
            ],
            [
                'day_number' => 6,
                'title' => 'Laikipia Activities',
                'location' => 'Laikipia',
                'narrative' => 'Choose camel trekking, horseback riding, or a full-day game drive. Evening sundowners overlooking the plateau.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Laikipia wilderness lodge',
                'activities' => ['camel trek', 'game drive'],
                'sort_order' => 6,
            ],
            [
                'day_number' => 7,
                'title' => 'Final Northern Safari',
                'location' => 'Laikipia',
                'narrative' => 'Dawn drive for last sightings. Relaxed afternoon at lodge before farewell dinner.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Laikipia wilderness lodge',
                'activities' => ['game drive'],
                'sort_order' => 7,
            ],
            [
                'day_number' => 8,
                'title' => 'Return to Nairobi',
                'location' => 'Nairobi',
                'narrative' => 'Morning transfer to airstrip or road back to Nairobi. Drop-off at airport or hotel.',
                'meals' => ['breakfast', 'lunch'],
                'travel_notes' => 'Charter flight approximately 1 hour; road transfer 5–6 hours.',
                'sort_order' => 8,
            ],
        ];

        foreach ($days as $day) {
            PackageDay::create([...$day, 'package_id' => $package->id]);
        }
    }

    private function seedBushBeachDays(Package $package): void
    {
        $days = [
            [
                'day_number' => 1,
                'title' => 'Nairobi to Maasai Mara',
                'location' => 'Maasai Mara',
                'narrative' => 'Fly or drive to the Mara. Afternoon game drive on arrival at your tented camp.',
                'meals' => ['lunch', 'dinner'],
                'accommodation' => 'Mara tented camp',
                'activities' => ['game drive'],
                'sort_order' => 1,
            ],
            [
                'day_number' => 2,
                'title' => 'Mara Game Drives',
                'location' => 'Maasai Mara',
                'narrative' => 'Full day of dawn and dusk game drives across the Mara ecosystem.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Mara tented camp',
                'activities' => ['game drive'],
                'sort_order' => 2,
            ],
            [
                'day_number' => 3,
                'title' => 'Mara Exploration',
                'location' => 'Maasai Mara',
                'narrative' => 'Track predator activity and migration herds. Optional bush dinner under the stars.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Mara tented camp',
                'activities' => ['game drive', 'bush dinner'],
                'sort_order' => 3,
            ],
            [
                'day_number' => 4,
                'title' => 'Mara Balloon Option',
                'location' => 'Maasai Mara',
                'narrative' => 'Optional hot air balloon at dawn. Full day game drives otherwise.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Mara tented camp',
                'activities' => ['game drive', 'hot air balloon'],
                'sort_order' => 4,
            ],
            [
                'day_number' => 5,
                'title' => 'Final Mara Day',
                'location' => 'Maasai Mara',
                'narrative' => 'Last full day in the Mara with flexible routing based on your wildlife wish list.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Mara tented camp',
                'activities' => ['game drive'],
                'sort_order' => 5,
            ],
            [
                'day_number' => 6,
                'title' => 'Mara to Coast',
                'location' => 'Diani Beach',
                'narrative' => 'Flight from Mara airstrip to Ukunda. Private transfer to your beach resort on Diani Beach.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Diani Beach resort',
                'travel_notes' => 'Approx. 1 hour flight + 30 min transfer.',
                'sort_order' => 6,
            ],
            [
                'day_number' => 7,
                'title' => 'Diani Beach Relaxation',
                'location' => 'Diani Beach',
                'narrative' => 'Unwind on white sand beaches. Optional snorkelling or dhow sailing.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Diani Beach resort',
                'activities' => ['beach', 'snorkelling'],
                'sort_order' => 7,
            ],
            [
                'day_number' => 8,
                'title' => 'Coastal Exploration',
                'location' => 'Diani Beach',
                'narrative' => 'Optional Kisite-Mpunguti marine park trip or forest walk in Shimba Hills.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Diani Beach resort',
                'activities' => ['marine park', 'forest walk'],
                'sort_order' => 8,
            ],
            [
                'day_number' => 9,
                'title' => 'Beach Leisure',
                'location' => 'Diani Beach',
                'narrative' => 'Free day at the resort. Spa treatments, pool, and Indian Ocean swimming.',
                'meals' => ['breakfast', 'lunch', 'dinner'],
                'accommodation' => 'Diani Beach resort',
                'sort_order' => 9,
            ],
            [
                'day_number' => 10,
                'title' => 'Departure',
                'location' => 'Mombasa',
                'narrative' => 'Leisurely morning at the coast. Transfer to Mombasa International Airport for departure.',
                'meals' => ['breakfast'],
                'travel_notes' => 'Allow 1.5 hours transfer to Mombasa airport.',
                'sort_order' => 10,
            ],
        ];

        foreach ($days as $day) {
            PackageDay::create([...$day, 'package_id' => $package->id]);
        }
    }

    /**
     * @param  array<string, Package>  $packages
     */
    private function seedTestimonials(array $packages): void
    {
        Testimonial::create([
            'package_id' => $packages['migration']->id,
            'author_name' => 'Sarah & James Mitchell',
            'author_location' => 'London, UK',
            'content' => 'We watched a river crossing on day six that left us speechless. Our guide James knew exactly where to position us - no other vehicles in sight. Leyla Safari delivered everything they promised and more.',
            'rating' => 5,
            'source' => 'manual',
            'is_approved' => true,
            'is_featured' => true,
            'sort_order' => 1,
        ]);

        Testimonial::create([
            'package_id' => $packages['amboseli']->id,
            'author_name' => 'Dr. Anita Rao',
            'author_location' => 'Mumbai, India',
            'content' => 'The Amboseli elephant photography was extraordinary. Kilimanjaro emerged from the clouds on our second morning and the lodge team had already arranged the perfect vantage point. Impeccable organisation.',
            'rating' => 5,
            'source' => 'manual',
            'is_approved' => true,
            'is_featured' => true,
            'sort_order' => 2,
        ]);

        Testimonial::create([
            'package_id' => $packages['bush-beach']->id,
            'author_name' => 'Marcus & Elena Weber',
            'author_location' => 'Munich, Germany',
            'content' => 'Bush and beach in ten days - we were sceptical it could work, but the flight connection was seamless. Five days of incredible game viewing followed by four days of pure relaxation. Our honeymoon was perfect.',
            'rating' => 5,
            'source' => 'manual',
            'is_approved' => true,
            'is_featured' => false,
            'sort_order' => 3,
        ]);
    }

    private function seedBlogPosts(User $admin): void
    {
        BlogPost::create([
            'author_id' => $admin->id,
            'title' => 'When to Visit the Maasai Mara for the Great Migration',
            'slug' => 'when-to-visit-maasai-mara-great-migration',
            'excerpt' => 'Timing is everything for the Great Migration. Here is our month-by-month guide to river crossings, calving season, and the best value windows.',
            'content' => '<p>The Great Migration is not a single event but a year-round cycle of movement across the Serengeti–Mara ecosystem. Understanding the rhythm helps you plan the safari of a lifetime.</p><p><strong>July – October</strong> is peak river-crossing season in the Maasai Mara. Herds mass along the Mara River, and dramatic crossings can happen daily - though nature follows its own schedule.</p><p><strong>January – March</strong> sees calving in the southern Serengeti, drawing predators and offering a different but equally compelling spectacle.</p><p>Our lead guides monitor herd movements daily during peak season and reposition itineraries to maximise your chances of witnessing a crossing. Contact us for a personalised timing recommendation based on your travel dates.</p>',
            'featured_image' => 'images/hot_air_baloon_and_zebras.jpg',
            'status' => 'published',
            'published_at' => now()->subDays(14),
            'seo_title' => 'When to Visit the Maasai Mara | Leyla Safari Tours Journal',
            'seo_description' => 'Month-by-month guide to Great Migration timing in the Maasai Mara from Leyla Safari Tours.',
        ]);

        BlogPost::create([
            'author_id' => $admin->id,
            'title' => 'What to Pack for a Kenyan Safari',
            'slug' => 'what-to-pack-kenyan-safari',
            'excerpt' => 'Neutral colours, layered clothing, and the right camera gear - our practical packing list for first-time and repeat safari travellers.',
            'content' => '<p>Packing for a Kenyan safari is simpler than most travellers expect. The key is layers, neutral tones, and protection from sun and dust.</p><p><strong>Clothing:</strong> Light long sleeves and trousers in khaki, olive, or tan. A warm fleece for dawn game drives (temperatures can drop to 10 °C). Comfortable closed shoes and sandals for camp.</p><p><strong>Essentials:</strong> Wide-brim hat, sunglasses, high-SPF sunscreen, insect repellent, binoculars, and a camera with zoom lens. Most camps offer laundry service, so pack light.</p><p><strong>Documents:</strong> Passport valid six months beyond travel, e-visa confirmation, travel insurance, and vaccination certificate if required.</p><p>We send a detailed pre-departure guide with every confirmed booking. Have questions? WhatsApp us anytime.</p>',
            'featured_image' => 'images/outside_open_tent.jpg',
            'status' => 'published',
            'published_at' => now()->subDays(7),
            'seo_title' => 'Safari Packing List | Leyla Safari Tours Journal',
            'seo_description' => 'Practical packing guide for Kenyan safaris from the Leyla Safari Tours team.',
        ]);
    }

    private function seedAnnualEvent(Package $migrationPackage): void
    {
        AnnualEvent::create([
            'package_id' => $migrationPackage->id,
            'title' => 'Great Migration 2027',
            'slug' => 'great-migration-2027',
            'excerpt' => 'Join our fixed-departure Great Migration safari in peak river-crossing season. Limited seats with early-bird pricing.',
            'description' => 'Our annual Great Migration departure is timed for the highest probability of Mara River crossings. Travel with a small group (maximum eight guests), a dedicated lead guide, and guaranteed balloon safari inclusion. Early-bird bookings before 31 December 2026 receive a USD 400 per person discount.',
            'event_date' => '2027-08-15',
            'early_bird_deadline' => '2026-12-31',
            'early_bird_price' => 4450.00,
            'regular_price' => 4850.00,
            'currency' => 'USD',
            'hero_image' => 'images/hot_air_baloon_and_zebras.jpg',
            'is_published' => true,
        ]);
    }

    private function seedSettings(): void
    {
        Setting::set('site_name', 'Leyla Safari Tours', 'general');
        Setting::set('phone', '+254 712 345 678', 'contact');
        Setting::set('whatsapp', '+254712345678', 'contact');
        Setting::set('emails', [
            'info@leylasafaritours.com',
            'inquiry@leylasafaritours.com',
        ], 'contact');
        Setting::set('address', 'Ring Road Parklands, Westlands, Nairobi, Kenya', 'contact');
    }
}
