<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\State;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiFilterSearchTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $electronics;

    private Category $vehicles;

    private Subcategory $smartphones;

    private Subcategory $cars;

    private State $maharashtra;

    private State $delhi;

    private City $mumbai;

    private City $pune;

    private City $newDelhi;

    private Listing $item1;

    private Listing $item2;

    private Listing $item3;

    private Listing $item4;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $country = Country::create(['name' => 'India', 'code' => 'IN']);
        $this->maharashtra = State::create(['country_id' => $country->id, 'name' => 'Maharashtra', 'slug' => 'maharashtra']);
        $this->delhi = State::create(['country_id' => $country->id, 'name' => 'Delhi', 'slug' => 'delhi']);

        $this->mumbai = City::create(['state_id' => $this->maharashtra->id, 'name' => 'Mumbai', 'slug' => 'mumbai', 'is_popular' => true]);
        $this->pune = City::create(['state_id' => $this->maharashtra->id, 'name' => 'Pune', 'slug' => 'pune', 'is_popular' => true]);
        $this->newDelhi = City::create(['state_id' => $this->delhi->id, 'name' => 'New Delhi', 'slug' => 'new-delhi', 'is_popular' => true]);

        $this->electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics', 'order' => 1]);
        $this->vehicles = Category::create(['name' => 'Vehicles', 'slug' => 'vehicles', 'order' => 2]);

        $this->smartphones = Subcategory::create(['category_id' => $this->electronics->id, 'name' => 'Smartphones', 'slug' => 'smartphones']);
        $this->cars = Subcategory::create(['category_id' => $this->vehicles->id, 'name' => 'Cars', 'slug' => 'cars']);

        // Item 1: Brand New iPhone in Mumbai, ₹60,000, negotiable, featured
        $this->item1 = Listing::create([
            'user_id' => $this->user->id,
            'category_id' => $this->electronics->id,
            'subcategory_id' => $this->smartphones->id,
            'state_id' => $this->maharashtra->id,
            'city_id' => $this->mumbai->id,
            'type' => 'product',
            'title' => 'Apple iPhone 15 Pro Max 256GB',
            'slug' => 'apple-iphone-15-pro-max-256gb',
            'description' => 'Brand new sealed pack Apple iPhone in Mumbai.',
            'price' => 60000,
            'condition' => 'Brand New',
            'is_negotiable' => true,
            'is_featured' => true,
            'status' => 'active',
            'views_count' => 150,
        ]);

        ListingImage::create([
            'listing_id' => $this->item1->id,
            'image_path' => 'https://example.com/iphone.jpg',
            'is_primary' => true,
        ]);

        // Item 2: Like New Samsung in Mumbai, ₹35,000, non-negotiable
        $this->item2 = Listing::create([
            'user_id' => $this->user->id,
            'category_id' => $this->electronics->id,
            'subcategory_id' => $this->smartphones->id,
            'state_id' => $this->maharashtra->id,
            'city_id' => $this->mumbai->id,
            'type' => 'product',
            'title' => 'Samsung Galaxy S24 Ultra',
            'slug' => 'samsung-galaxy-s24-ultra',
            'description' => 'Like new flagship phone in Mumbai.',
            'price' => 35000,
            'condition' => 'Like New',
            'is_negotiable' => false,
            'is_featured' => false,
            'status' => 'active',
            'views_count' => 80,
        ]);

        // Item 3: Good Condition Honda City in Pune, ₹450,000
        $this->item3 = Listing::create([
            'user_id' => $this->user->id,
            'category_id' => $this->vehicles->id,
            'subcategory_id' => $this->cars->id,
            'state_id' => $this->maharashtra->id,
            'city_id' => $this->pune->id,
            'type' => 'product',
            'title' => 'Honda City 2021 Petrol V MT',
            'slug' => 'honda-city-2021-petrol-v-mt',
            'description' => 'Well maintained sedan in Pune.',
            'price' => 450000,
            'condition' => 'Good',
            'is_negotiable' => true,
            'is_featured' => false,
            'status' => 'active',
            'views_count' => 300,
        ]);

        // Item 4: Phone Repair Service in Mumbai, ₹500
        $this->item4 = Listing::create([
            'user_id' => $this->user->id,
            'category_id' => $this->electronics->id,
            'state_id' => $this->maharashtra->id,
            'city_id' => $this->mumbai->id,
            'type' => 'service',
            'title' => 'Doorstep iPhone & Mobile Repair Service',
            'slug' => 'doorstep-iphone-mobile-repair-service',
            'description' => 'Professional repair for all smartphone models.',
            'price' => 500,
            'is_negotiable' => false,
            'is_featured' => true,
            'status' => 'active',
            'views_count' => 50,
        ]);
    }

    public function test_multiple_filters_keyword_category_city_and_price_range(): void
    {
        // Search "iPhone" in Category "Electronics" + City "Mumbai" + Price Range 20,000 - 80,000 + Type "product"
        $response = $this->get('/listings?search=iPhone&category=electronics&city=mumbai&min_price=20000&max_price=80000&type=product');

        $response->assertStatus(200)
            ->assertSee('Apple iPhone 15 Pro Max')
            ->assertDontSee('Samsung Galaxy S24')
            ->assertDontSee('Honda City')
            ->assertDontSee('Doorstep iPhone & Mobile Repair Service');
    }

    public function test_multiple_condition_filters_using_array(): void
    {
        // Filter condition array: ['Brand New', 'Like New'] in Mumbai
        $response = $this->get('/listings?city=mumbai&condition[]=Brand+New&condition[]=Like+New&type=product');

        $response->assertStatus(200)
            ->assertSee('Apple iPhone 15 Pro Max')
            ->assertSee('Samsung Galaxy S24 Ultra')
            ->assertDontSee('Honda City');
    }

    public function test_price_range_and_negotiable_filters_together(): void
    {
        // Price under 100,000 and is_negotiable = 1
        $response = $this->get('/listings?max_price=100000&is_negotiable=1&type=product');

        $response->assertStatus(200)
            ->assertSee('Apple iPhone 15 Pro Max')
            ->assertDontSee('Samsung Galaxy S24 Ultra') // not negotiable
            ->assertDontSee('Honda City'); // price 450,000 > 100,000
    }

    public function test_featured_ads_and_with_photos_filters(): void
    {
        // Filter featured=1 and with_photos=1
        $response = $this->get('/listings?is_featured=1&with_photos=1');

        $response->assertStatus(200)
            ->assertSee('Apple iPhone 15 Pro Max')
            ->assertDontSee('Samsung Galaxy S24 Ultra'); // no photo uploaded in test setup
    }

    public function test_location_hierarchy_filter_by_state_and_city(): void
    {
        // Filter by state Maharashtra and city Pune
        $response = $this->get('/listings?state_id='.$this->maharashtra->id.'&city=pune');

        $response->assertStatus(200)
            ->assertSee('Honda City 2021')
            ->assertDontSee('Apple iPhone 15 Pro Max'); // Mumbai
    }

    public function test_sorting_combined_with_filters(): void
    {
        // Filter category Electronics sorted by price ascending
        $response = $this->get('/listings?category=electronics&type=product&sort=price_low');

        $response->assertStatus(200);

        // Samsung (35,000) should appear before iPhone (60,000)
        $content = $response->getContent();
        $posSamsung = strpos($content, 'Samsung Galaxy S24 Ultra');
        $posIphone = strpos($content, 'Apple iPhone 15 Pro Max');

        $this->assertNotFalse($posSamsung);
        $this->assertNotFalse($posIphone);
        $this->assertLessThan($posIphone, $posSamsung);
    }

    public function test_active_filter_pills_rendered_in_view(): void
    {
        $response = $this->get('/listings?search=iPhone&category=electronics&city=mumbai&is_featured=1');

        $response->assertStatus(200)
            ->assertSee('Active Filters')
            ->assertSee('Keyword: "iPhone"')
            ->assertSee('Category: Electronics')
            ->assertSee('City: Mumbai')
            ->assertSee('Featured Only');
    }

    public function test_category_highlight_dynamic_on_query_param_filter(): void
    {
        // When filtering with ?category=electronics query param
        $response = $this->get('/listings?category=electronics');

        $response->assertStatus(200);

        // Sidebar subcategory should be displayed
        $response->assertSee('Smartphones');
    }

    public function test_category_highlight_dynamic_on_route_slug(): void
    {
        // When navigating to dedicated category route /category/electronics
        $response = $this->get('/category/electronics');

        $response->assertStatus(200);

        // Category title and subcategory should be visible
        $response->assertSee('Electronics');
        $response->assertSee('Smartphones');
    }
}
