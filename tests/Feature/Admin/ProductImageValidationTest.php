<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tests\TestCase;

class ProductImageValidationTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
            'is_staff' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Accessories',
            'description' => 'Test accessories category',
            'slug' => Str::slug('Accessories'),
            'is_active' => true,
        ]);

        Storage::fake('public');
    }

    public function test_primary_image_must_match_required_dimensions(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Travel Backpack',
            'description' => 'Durable backpack',
            'price' => 79.99,
            'category_id' => $this->category->id,
            'stock_quantity' => 12,
            'is_active' => true,
            'image' => UploadedFile::fake()->image('backpack.jpg', 600, 600),
        ]);

        $response->assertSessionHasErrors(['image']);
        $this->assertDatabaseCount('products', 0);
    }

    public function test_gallery_images_must_match_required_dimensions(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Electric Kettle',
            'description' => 'Fast boil kettle',
            'price' => 49.50,
            'category_id' => $this->category->id,
            'stock_quantity' => 8,
            'is_active' => true,
            'image' => UploadedFile::fake()->image('kettle-primary.jpg', 750, 1080),
            'gallery' => [
                UploadedFile::fake()->image('kettle-gallery-1.jpg', 750, 1080),
                UploadedFile::fake()->image('kettle-gallery-2.jpg', 800, 800),
            ],
        ]);

        $response->assertSessionHasErrors(['gallery.1']);
        $this->assertDatabaseCount('products', 0);
    }

    public function test_product_creates_successfully_with_valid_dimensions(): void
    {
        $response = $this->actingAs($this->admin)->post(route('admin.products.store'), [
            'name' => 'Ceramic Vase',
            'description' => 'Handcrafted vase',
            'price' => 35.25,
            'category_id' => $this->category->id,
            'stock_quantity' => 4,
            'is_active' => true,
            'image' => UploadedFile::fake()->image('vase-primary.jpg', 750, 1080),
            'gallery' => [
                UploadedFile::fake()->image('vase-gallery-1.jpg', 750, 1080),
                UploadedFile::fake()->image('vase-gallery-2.jpg', 750, 1080),
            ],
        ]);

        $response->assertRedirect(route('admin.products.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseCount('products', 1);

        /** @var Product $product */
        $product = Product::first();
        $this->assertEquals('Ceramic Vase', $product->name);
        $this->assertNotNull($product->image);
        $this->assertDatabaseHas('product_images', [
            'product_id' => $product->id,
        ]);
    }
}


