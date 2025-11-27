<?php

namespace Tests\Feature\Admin;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class SeoToolTest extends TestCase
{
    use RefreshDatabase;

    protected string $testPublicPath;

    protected function setUp(): void
    {
        parent::setUp();

        $this->testPublicPath = base_path('tests/__public');
        File::ensureDirectoryExists($this->testPublicPath);
        $this->app->usePublicPath($this->testPublicPath);
    }

    protected function tearDown(): void
    {
        if (File::exists($this->testPublicPath)) {
            File::deleteDirectory($this->testPublicPath);
        }

        parent::tearDown();
    }

    public function test_admin_can_update_robots_file(): void
    {
        $admin = $this->adminUser();

        $response = $this->actingAs($admin)
            ->post(route('admin.settings.seo.robots.update'), [
                'robots_content' => "User-agent: *\nAllow: /",
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertFileExists(public_path('robots.txt'));
        $this->assertStringContainsString('User-agent: *', File::get(public_path('robots.txt')));

        $this->assertSame("User-agent: *\nAllow: /", Setting::getValue('seo_robots_content'));
    }

    public function test_admin_can_generate_sitemap(): void
    {
        $admin = $this->adminUser();

        $category = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'Smart Watch',
            'slug' => 'smart-watch',
            'description' => 'Test product',
            'price' => 199.99,
            'category_id' => $category->id,
            'stock_quantity' => 10,
            'is_active' => true,
        ]);

        $tag = Tag::create([
            'name' => 'Wearables',
            'slug' => 'wearables',
            'description' => 'Smart accessories',
        ]);

        $product->tags()->attach($tag->id);

        $blogCategory = BlogCategory::create([
            'name' => 'Guides',
            'slug' => 'guides',
            'is_active' => true,
        ]);

        BlogPost::create([
            'blog_category_id' => $blogCategory->id,
            'author_id' => $admin->id,
            'title' => 'Welcome Post',
            'slug' => 'welcome-post',
            'excerpt' => 'Excerpt',
            'content' => 'Content',
            'published_at' => now(),
            'is_published' => true,
        ]);

        $response = $this->actingAs($admin)
            ->post(route('admin.settings.seo.sitemap.generate'), [
                'sections' => ['home', 'static', 'products', 'categories', 'blog_posts', 'blog_categories', 'tags'],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertFileExists(public_path('sitemap.xml'));
        $sitemap = File::get(public_path('sitemap.xml'));

        $this->assertStringContainsString(route('products.index'), $sitemap);
        $this->assertStringContainsString(route('products.category', ['category' => 'electronics']), $sitemap);
        $this->assertStringContainsString(route('products.tag', ['tag' => 'wearables']), $sitemap);
        $this->assertStringContainsString(route('blog.show', ['slug' => 'welcome-post']), $sitemap);
    }

    protected function adminUser(): User
    {
        return User::factory()->create([
            'is_admin' => true,
            'email' => 'admin@example.com',
        ]);
    }
}

