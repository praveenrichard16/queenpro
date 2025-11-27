<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\HomeSlider;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()
            ->inStock()
            ->latest()
            ->take(8)
            ->get();

        $homeFeaturedProducts = Product::active()
            ->inStock()
            ->featured()
            ->latest()
            ->get();

        $categories = Category::active()
            ->withCount('products')
            ->get();

        $homeSlides = Schema::hasTable('home_sliders')
            ? HomeSlider::query()
                ->active()
                ->orderBy('sort_order')
                ->orderByDesc('created_at')
                ->limit(3)
                ->get()
            : collect();

        $secondSlider = [
            'desktop_image_path' => Setting::getValue('home_second_slider_desktop_image_path', ''),
            'mobile_image_path' => Setting::getValue('home_second_slider_mobile_image_path', ''),
            'alt_text' => Setting::getValue('home_second_slider_alt_text', ''),
            'button_link' => Setting::getValue('home_second_slider_button_link', ''),
            'is_active' => Setting::getValue('home_second_slider_is_active', '1') !== '0',
        ];
        $secondSlider['should_show'] = $secondSlider['is_active']
            && !empty($secondSlider['desktop_image_path'])
            && !empty($secondSlider['mobile_image_path']);

        $productSlideIds = collect(Setting::getValue('home_product_slides', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $productSlides = collect();

        if ($productSlideIds->isNotEmpty()) {
            $productMap = Product::query()
                ->active()
                ->with(['images', 'category'])
                ->whereIn('id', $productSlideIds)
                ->get()
                ->keyBy('id');

            $productSlides = $productSlideIds
                ->map(fn ($id) => $productMap->get($id))
                ->filter()
                ->values();
        }

        // Load third slider settings
        $thirdSlider = [
            'desktop_image_path' => Setting::getValue('home_third_slider_desktop_image_path', ''),
            'mobile_image_path' => Setting::getValue('home_third_slider_mobile_image_path', ''),
            'alt_text' => Setting::getValue('home_third_slider_alt_text', ''),
            'button_link' => Setting::getValue('home_third_slider_button_link', ''),
            'is_active' => Setting::getValue('home_third_slider_is_active', '1') !== '0',
        ];
        $thirdSlider['should_show'] = $thirdSlider['is_active']
            && !empty($thirdSlider['desktop_image_path'])
            && !empty($thirdSlider['mobile_image_path']);

        // Load product slider 2
        $productSlider2Ids = collect(Setting::getValue('home_product_slider2', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $productSlider2 = collect();

        if ($productSlider2Ids->isNotEmpty()) {
            $productSlider2Map = Product::query()
                ->active()
                ->with(['images', 'category'])
                ->whereIn('id', $productSlider2Ids)
                ->get()
                ->keyBy('id');

            $productSlider2 = $productSlider2Ids
                ->map(fn ($id) => $productSlider2Map->get($id))
                ->filter()
                ->values();
        }

        // Load home reviews
        $homeReviewsIds = collect(Setting::getValue('home_reviews', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $homeReviews = collect();

        if ($homeReviewsIds->isNotEmpty()) {
            $homeReviewsMap = \App\Models\ProductReview::query()
                ->approved()
                ->with(['product', 'user'])
                ->whereIn('id', $homeReviewsIds)
                ->get()
                ->keyBy('id');

            $homeReviews = $homeReviewsIds
                ->map(fn ($id) => $homeReviewsMap->get($id))
                ->filter()
                ->values();
        }

        $homeReviewsTitle = Setting::getValue('home_reviews_title', '');
        $homeReviewsDescription = Setting::getValue('home_reviews_description', '');

        return view('home', compact(
            'featuredProducts',
            'homeFeaturedProducts',
            'categories',
            'homeSlides',
            'secondSlider',
            'productSlides',
            'thirdSlider',
            'productSlider2',
            'homeReviews',
            'homeReviewsTitle',
            'homeReviewsDescription'
        ));
    }
}
