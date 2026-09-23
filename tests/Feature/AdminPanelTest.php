<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Country;
use App\Models\Listing;
use App\Models\ListingImage;
use App\Models\State;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminPanelTest extends TestCase
{
    use RefreshDatabase;

    private User $adminUser;

    private User $regularUser;

    private Category $category;

    private Listing $listing;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminUser = User::factory()->create([
            'name' => 'System Admin',
            'email' => 'admin@marketplace.com',
            'is_admin' => true,
        ]);

        $this->regularUser = User::factory()->create([
            'name' => 'Regular User',
            'email' => 'user@example.com',
            'is_admin' => false,
        ]);

        $this->category = Category::create([
            'name' => 'Vehicles',
            'slug' => 'vehicles',
            'icon' => 'car',
            'order' => 1,
        ]);

        $country = Country::create(['name' => 'India', 'code' => 'IN']);
        $state = State::create(['country_id' => $country->id, 'name' => 'Delhi NCR', 'slug' => 'delhi-ncr']);
        $city = City::create(['state_id' => $state->id, 'name' => 'New Delhi', 'slug' => 'new-delhi', 'is_popular' => true]);

        $this->listing = Listing::create([
            'user_id' => $this->regularUser->id,
            'category_id' => $this->category->id,
            'country_id' => $country->id,
            'state_id' => $state->id,
            'city_id' => $city->id,
            'type' => 'product',
            'title' => '2023 Royal Enfield Meteor 350',
            'slug' => '2023-royal-enfield-meteor-350',
            'description' => 'Single owner bike with all service records and accessories.',
            'price' => 185000,
            'status' => 'active',
            'is_featured' => false,
        ]);
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');
    }

    public function test_regular_user_forbidden_from_admin_panel(): void
    {
        $response = $this->actingAs($this->regularUser)->get('/admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_dashboard_and_metrics(): void
    {
        $response = $this->actingAs($this->adminUser)->get('/admin');

        $response->assertStatus(200)
            ->assertSee('MARKETPLACE')
            ->assertSee('ADMIN PORTAL')
            ->assertSee('Total Listings')
            ->assertSee('2023 Royal Enfield Meteor 350');
    }

    public function test_admin_can_toggle_listing_featured(): void
    {
        $response = $this->actingAs($this->adminUser)->patch("/admin/listings/{$this->listing->id}/featured");

        $response->assertRedirect();
        $this->assertTrue($this->listing->fresh()->is_featured);
    }

    public function test_admin_can_change_listing_status(): void
    {
        $response = $this->actingAs($this->adminUser)->patch("/admin/listings/{$this->listing->id}/status", [
            'status' => 'sold',
        ]);

        $response->assertRedirect();
        $this->assertEquals('sold', $this->listing->fresh()->status);
    }

    public function test_admin_can_delete_listing(): void
    {
        $response = $this->actingAs($this->adminUser)->delete("/admin/listings/{$this->listing->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('listings', ['id' => $this->listing->id]);
    }

    public function test_admin_can_upload_images_to_listing(): void
    {
        Storage::fake('public');

        $file1 = UploadedFile::fake()->image('front.jpg');
        $file2 = UploadedFile::fake()->image('back.png');

        $response = $this->actingAs($this->adminUser)->post("/admin/listings/{$this->listing->id}/images", [
            'images' => [$file1, $file2],
        ]);

        $response->assertRedirect();
        $this->assertCount(2, $this->listing->fresh()->images);
        $this->assertTrue($this->listing->fresh()->images->first()->is_primary);
    }

    public function test_admin_can_set_primary_image_for_listing(): void
    {
        $img1 = ListingImage::create([
            'listing_id' => $this->listing->id,
            'image_path' => 'listings/img1.jpg',
            'is_primary' => true,
            'order' => 0,
        ]);

        $img2 = ListingImage::create([
            'listing_id' => $this->listing->id,
            'image_path' => 'listings/img2.jpg',
            'is_primary' => false,
            'order' => 1,
        ]);

        $response = $this->actingAs($this->adminUser)->patch("/admin/images/{$img2->id}/primary");

        $response->assertRedirect();
        $this->assertFalse($img1->fresh()->is_primary);
        $this->assertTrue($img2->fresh()->is_primary);
    }

    public function test_admin_can_delete_listing_image_and_auto_reassign_primary(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('listings/primary.jpg', 'content');
        Storage::disk('public')->put('listings/secondary.jpg', 'content');

        $img1 = ListingImage::create([
            'listing_id' => $this->listing->id,
            'image_path' => 'listings/primary.jpg',
            'is_primary' => true,
            'order' => 0,
        ]);

        $img2 = ListingImage::create([
            'listing_id' => $this->listing->id,
            'image_path' => 'listings/secondary.jpg',
            'is_primary' => false,
            'order' => 1,
        ]);

        $response = $this->actingAs($this->adminUser)->delete("/admin/images/{$img1->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('listing_images', ['id' => $img1->id]);
        $this->assertTrue($img2->fresh()->is_primary);
    }

    public function test_admin_can_toggle_user_admin_privileges(): void
    {
        $response = $this->actingAs($this->adminUser)->patch("/admin/users/{$this->regularUser->id}/toggle-admin");

        $response->assertRedirect();
        $this->assertTrue($this->regularUser->fresh()->is_admin);
    }

    public function test_admin_can_create_new_category_and_subcategory(): void
    {
        $response = $this->actingAs($this->adminUser)->post('/admin/categories', [
            'name' => 'Heavy Machinery',
            'icon' => 'truck',
            'description' => 'Industrial and construction equipment',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('categories', ['name' => 'Heavy Machinery']);

        $newCategory = Category::where('name', 'Heavy Machinery')->first();

        $subResponse = $this->actingAs($this->adminUser)->post("/admin/categories/{$newCategory->id}/subcategories", [
            'name' => 'Excavators & Cranes',
        ]);

        $subResponse->assertRedirect();
        $this->assertDatabaseHas('subcategories', ['name' => 'Excavators & Cranes', 'category_id' => $newCategory->id]);
    }
}
