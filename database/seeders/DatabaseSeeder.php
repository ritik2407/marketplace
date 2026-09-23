<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\State;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Demo Users
        $password = 'password123';

        // Super Admin Account
        $admin = User::create([
            'name' => 'Marketplace Admin',
            'email' => 'admin@marketplace.com',
            'phone' => '+91 99999 00000',
            'city' => 'Mumbai',
            'bio' => 'System Administrator with full marketplace privileges.',
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=200&auto=format&fit=crop&q=80',
            'password' => $password,
            'is_admin' => true,
        ]);

        $users = [
            User::create([
                'name' => 'Rahul Sharma',
                'email' => 'rahul@example.com',
                'phone' => '+91 98765 43210',
                'city' => 'Mumbai',
                'bio' => 'Gadget enthusiast & tech reviewer. Verified seller since 2023.',
                'avatar' => 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=200&auto=format&fit=crop&q=80',
                'password' => $password,
                'is_admin' => true,
            ]),
            User::create([
                'name' => 'Priya Patel',
                'email' => 'priya@example.com',
                'phone' => '+91 98123 45678',
                'city' => 'Bengaluru',
                'bio' => 'Interior designer and certified freelancer. Quality verified listings.',
                'avatar' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&auto=format&fit=crop&q=80',
                'password' => $password,
            ]),
            User::create([
                'name' => 'Amit Verma',
                'email' => 'amit@example.com',
                'phone' => '+91 99887 76655',
                'city' => 'New Delhi',
                'bio' => 'Automobile dealer & verified service provider across NCR.',
                'avatar' => 'https://images.unsplash.com/photo-1570295999919-56ceb5ecca61?w=200&auto=format&fit=crop&q=80',
                'password' => $password,
            ]),
            User::create([
                'name' => 'Ananya Iyer',
                'email' => 'ananya@example.com',
                'phone' => '+91 97654 32109',
                'city' => 'Chennai',
                'bio' => 'Professional photographer & creative services specialist.',
                'avatar' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=200&auto=format&fit=crop&q=80',
                'password' => $password,
            ]),
            User::create([
                'name' => 'Vikram Singh',
                'email' => 'vikram@example.com',
                'phone' => '+91 91234 56789',
                'city' => 'Pune',
                'bio' => 'Home maintenance & appliance service professional with 10+ years experience.',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&auto=format&fit=crop&q=80',
                'password' => $password,
            ]),
        ];

        // 2. Create Categories & Subcategories
        $categoriesData = [
            [
                'name' => 'Mobiles & Tablets',
                'slug' => 'mobiles',
                'icon' => 'smartphone',
                'description' => 'Mobile Phones, Tablets, Smartwatches & Mobile Accessories',
                'order' => 1,
                'subcategories' => ['Mobile Phones', 'Tablets', 'Accessories', 'Smart Watches'],
            ],
            [
                'name' => 'Vehicles & Cars',
                'slug' => 'vehicles',
                'icon' => 'car',
                'description' => 'Cars, Motorcycles, Scooters, Commercial Vehicles & Spare Parts',
                'order' => 2,
                'subcategories' => ['Cars', 'Motorcycles', 'Scooters', 'Commercial Vehicles', 'Spare Parts'],
            ],
            [
                'name' => 'Electronics & Appliances',
                'slug' => 'electronics',
                'icon' => 'laptop',
                'description' => 'Laptops, Computers, TVs, Audio, Cameras & Kitchen Appliances',
                'order' => 3,
                'subcategories' => ['Laptops & Computers', 'TVs, Video & Audio', 'Cameras & Lenses', 'Kitchen & Home Appliances'],
            ],
            [
                'name' => 'Real Estate & Properties',
                'slug' => 'real-estate',
                'icon' => 'home',
                'description' => 'Apartments for Rent & Sale, Houses, Lands & Commercial Shops',
                'order' => 4,
                'subcategories' => ['Houses & Apartments for Sale', 'Houses & Apartments for Rent', 'Lands & Plots', 'Commercial Property'],
            ],
            [
                'name' => 'Home & Furniture',
                'slug' => 'home-furniture',
                'icon' => 'sofa',
                'description' => 'Sofa & Dining Sets, Beds & Wardrobes, Home Decor & Garden',
                'order' => 5,
                'subcategories' => ['Sofa & Dining', 'Beds & Wardrobes', 'Home Decor', 'Office Furniture'],
            ],
            [
                'name' => 'Professional Services',
                'slug' => 'services',
                'icon' => 'briefcase',
                'description' => 'Home Cleaning, AC Repair, Tuition, Web Development & Events',
                'order' => 6,
                'subcategories' => ['Home Cleaning & Maid', 'AC & Appliance Repair', 'Web & App Development', 'Tuition & Education', 'Photography & Events', 'Legal & Tax Services'],
            ],
            [
                'name' => 'Fashion & Beauty',
                'slug' => 'fashion',
                'icon' => 'shopping-bag',
                'description' => 'Men Fashion, Women Fashion, Luxury Watches, Shoes & Jewelry',
                'order' => 7,
                'subcategories' => ['Men Fashion', 'Women Fashion', 'Watches & Jewelry', 'Footwear'],
            ],
            [
                'name' => 'Hobbies, Sports & Pets',
                'slug' => 'hobbies-sports',
                'icon' => 'activity',
                'description' => 'Fitness Equipment, Musical Instruments, Books, Gaming & Pets',
                'order' => 8,
                'subcategories' => ['Gym & Fitness', 'Musical Instruments', 'Gaming Consoles', 'Books & Magazines'],
            ],
        ];

        $categoriesMap = [];
        $subcategoriesMap = [];

        foreach ($categoriesData as $cData) {
            $category = Category::create([
                'name' => $cData['name'],
                'slug' => $cData['slug'],
                'icon' => $cData['icon'],
                'description' => $cData['description'],
                'order' => $cData['order'],
            ]);
            $categoriesMap[$category->slug] = $category;

            foreach ($cData['subcategories'] as $subName) {
                $sub = Subcategory::create([
                    'category_id' => $category->id,
                    'name' => $subName,
                    'slug' => Str::slug($subName),
                ]);
                $subcategoriesMap[$category->slug.':'.$sub->slug] = $sub;
            }
        }

        // 3. Create Locations (India > States > Cities > Areas)
        $india = Country::create([
            'name' => 'India',
            'code' => 'IN',
        ]);

        $locationsData = [
            'Maharashtra' => [
                'slug' => 'maharashtra',
                'cities' => [
                    'Mumbai' => [
                        'slug' => 'mumbai',
                        'popular' => true,
                        'areas' => ['Andheri West', 'Bandra West', 'Juhu', 'Powai', 'Worli', 'Colaba', 'Borivali West'],
                    ],
                    'Pune' => [
                        'slug' => 'pune',
                        'popular' => true,
                        'areas' => ['Koregaon Park', 'Kothrud', 'Baner', 'Viman Nagar', 'Hinjewadi', 'Wakad'],
                    ],
                    'Nagpur' => [
                        'slug' => 'nagpur',
                        'popular' => false,
                        'areas' => ['Dharampeth', 'Civil Lines', 'Sitabuldi', 'Ramdaspeth'],
                    ],
                ],
            ],
            'Delhi NCR' => [
                'slug' => 'delhi-ncr',
                'cities' => [
                    'New Delhi' => [
                        'slug' => 'new-delhi',
                        'popular' => true,
                        'areas' => ['Connaught Place', 'Hauz Khas', 'South Extension', 'Saket', 'Dwarka Sector 10', 'Rohini Sector 14'],
                    ],
                    'Noida' => [
                        'slug' => 'noida',
                        'popular' => true,
                        'areas' => ['Sector 18', 'Sector 62', 'Sector 137', 'Sector 50', 'Greater Noida West'],
                    ],
                    'Gurgaon' => [
                        'slug' => 'gurgaon',
                        'popular' => true,
                        'areas' => ['DLF Phase 1', 'Cyber City', 'Golf Course Road', 'Sohna Road', 'Sector 56'],
                    ],
                ],
            ],
            'Karnataka' => [
                'slug' => 'karnataka',
                'cities' => [
                    'Bengaluru' => [
                        'slug' => 'bengaluru',
                        'popular' => true,
                        'areas' => ['Koramangala', 'Indiranagar', 'HSR Layout', 'Whitefield', 'Electronic City', 'Jayanagar', 'Marathahalli'],
                    ],
                    'Mysuru' => [
                        'slug' => 'mysuru',
                        'popular' => false,
                        'areas' => ['Gokulam', 'Jayalakshmipuram', 'Vijayanagar', 'Saraswathipuram'],
                    ],
                ],
            ],
            'Telangana' => [
                'slug' => 'telangana',
                'cities' => [
                    'Hyderabad' => [
                        'slug' => 'hyderabad',
                        'popular' => true,
                        'areas' => ['Gachibowli', 'Hitec City', 'Jubilee Hills', 'Banjara Hills', 'Madhapur', 'Kondapur', 'Kukatpally'],
                    ],
                ],
            ],
            'Tamil Nadu' => [
                'slug' => 'tamil-nadu',
                'cities' => [
                    'Chennai' => [
                        'slug' => 'chennai',
                        'popular' => true,
                        'areas' => ['T Nagar', 'Adyar', 'Anna Nagar', 'Velachery', 'Besant Nagar', 'OMR'],
                    ],
                ],
            ],
            'West Bengal' => [
                'slug' => 'west-bengal',
                'cities' => [
                    'Kolkata' => [
                        'slug' => 'kolkata',
                        'popular' => true,
                        'areas' => ['Salt Lake City', 'New Town', 'Park Street', 'Ballygunge', 'Alipore'],
                    ],
                ],
            ],
            'Gujarat' => [
                'slug' => 'gujarat',
                'cities' => [
                    'Ahmedabad' => [
                        'slug' => 'ahmedabad',
                        'popular' => true,
                        'areas' => ['Satellite', 'SG Highway', 'Bodakdev', 'Vastrapur', 'Prahlad Nagar'],
                    ],
                ],
            ],
            'Rajasthan' => [
                'slug' => 'rajasthan',
                'cities' => [
                    'Jaipur' => [
                        'slug' => 'jaipur',
                        'popular' => true,
                        'areas' => ['Vaishali Nagar', 'Malviya Nagar', 'C Scheme', 'Mansarovar', 'Raja Park'],
                    ],
                ],
            ],
        ];

        $statesMap = [];
        $citiesMap = [];
        $areasMap = [];

        foreach ($locationsData as $stateName => $sData) {
            $state = State::create([
                'country_id' => $india->id,
                'name' => $stateName,
                'slug' => $sData['slug'],
            ]);
            $statesMap[$sData['slug']] = $state;

            foreach ($sData['cities'] as $cityName => $cData) {
                $city = City::create([
                    'state_id' => $state->id,
                    'name' => $cityName,
                    'slug' => $cData['slug'],
                    'is_popular' => $cData['popular'],
                ]);
                $citiesMap[$cData['slug']] = $city;

                foreach ($cData['areas'] as $areaName) {
                    $area = Area::create([
                        'city_id' => $city->id,
                        'name' => $areaName,
                        'slug' => Str::slug($areaName),
                    ]);
                    $areasMap[$cData['slug'].':'.$area->slug] = $area;
                }
            }
        }

        // 4. Create Rich Sample Listings (Products & Services)
        $listingsData = [
            // Mobiles
            [
                'user_index' => 0,
                'type' => 'product',
                'title' => 'Apple iPhone 15 Pro Max - 256GB Natural Titanium (Mint Condition)',
                'slug' => 'apple-iphone-15-pro-max-256gb-natural-titanium',
                'category' => 'mobiles',
                'subcategory' => 'mobile-phones',
                'city' => 'mumbai',
                'area' => 'bandra-west',
                'price' => 112000,
                'is_negotiable' => true,
                'condition' => 'Like New',
                'is_featured' => true,
                'views_count' => 342,
                'phone' => '+91 98765 43210',
                'whatsapp' => '+919876543210',
                'description' => "Selling my sparingly used Apple iPhone 15 Pro Max 256GB in Natural Titanium color.\n\nKey Highlights:\n- 100% Battery Health\n- Apple India Warranty valid till December 2026\n- 10/10 cosmetic condition with tempered glass and case applied from day 1\n- Original box, bill, and unused braided USB-C cable included.\n- Genuine buyers only. Face-to-face deal in Bandra/Khar preferred.",
                'images' => [
                    'https://images.unsplash.com/photo-1695048133142-1a20484d2569?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 1,
                'type' => 'product',
                'title' => 'Samsung Galaxy S24 Ultra 5G - 512GB Titanium Gray',
                'slug' => 'samsung-galaxy-s24-ultra-5g-512gb',
                'category' => 'mobiles',
                'subcategory' => 'mobile-phones',
                'city' => 'bengaluru',
                'area' => 'koramangala',
                'price' => 98500,
                'is_negotiable' => false,
                'condition' => 'Like New',
                'is_featured' => true,
                'views_count' => 218,
                'phone' => '+91 98123 45678',
                'whatsapp' => '+919812345678',
                'description' => 'Samsung Galaxy S24 Ultra in pristine condition with S-Pen, 512GB storage, Snapdragon 8 Gen 3 for Galaxy processor, 200MP camera with Galaxy AI features. Comes with box, bill, and official Samsung Standing Grip Case.',
                'images' => [
                    'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1580910051074-3eb694886505?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 2,
                'type' => 'product',
                'title' => 'Apple Watch Ultra 2 GPS + Cellular 49mm Titanium Case',
                'slug' => 'apple-watch-ultra-2-gps-cellular-49mm',
                'category' => 'mobiles',
                'subcategory' => 'smart-watches',
                'city' => 'new-delhi',
                'area' => 'connaught-place',
                'price' => 64000,
                'is_negotiable' => true,
                'condition' => 'Brand New',
                'is_featured' => false,
                'views_count' => 140,
                'phone' => '+91 99887 76655',
                'whatsapp' => '+919988776655',
                'description' => 'Sealed pack Apple Watch Ultra 2 with Blue Ocean Band. Received as corporate gift, unopened with valid 1 year Apple international warranty. Invoice copy available.',
                'images' => [
                    'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&auto=format&fit=crop&q=80',
                ],
            ],

            // Vehicles
            [
                'user_index' => 2,
                'type' => 'product',
                'title' => '2023 Hyundai Creta SX (O) 1.5 Turbo Petrol DCT Automatic',
                'slug' => '2023-hyundai-creta-sx-o-turbo-dct',
                'category' => 'vehicles',
                'subcategory' => 'cars',
                'city' => 'new-delhi',
                'area' => 'south-extension',
                'price' => 1650000,
                'is_negotiable' => true,
                'condition' => 'Like New',
                'is_featured' => true,
                'views_count' => 520,
                'phone' => '+91 99887 76655',
                'whatsapp' => '+919988776655',
                'description' => 'First owner Hyundai Creta SX(O) Turbo Petrol DCT. 14,000 km driven with full Hyundai service record. Features panoramic sunroof, ventilated front seats, ADAS Level 2, Bose premium audio system, zero dep insurance valid till Nov 2026.',
                'images' => [
                    'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 0,
                'type' => 'product',
                'title' => '2022 Royal Enfield Classic 350 Stealth Black (Dual ABS)',
                'slug' => '2022-royal-enfield-classic-350-stealth-black',
                'category' => 'vehicles',
                'subcategory' => 'motorcycles',
                'city' => 'mumbai',
                'area' => 'andheri-west',
                'price' => 175000,
                'is_negotiable' => true,
                'condition' => 'Good',
                'is_featured' => false,
                'views_count' => 290,
                'phone' => '+91 98765 43210',
                'whatsapp' => '+919876543210',
                'description' => 'Single owner Royal Enfield Classic 350 Stealth Black with alloy wheels and tubeless tyres. Driven 8,500 km only. Fitted with touring seat, crash guard, and sump guard. All service done on time at authorized service center.',
                'images' => [
                    'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 4,
                'type' => 'product',
                'title' => '2024 Honda Activa 6G DLX - Matt Axis Grey (Only 2200 KM)',
                'slug' => '2024-honda-activa-6g-dlx',
                'category' => 'vehicles',
                'subcategory' => 'scooters',
                'city' => 'pune',
                'area' => 'kothrud',
                'price' => 74000,
                'is_negotiable' => false,
                'condition' => 'Like New',
                'is_featured' => false,
                'views_count' => 180,
                'phone' => '+91 91234 56789',
                'whatsapp' => '+919123456789',
                'description' => 'Just 6 months old Honda Activa 6G DLX in mint condition. Showroom condition, 1st free service completed. 5 years insurance active. Moving abroad hence selling.',
                'images' => [
                    'https://images.unsplash.com/photo-1596707328604-58a436be336f?w=800&auto=format&fit=crop&q=80',
                ],
            ],

            // Electronics
            [
                'user_index' => 1,
                'type' => 'product',
                'title' => 'Apple MacBook Pro 16-inch M3 Max (36GB RAM / 1TB SSD)',
                'slug' => 'apple-macbook-pro-16-m3-max',
                'category' => 'electronics',
                'subcategory' => 'laptops-computers',
                'city' => 'bengaluru',
                'area' => 'indiranagar',
                'price' => 275000,
                'is_negotiable' => true,
                'condition' => 'Like New',
                'is_featured' => true,
                'views_count' => 410,
                'phone' => '+91 98123 45678',
                'whatsapp' => '+919812345678',
                'description' => 'MacBook Pro 16 Space Black with M3 Max 14-core CPU, 30-core GPU, 36GB unified memory, 1TB superfast SSD. Battery cycle count only 18. Includes 140W MagSafe charger and original box.',
                'images' => [
                    'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 3,
                'type' => 'product',
                'title' => 'Sony PlayStation 5 Disc Edition + 2 DualSense Controllers + 3 Games',
                'slug' => 'sony-playstation-5-disc-edition-bundle',
                'category' => 'electronics',
                'subcategory' => 'laptops-computers',
                'city' => 'chennai',
                'area' => 't-nagar',
                'price' => 42000,
                'is_negotiable' => true,
                'condition' => 'Good',
                'is_featured' => false,
                'views_count' => 312,
                'phone' => '+91 97654 32109',
                'whatsapp' => '+919765432109',
                'description' => 'Sony PS5 Disc console in flawless condition. Includes two original wireless controllers, HDMI cable, power cord, and 3 games: Spider-Man 2, God of War Ragnarok, and FC24.',
                'images' => [
                    'https://images.unsplash.com/photo-1606813907291-d86efa9b94db?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 3,
                'type' => 'product',
                'title' => 'Sony Alpha A7 IV Full-Frame Camera with 24-70mm f/2.8 GM Lens',
                'slug' => 'sony-alpha-a7-iv-camera-lens-bundle',
                'category' => 'electronics',
                'subcategory' => 'cameras-lenses',
                'city' => 'chennai',
                'area' => 'adyar',
                'price' => 210000,
                'is_negotiable' => true,
                'condition' => 'Like New',
                'is_featured' => true,
                'views_count' => 265,
                'phone' => '+91 97654 32109',
                'whatsapp' => '+919765432109',
                'description' => 'Professional Sony A7 IV (33MP BSI CMOS Sensor, 4K 60p 10-bit video). Shutter count under 4,500 clicks. Bundled with Sony G Master 24-70mm f/2.8 lens, 2 original Sony batteries, 128GB SanDisk Extreme Pro V90 card.',
                'images' => [
                    'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800&auto=format&fit=crop&q=80',
                ],
            ],

            // Real Estate
            [
                'user_index' => 0,
                'type' => 'product',
                'title' => 'Luxury 3 BHK Sea-Facing Apartment in Bandra West (1850 Sq.Ft)',
                'slug' => 'luxury-3-bhk-sea-facing-bandra-west',
                'category' => 'real-estate',
                'subcategory' => 'houses-apartments-for-sale',
                'city' => 'mumbai',
                'area' => 'bandra-west',
                'price' => 65000000,
                'is_negotiable' => true,
                'condition' => 'Brand New',
                'is_featured' => true,
                'views_count' => 890,
                'phone' => '+91 98765 43210',
                'whatsapp' => '+919876543210',
                'description' => "Breathtaking panoramic Arabian sea views from this ultra-luxurious 3 BHK high-rise residence on Carter Road, Bandra West.\n- 1850 sq.ft carpet area\n- 3 Ensuite Bedrooms + Italian Marble Flooring\n- Modular Poggenpohl kitchen with Miele appliances\n- 2 Covered Reserved Car Parkings\n- Building equipped with infinity pool, gymnasium, 24/7 concierge security.",
                'images' => [
                    'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 1,
                'type' => 'product',
                'title' => 'Fully Furnished 2 BHK Flat for Rent in Koramangala 4th Block',
                'slug' => 'fully-furnished-2-bhk-flat-rent-koramangala',
                'category' => 'real-estate',
                'subcategory' => 'houses-apartments-for-rent',
                'city' => 'bengaluru',
                'area' => 'koramangala',
                'price' => 45000,
                'is_negotiable' => false,
                'condition' => 'Good',
                'is_featured' => false,
                'views_count' => 430,
                'phone' => '+91 98123 45678',
                'whatsapp' => '+919812345678',
                'description' => 'Spacious 1200 sq.ft 2 BHK apartment in prime Koramangala 4th Block close to top cafes and tech hubs. Fully loaded with 55-inch Smart TV, 3 inverter ACs, double door refrigerator, automatic washing machine, king beds, and high-speed fiber internet.',
                'images' => [
                    'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800&auto=format&fit=crop&q=80',
                ],
            ],

            // Home & Furniture
            [
                'user_index' => 1,
                'type' => 'product',
                'title' => 'Designer 6-Seater Royal Velvet L-Shape Sofa Set with Lounger',
                'slug' => 'designer-6-seater-royal-velvet-sofa',
                'category' => 'home-furniture',
                'subcategory' => 'sofa-dining',
                'city' => 'bengaluru',
                'area' => 'hsr-layout',
                'price' => 38000,
                'is_negotiable' => true,
                'condition' => 'Like New',
                'is_featured' => false,
                'views_count' => 175,
                'phone' => '+91 98123 45678',
                'whatsapp' => '+919812345678',
                'description' => 'Premium quality Royal Emerald Green velvet sectional sofa set crafted with solid Teak wood frame and 40-density high resilience foam. Bought 8 months ago, spotless condition with matching designer cushions.',
                'images' => [
                    'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 2,
                'type' => 'product',
                'title' => 'Solid Sheesham Wood King Size Bed with Hydraulic Storage',
                'slug' => 'solid-sheesham-wood-king-bed-hydraulic',
                'category' => 'home-furniture',
                'subcategory' => 'beds-wardrobes',
                'city' => 'new-delhi',
                'area' => 'hauz-khas',
                'price' => 29500,
                'is_negotiable' => true,
                'condition' => 'Good',
                'is_featured' => false,
                'views_count' => 160,
                'phone' => '+91 99887 76655',
                'whatsapp' => '+919988776655',
                'description' => 'Authentic solid Sheesham wood king size bed with easy-lift hydraulic storage mechanism. Includes high quality 8-inch orthopaedic memory foam mattress.',
                'images' => [
                    'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?w=800&auto=format&fit=crop&q=80',
                ],
            ],

            // Services
            [
                'user_index' => 4,
                'type' => 'service',
                'title' => 'Express Deep Home Cleaning & Sanitization Service',
                'slug' => 'express-deep-home-cleaning-service',
                'category' => 'services',
                'subcategory' => 'home-cleaning-maid',
                'city' => 'pune',
                'area' => 'viman-nagar',
                'price' => 2499,
                'is_negotiable' => true,
                'condition' => null,
                'is_featured' => true,
                'views_count' => 380,
                'phone' => '+91 91234 56789',
                'whatsapp' => '+919123456789',
                'description' => "Professional, verified 5-star home deep cleaning services in Pune.\n\nIncluded in package:\n- Complete kitchen degreasing & chimney scrubbing\n- Bathroom tile descaling & sanitization\n- Sofa & mattress shampooing with German Karcher vacuum\n- Balcony & window mesh jet washing\n- Eco-friendly chemicals & background verified staff.",
                'images' => [
                    'https://images.unsplash.com/photo-1581578731548-c64695cc6952?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 4,
                'type' => 'service',
                'title' => 'Certified Split & Window AC Repair, Gas Refill & Service',
                'slug' => 'certified-ac-repair-service-gas-refill',
                'category' => 'services',
                'subcategory' => 'ac-appliance-repair',
                'city' => 'mumbai',
                'area' => 'andheri-west',
                'price' => 599,
                'is_negotiable' => false,
                'condition' => null,
                'is_featured' => true,
                'views_count' => 420,
                'phone' => '+91 91234 56789',
                'whatsapp' => '+919123456789',
                'description' => "Same-day doorstep AC repair & servicing across Mumbai.\n- Comprehensive Foam Jet wet cleaning\n- R32 / R410A Genuine refrigerant gas charging\n- PCB repair & compressor troubleshooting\n- 90 days service warranty with invoice.",
                'images' => [
                    'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 1,
                'type' => 'service',
                'title' => 'Full-Stack Custom Web & Mobile App Development (Laravel, React, Flutter)',
                'slug' => 'custom-web-mobile-app-development-services',
                'category' => 'services',
                'subcategory' => 'web-app-development',
                'city' => 'bengaluru',
                'area' => 'whitefield',
                'price' => 35000,
                'is_negotiable' => true,
                'condition' => null,
                'is_featured' => true,
                'views_count' => 610,
                'phone' => '+91 98123 45678',
                'whatsapp' => '+919812345678',
                'description' => "Senior software engineer with 8+ years experience building SaaS apps, e-commerce stores, and marketplace platforms.\n- Modern tech stack: Laravel 12, Next.js, React, Tailwind CSS, Flutter\n- Fast turnaround time, clean code architecture, and ongoing maintenance support\n- Free initial consultation & architectural roadmap.",
                'images' => [
                    'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&auto=format&fit=crop&q=80',
                    'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 3,
                'type' => 'service',
                'title' => 'Candid Wedding, Pre-Wedding & Fashion Portfolio Photography',
                'slug' => 'candid-wedding-fashion-photography-services',
                'category' => 'services',
                'subcategory' => 'photography-events',
                'city' => 'chennai',
                'area' => 'besant-nagar',
                'price' => 25000,
                'is_negotiable' => true,
                'condition' => null,
                'is_featured' => false,
                'views_count' => 290,
                'phone' => '+91 97654 32109',
                'whatsapp' => '+919765432109',
                'description' => 'Award-winning creative photography & cinematic 4K videography team for weddings, portraits, and corporate shoots. High quality color grading and teaser delivery within 7 days.',
                'images' => [
                    'https://images.unsplash.com/photo-1519741497674-611481863552?w=800&auto=format&fit=crop&q=80',
                ],
            ],

            // Hobbies & Sports
            [
                'user_index' => 0,
                'type' => 'product',
                'title' => 'Yamaha FG800 Solid Top Acoustic Guitar + Padded Gig Bag',
                'slug' => 'yamaha-fg800-solid-top-acoustic-guitar',
                'category' => 'hobbies-sports',
                'subcategory' => 'musical-instruments',
                'city' => 'mumbai',
                'area' => 'juhu',
                'price' => 14500,
                'is_negotiable' => true,
                'condition' => 'Like New',
                'is_featured' => false,
                'views_count' => 150,
                'phone' => '+91 98765 43210',
                'whatsapp' => '+919876543210',
                'description' => "Yamaha FG800 solid spruce top acoustic guitar with rich resonant tone. Setup with low action and fresh D'Addario EXP16 strings. Comes with thick waterproof padded bag, digital clip-on tuner, and capo.",
                'images' => [
                    'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=800&auto=format&fit=crop&q=80',
                ],
            ],
            [
                'user_index' => 2,
                'type' => 'product',
                'title' => 'Commercial Grade Adjustable Dumbbells Set (2.5kg to 32kg Pairs)',
                'slug' => 'commercial-grade-adjustable-dumbbells-set',
                'category' => 'hobbies-sports',
                'subcategory' => 'gym-fitness',
                'city' => 'new-delhi',
                'area' => 'dwarka-sector-10',
                'price' => 18000,
                'is_negotiable' => true,
                'condition' => 'Brand New',
                'is_featured' => false,
                'views_count' => 195,
                'phone' => '+91 99887 76655',
                'whatsapp' => '+919988776655',
                'description' => 'Heavy-duty quick-switch dial adjustable dumbbell pair replacing 16 pairs of individual dumbbells. Instant weight selection from 2.5kg to 32kg each. Anti-slip steel knurled handles and durable tray stands.',
                'images' => [
                    'https://images.unsplash.com/photo-1584735935682-2f2b69dff9d2?w=800&auto=format&fit=crop&q=80',
                ],
            ],
        ];

        foreach ($listingsData as $item) {
            $user = $users[$item['user_index']];
            $category = $categoriesMap[$item['category']];
            $subcategory = $subcategoriesMap[$item['category'].':'.$item['subcategory']] ?? null;
            $city = $citiesMap[$item['city']];
            $state = $city->state;
            $area = $areasMap[$item['city'].':'.$item['area']] ?? null;

            $listing = Listing::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'subcategory_id' => $subcategory?->id,
                'country_id' => $india->id,
                'state_id' => $state->id,
                'city_id' => $city->id,
                'area_id' => $area?->id,
                'type' => $item['type'],
                'title' => $item['title'],
                'slug' => $item['slug'],
                'description' => $item['description'],
                'price' => $item['price'],
                'is_negotiable' => $item['is_negotiable'],
                'condition' => $item['condition'],
                'status' => 'active',
                'is_featured' => $item['is_featured'],
                'phone' => $item['phone'],
                'whatsapp' => $item['whatsapp'],
                'email' => $user->email,
                'views_count' => $item['views_count'],
            ]);

            foreach ($item['images'] as $idx => $imgUrl) {
                ListingImage::create([
                    'listing_id' => $listing->id,
                    'image_path' => $imgUrl,
                    'is_primary' => ($idx === 0),
                    'order' => $idx,
                ]);
            }
        }
    }
}
