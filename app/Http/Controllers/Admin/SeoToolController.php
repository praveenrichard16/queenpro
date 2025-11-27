<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\Integration\RobotsGenerator;
use App\Services\Integration\SitemapGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SeoToolController extends Controller
{
    public function edit(): View
    {
        $storedRobots = Setting::getValue('seo_robots_content');
        $robotsContent = $storedRobots ?? RobotsGenerator::read() ?? RobotsGenerator::defaultRules();

        $sitemapSections = Setting::getValue('seo_sitemap_sections', []);
        $lastGenerated = Setting::getValue('seo_sitemap_last_generated');
        $lastUpdatedRobots = Setting::getValue('seo_robots_last_updated');
        $urlCount = (int) Setting::getValue('seo_sitemap_url_count', 0);

        return view('admin.settings.seo', [
            'robotsContent' => $robotsContent,
            'sitemapSections' => is_array($sitemapSections) ? $sitemapSections : [],
            'sitemapExists' => file_exists(public_path('sitemap.xml')),
            'robotsExists' => file_exists(RobotsGenerator::path()),
            'sitemapUrl' => url('sitemap.xml'),
            'robotsUrl' => url('robots.txt'),
            'lastGeneratedAt' => $lastGenerated ? Carbon::parse($lastGenerated) : null,
            'lastUpdatedRobotsAt' => $lastUpdatedRobots ? Carbon::parse($lastUpdatedRobots) : null,
            'sitemapUrlCount' => $urlCount,
        ]);
    }

    public function updateRobots(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'robots_content' => ['required', 'string', 'max:20000'],
        ]);

        $content = $validated['robots_content'];

        RobotsGenerator::write($content);

        Setting::setValue('seo_robots_content', $content);
        Setting::setValue('seo_robots_last_updated', now()->toIso8601String());

        return back()->with('success', 'robots.txt updated successfully.');
    }

    public function resetRobots(): RedirectResponse
    {
        $content = RobotsGenerator::defaultRules();

        RobotsGenerator::write($content);

        Setting::setValue('seo_robots_content', $content);
        Setting::setValue('seo_robots_last_updated', now()->toIso8601String());

        return back()->with('success', 'robots.txt restored to the default template.');
    }

    public function downloadRobots(): BinaryFileResponse
    {
        $path = RobotsGenerator::path();

        if (!file_exists($path)) {
            RobotsGenerator::write(RobotsGenerator::defaultRules());
        }

        return response()->download($path, 'robots.txt');
    }

    public function generateSitemap(Request $request, SitemapGenerator $generator): RedirectResponse
    {
        $validated = $request->validate([
            'sections' => ['nullable', 'array'],
            'sections.*' => ['string', 'in:home,static,products,categories,blog_posts,blog_categories'],
        ]);

        $sections = $validated['sections'] ?? [];

        $result = $generator->generate([
            'sections' => $sections,
        ]);

        Setting::setValue('seo_sitemap_last_generated', now()->toIso8601String());
        Setting::setValue('seo_sitemap_url_count', $result['url_count']);
        Setting::setValue('seo_sitemap_sections', $sections, 'json');

        return back()->with('success', "Sitemap generated successfully with {$result['url_count']} URLs.");
    }

    public function downloadSitemap(): BinaryFileResponse
    {
        $path = public_path('sitemap.xml');

        abort_unless(file_exists($path), 404, 'Sitemap file has not been generated yet.');

        return response()->download($path, 'sitemap.xml');
    }
}

