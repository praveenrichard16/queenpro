<?php

namespace App\Services\Integration;

use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use Throwable;

class SitemapGenerator
{
    /**
     * Generate the sitemap.xml file.
     *
     * @param  array<string, mixed>  $options
     * @return array{path: string, url_count: int}
     */
    public function generate(array $options = []): array
    {
        $sections = $options['sections'] ?? [];
        $includeAll = empty($sections);

        $shouldInclude = static function (string $key) use ($sections, $includeAll): bool {
            return $includeAll || in_array($key, $sections, true);
        };

        $urls = collect();

        if ($shouldInclude('home')) {
            $urls->push($this->makeUrl(URL::to('/'), 'daily', '1.0', now()));
        }

        if ($shouldInclude('static')) {
            $this->appendStaticRoutes($urls);
        }

        if ($shouldInclude('products')) {
            $this->appendProducts($urls);
        }

        if ($shouldInclude('categories')) {
            $this->appendProductCategories($urls);
        }

        if ($shouldInclude('blog_posts')) {
            $this->appendBlogPosts($urls);
        }

        if ($shouldInclude('blog_categories')) {
            $this->appendBlogCategories($urls);
        }

        if ($shouldInclude('tags')) {
            $this->appendProductTags($urls);
        }

        $xml = $this->renderXml($urls);
        $path = public_path('sitemap.xml');

        File::put($path, $xml);

        return [
            'path' => $path,
            'url_count' => $urls->count(),
        ];
    }

    protected function appendStaticRoutes(Collection $urls): void
    {
        foreach ($this->staticRoutes() as $route) {
            $loc = $this->resolveRouteUrl($route['name'], $route['params'] ?? []);

            if ($loc) {
                $urls->push($this->makeUrl(
                    $loc,
                    $route['changefreq'] ?? 'weekly',
                    $route['priority'] ?? '0.6'
                ));
            }
        }
    }

    protected function appendProducts(Collection $urls): void
    {
        try {
            Product::query()
                ->active()
                ->select(['id', 'updated_at', 'created_at'])
                ->orderBy('id')
                ->chunkById(200, function ($products) use ($urls): void {
                    foreach ($products as $product) {
                        $loc = $this->resolveRouteUrl('products.show', ['id' => $product->id]);

                        if (!$loc) {
                            continue;
                        }

                        $lastmod = $product->updated_at ?? $product->created_at;
                        $urls->push($this->makeUrl($loc, 'weekly', '0.8', $lastmod));
                    }
                });
        } catch (Throwable $exception) {
            Log::warning('Unable to append products to sitemap.', [
                'message' => $exception->getMessage(),
            ]);
        }
    }

    protected function appendProductCategories(Collection $urls): void
    {
        try {
            Category::query()
                ->active()
                ->select(['id', 'slug', 'updated_at', 'created_at'])
                ->orderBy('id')
                ->chunkById(200, function ($categories) use ($urls): void {
                    foreach ($categories as $category) {
                        $loc = $this->resolveRouteUrl('products.category', ['category' => $category->slug]);

                        if (!$loc) {
                            continue;
                        }

                        $lastmod = $category->updated_at ?? $category->created_at;
                        $urls->push($this->makeUrl($loc, 'weekly', '0.7', $lastmod));
                    }
                });
        } catch (Throwable $exception) {
            Log::warning('Unable to append product categories to sitemap.', [
                'message' => $exception->getMessage(),
            ]);
        }
    }

    protected function appendProductTags(Collection $urls): void
    {
        try {
            Tag::query()
                ->select(['id', 'slug', 'updated_at', 'created_at'])
                ->orderBy('id')
                ->chunkById(200, function ($tags) use ($urls): void {
                    foreach ($tags as $tag) {
                        $loc = $this->resolveRouteUrl('products.tag', ['tag' => $tag->slug]);

                        if (!$loc) {
                            continue;
                        }

                        $lastmod = $tag->updated_at ?? $tag->created_at;
                        $urls->push($this->makeUrl($loc, 'weekly', '0.6', $lastmod));
                    }
                });
        } catch (Throwable $exception) {
            Log::warning('Unable to append product tags to sitemap.', [
                'message' => $exception->getMessage(),
            ]);
        }
    }

