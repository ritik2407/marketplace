<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Listing;
use App\Models\State;
use App\Models\Subcategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketplaceWebPagesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    private Category $category;

    private Subcategory $subcategory;

    private Country $country;

    private State $state;

    private City $city;

    private Listing $listing;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'Demo Seller',
            'email' => 'seller@example.com',
            'phone' => '+91 98765 00000',
        ]);

        $this->category = Category::create([
            'name' => 'Mobiles',
            'slug' => 'mobiles',
            'icon' => 'smartphone',
            'description' => 'Mobile phones and tablets',
            'order' => 1,
        ]);

        $this->subcategory = Subcategory::create([
            'category_id' => $this->category->id,
            'name' => 'Smartphones',
            'slug' => 'smartphones',
        ]);

        $this->country = Country::create([
            'name' => 'India',
            'code' => 'IN',
        ]);

        $this->state = State::create([
            'country_id' => $this->country->id,
            'name' => 'Maharashtra',
            'slug' => 'maharashtra',
        ]);

        $this->city = City::create([
            'state_id' => $this->state->id,
            'name' => 'Mumbai',
            'slug' => 'mumbai',
            'is_popular' => true,
        ]);

        $this->listing = Listing::create([
            'user_id' => $this->user->id,
            'category_id' => $this->category->id,
            'subcategory_id' => $this->subcategory->id,
            'country_id' => $this->country->id,
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'type' => 'product',
            'title' => 'Apple iPhone 15 Pro 128GB Blue Titanium',
            'slug' => 'apple-iphone-15-pro-128gb-blue-titanium',
            'description' => 'Mint condition Apple iPhone 15 Pro with original bill and box.',
            'price' => 89999,
            'is_negotiable' => true,
            'condition' => 'Like New',
            'status' => 'active',
            'is_featured' => true,
        ]);
    }

    public function test_home_page_loads_with_categories_and_listings(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200)
            ->assertSee('MARKET')
            ->assertSee('Mobiles')
            ->assertSee('Mumbai')
            ->assertSee('Apple iPhone 15 Pro');
    }

    public function test_category_wise_listing_page_displays_matching_ads(): void
    {
        $response = $this->get('/category/mobiles');

        $response->assertStatus(200)
            ->assertSee('Mobiles Ads')
            ->assertSee('Apple iPhone 15 Pro');
    }

    public function test_city_wise_listing_page_displays_matching_ads(): void
    {
        $response = $this->get('/city/mumbai');

        $response->assertStatus(200)
            ->assertSee('Buy, Sell & Find Services in Mumbai')
            ->assertSee('Apple iPhone 15 Pro');
    }

    public function test_city_and_category_wise_listing_page_displays_matching_ads(): void
    {
        $response = $this->get('/city/mumbai/category/mobiles');

        $response->assertStatus(200)
            ->assertSee('Mobiles in Mumbai')
            ->assertSee('Apple iPhone 15 Pro');
    }

    public function test_listing_detail_page_loads_with_seller_and_specs(): void
    {
        $response = $this->get('/listings/apple-iphone-15-pro-128gb-blue-titanium');

        $response->assertStatus(200)
            ->assertSee('Apple iPhone 15 Pro 128GB Blue Titanium')
            ->assertSee('₹ 89,999')
            ->assertSee('Demo Seller')
            ->assertSee('Maharashtra')
            ->assertSee('Mumbai');
    }

    public function test_user_can_submit_inquiry_on_listing(): void
    {
        $response = $this->post("/listings/{$this->listing->id}/inquiry", [
            'name' => 'Interested Buyer',
            'email' => 'buyer@example.com',
            'phone' => '+91 99999 11111',
            'message' => 'Hello, is this iPhone still available for pickup in Mumbai?',
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('inquiries', [
            'listing_id' => $this->listing->id,
            'name' => 'Interested Buyer',
            'email' => 'buyer@example.com',
        ]);
    }

    public function test_authenticated_user_can_create_new_listing(): void
    {
        $response = $this->actingAs($this->user)->post('/post-ad', [
            'type' => 'product',
            'title' => 'Sony Wireless Noise Cancelling Headphones WH-1000XM5',
            'category_id' => $this->category->id,
            'subcategory_id' => $this->subcategory->id,
            'description' => 'Unopened Sony WH-1000XM5 headphones with industry leading noise cancellation.',
            'price' => 24999,
            'is_negotiable' => 1,
            'condition' => 'Brand New',
            'country_id' => $this->country->id,
            'state_id' => $this->state->id,
            'city_id' => $this->city->id,
            'phone' => '+91 98765 00000',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('listings', [
            'title' => 'Sony Wireless Noise Cancelling Headphones WH-1000XM5',
            'user_id' => $this->user->id,
            'price' => 24999,
            'status' => 'active',
        ]);
    }

    public function test_cascade_api_returns_cities_for_state(): void
    {
        $response = $this->getJson("/api/cascade/cities?state_id={$this->state->id}");

        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Mumbai',
                'slug' => 'mumbai',
            ]);
    }
}