    protected function appendBlogPosts(Collection $urls): void
    {
        try {
            BlogPost::query()
                ->published()
                ->select(['id', 'slug', 'updated_at', 'published_at', 'created_at'])
                ->orderBy('id')
                ->chunkById(200, function ($posts) use ($urls): void {
                    foreach ($posts as $post) {
                        $loc = $this->resolveRouteUrl('blog.show', ['slug' => $post->slug]);

                        if (!$loc) {
                            continue;
                        }

                        $lastmod = $post->updated_at ?? $post->published_at ?? $post->created_at;
                        $urls->push($this->makeUrl($loc, 'weekly', '0.7', $lastmod));
                    }
                });
        } catch (Throwable $exception) {
            Log::warning('Unable to append blog posts to sitemap.', [
                'message' => $exception->getMessage(),
            ]);
        }
    }

    protected function appendBlogCategories(Collection $urls): void
    {
        try {
            BlogCategory::query()
                ->select(['id', 'slug', 'updated_at', 'created_at'])
                ->orderBy('id')
                ->chunkById(200, function ($categories) use ($urls): void {
                    foreach ($categories as $category) {
                        $loc = $this->resolveRouteUrl('blog.category', ['slug' => $category->slug]);

                        if (!$loc) {
                            continue;
                        }

                        $lastmod = $category->updated_at ?? $category->created_at;
                        $urls->push($this->makeUrl($loc, 'weekly', '0.6', $lastmod));
                    }
                });
        } catch (Throwable $exception) {
            Log::warning('Unable to append blog categories to sitemap.', [
                'message' => $exception->getMessage(),
            ]);
        }
    }

    protected function staticRoutes(): array
    {
        return [
            ['name' => 'products.index', 'changefreq' => 'daily', 'priority' => '0.9'],
            ['name' => 'blog.index', 'changefreq' => 'daily', 'priority' => '0.8'],
            ['name' => 'about', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['name' => 'contact', 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['name' => 'privacy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['name' => 'terms', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['name' => 'refund', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['name' => 'wishlist.index', 'changefreq' => 'weekly', 'priority' => '0.4'],
            ['name' => 'compare.index', 'changefreq' => 'weekly', 'priority' => '0.4'],
        ];
    }

    protected function resolveRouteUrl(string $name, array $parameters = []): ?string
    {
        try {
            return route($name, $parameters, absolute: true);
        } catch (Throwable $exception) {
            Log::notice('Skipping sitemap route because it is unavailable.', [
                'route' => $name,
                'message' => $exception->getMessage(),
            ]);

            return null;
        }
    }

    protected function makeUrl(
        string $loc,
        string $changefreq = 'weekly',
        string $priority = '0.5',
        CarbonInterface|null $lastmod = null
    ): array {
        return array_filter([
            'loc' => $loc,
            'lastmod' => $lastmod?->toAtomString(),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ]);
    }

    protected function renderXml(Collection $urls): string
    {
        $lines = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',
        ];

        foreach ($urls as $url) {
            $lines[] = '  <url>';
            $lines[] = '    <loc>' . htmlspecialchars($url['loc'], ENT_XML1 | ENT_COMPAT, 'UTF-8') . '</loc>';

            if (!empty($url['lastmod'])) {
                $lines[] = '    <lastmod>' . $url['lastmod'] . '</lastmod>';
            }

            if (!empty($url['changefreq'])) {
                $lines[] = '    <changefreq>' . $url['changefreq'] . '</changefreq>';
            }

            if (!empty($url['priority'])) {
                $lines[] = '    <priority>' . $url['priority'] . '</priority>';
            }

            $lines[] = '  </url>';
        }

        $lines[] = '</urlset>';

        return implode(PHP_EOL, $lines) . PHP_EOL;
    }
}

