<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomeSlider;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HomeSliderController extends Controller
{
    private const MAX_SLIDES = 3;
    private const MAX_PRODUCT_SLIDES = 12;
    private const MAX_PRODUCT_SLIDER2_PRODUCTS = 12;
    private const MAX_HOME_REVIEWS = 12;

    public function home(): View
    {
        // Load hero sliders data
        $sliders = HomeSlider::query()
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        // Load second slider settings
        $secondSlider = [
            'desktop_image_path' => Setting::getValue('home_second_slider_desktop_image_path', ''),
            'mobile_image_path' => Setting::getValue('home_second_slider_mobile_image_path', ''),
            'alt_text' => Setting::getValue('home_second_slider_alt_text', ''),
            'button_link' => Setting::getValue('home_second_slider_button_link', ''),
            'is_active' => Setting::getValue('home_second_slider_is_active', '1') !== '0',
        ];

        // Load product slides settings
        $selectedIds = collect(Setting::getValue('home_product_slides', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $selectedProducts = Product::query()
            ->whereIn('id', $selectedIds)
            ->select(['id', 'name', 'is_active'])
            ->get()
            ->keyBy('id');

        $orderedSelections = $selectedIds
            ->map(fn ($id) => $selectedProducts->get($id))
            ->filter()
            ->values();

        $productOptions = Product::query()
            ->orderBy('name')
            ->select(['id', 'name', 'is_active'])
            ->get();

        // Load third slider settings
        $thirdSlider = [
            'desktop_image_path' => Setting::getValue('home_third_slider_desktop_image_path', ''),
            'mobile_image_path' => Setting::getValue('home_third_slider_mobile_image_path', ''),
            'alt_text' => Setting::getValue('home_third_slider_alt_text', ''),
            'button_link' => Setting::getValue('home_third_slider_button_link', ''),
            'is_active' => Setting::getValue('home_third_slider_is_active', '1') !== '0',
        ];

        // Load product slider 2 settings
        $productSlider2Ids = collect(Setting::getValue('home_product_slider2', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $productSlider2Selected = Product::query()
            ->whereIn('id', $productSlider2Ids)
            ->select(['id', 'name', 'is_active'])
            ->get()
            ->keyBy('id');

        $productSlider2Ordered = $productSlider2Ids
            ->map(fn ($id) => $productSlider2Selected->get($id))
            ->filter()
            ->values();

        // Load home reviews settings
        $homeReviewsIds = collect(Setting::getValue('home_reviews', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $homeReviewsSelected = ProductReview::query()
            ->with('product')
            ->whereIn('id', $homeReviewsIds)
            ->get()
            ->keyBy('id');

        $homeReviewsOrdered = $homeReviewsIds
            ->map(fn ($id) => $homeReviewsSelected->get($id))
            ->filter()
            ->values();

        $reviewOptions = ProductReview::query()
            ->approved()
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.cms.home.index', [
            'sliders' => $sliders,
            'canCreate' => $sliders->count() < self::MAX_SLIDES,
            'secondSlider' => $secondSlider,
            'selectedProducts' => $orderedSelections,
            'productOptions' => $productOptions,
            'maxSlides' => self::MAX_PRODUCT_SLIDES,
            'thirdSlider' => $thirdSlider,
            'productSlider2Selected' => $productSlider2Ordered,
            'maxProductSlider2' => self::MAX_PRODUCT_SLIDER2_PRODUCTS,
            'homeReviewsSelected' => $homeReviewsOrdered,
            'reviewOptions' => $reviewOptions,
            'homeReviewsTitle' => Setting::getValue('home_reviews_title', ''),
            'homeReviewsDescription' => Setting::getValue('home_reviews_description', ''),
            'maxHomeReviews' => self::MAX_HOME_REVIEWS,
        ]);
    }

    public function index(): View
    {
        $sliders = HomeSlider::query()
            ->orderBy('sort_order')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.cms.home-sliders.index', [
            'sliders' => $sliders,
            'canCreate' => $sliders->count() < self::MAX_SLIDES,
        ]);
    }

    public function create(): View|RedirectResponse
    {
        if (HomeSlider::count() >= self::MAX_SLIDES) {
            return redirect()
                ->route('admin.cms.home.index')
                ->with('error', 'You can only create up to three slides.')
                ->with('active_tab', 'hero');
        }

        return view('admin.cms.home-sliders.create', [
            'slider' => new HomeSlider(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (HomeSlider::count() >= self::MAX_SLIDES) {
            return redirect()
                ->route('admin.cms.home.index')
                ->with('error', 'You can only create up to three slides.')
                ->with('active_tab', 'hero');
        }

        $data = $this->validateData($request, true);

        $data['desktop_image_path'] = $request->file('desktop_image')->store('home-sliders', 'public');
        $data['mobile_image_path'] = $request->file('mobile_image')->store('home-sliders', 'public');
        $data['is_active'] = $request->boolean('is_active');
        $data['show_title'] = $request->boolean('show_title');
        $data['show_description'] = $request->boolean('show_description');
        $data['show_button'] = $request->boolean('show_button');
        $data['sort_order'] = $data['sort_order'] ?? (HomeSlider::max('sort_order') ?? 0) + 1;

        HomeSlider::create($data);

        return redirect()
            ->route('admin.cms.home.index')
            ->with('success', 'Slider created successfully.')
            ->with('active_tab', $request->input('active_tab', 'hero'));
    }

    public function edit(HomeSlider $slider): View
    {
        return view('admin.cms.home-sliders.edit', [
            'slider' => $slider,
        ]);
    }

    public function update(Request $request, HomeSlider $slider): RedirectResponse
    {
        $data = $this->validateData($request, false);
        $payload = collect($data)->except(['desktop_image', 'mobile_image'])->toArray();

        if ($request->hasFile('desktop_image')) {
            $this->deleteStoredFile($slider->desktop_image_path);
            $payload['desktop_image_path'] = $request->file('desktop_image')->store('home-sliders', 'public');
        }

        if ($request->hasFile('mobile_image')) {
            $this->deleteStoredFile($slider->mobile_image_path);
            $payload['mobile_image_path'] = $request->file('mobile_image')->store('home-sliders', 'public');
        }

        $payload['is_active'] = $request->boolean('is_active');
        $payload['show_title'] = $request->boolean('show_title');
        $payload['show_description'] = $request->boolean('show_description');
        $payload['show_button'] = $request->boolean('show_button');

        $slider->update($payload);

        return redirect()
            ->route('admin.cms.home.index')
            ->with('success', 'Slider updated successfully.')
            ->with('active_tab', $request->input('active_tab', 'hero'));
    }

    public function destroy(Request $request, HomeSlider $slider): RedirectResponse
    {
        $this->deleteStoredFile($slider->desktop_image_path);
        $this->deleteStoredFile($slider->mobile_image_path);

        $slider->delete();

        return redirect()
            ->route('admin.cms.home.index')
            ->with('success', 'Slider deleted successfully.')
            ->with('active_tab', $request->input('active_tab', 'hero'));
    }

    private function validateData(Request $request, bool $isCreate): array
    {
        $baseRules = [
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'button_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'url', 'max:2048'],
            'show_title' => ['nullable', 'boolean'],
            'show_description' => ['nullable', 'boolean'],
            'show_button' => ['nullable', 'boolean'],
            'button_size' => ['nullable', 'string', 'in:small,medium,large'],
            'button_color' => ['nullable', 'string', 'max:20', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
            'title_position' => ['nullable', 'string', 'in:left,center,right'],
            'description_position' => ['nullable', 'string', 'in:left,center,right'],
            'button_position' => ['nullable', 'string', 'in:left,center,right'],
            'sort_order' => ['nullable', 'integer', 'min:1', 'max:50'],
            'is_active' => ['nullable', 'boolean'],
        ];

        $imageRules = [
            'desktop_image' => array_merge(
                $isCreate ? ['required'] : ['nullable'],
                ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:width=1600,height=900']
            ),
            'mobile_image' => array_merge(
                $isCreate ? ['required'] : ['nullable'],
                ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:width=800,height=1300']
            ),
        ];

        return $request->validate(array_merge($baseRules, $imageRules));
    }

    public function seoSettings(): View
    {
        $settings = [
            'home_meta_title' => Setting::getValue('home_meta_title', ''),
            'home_meta_description' => Setting::getValue('home_meta_description', ''),
            'home_meta_keywords' => Setting::getValue('home_meta_keywords', ''),
        ];

        return view('admin.cms.home-seo.settings', [
            'settings' => $settings,
        ]);
    }

    public function updateSeoSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'home_meta_title' => ['nullable', 'string', 'max:255'],
            'home_meta_description' => ['nullable', 'string', 'max:500'],
            'home_meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);

        Setting::setValue('home_meta_title', $validated['home_meta_title'] ?? '');
        Setting::setValue('home_meta_description', $validated['home_meta_description'] ?? '');
        Setting::setValue('home_meta_keywords', $validated['home_meta_keywords'] ?? '');

        return redirect()
            ->route('admin.cms.home.seo.settings')
            ->with('success', 'Home page SEO settings updated successfully.');
    }

    public function secondSliderSettings(): View
    {
        $secondSlider = [
            'desktop_image_path' => Setting::getValue('home_second_slider_desktop_image_path', ''),
            'mobile_image_path' => Setting::getValue('home_second_slider_mobile_image_path', ''),
            'alt_text' => Setting::getValue('home_second_slider_alt_text', ''),
            'button_link' => Setting::getValue('home_second_slider_button_link', ''),
            'is_active' => Setting::getValue('home_second_slider_is_active', '1') !== '0',
        ];

        return view('admin.cms.home-second-slider.settings', compact('secondSlider'));
    }

    public function updateSecondSliderSettings(Request $request): RedirectResponse
    {
        $existingDesktop = Setting::getValue('home_second_slider_desktop_image_path');
        $existingMobile = Setting::getValue('home_second_slider_mobile_image_path');

        $rules = [
            'is_active' => ['nullable', 'boolean'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'url', 'max:2048'],
        ];

        $rules['desktop_image'] = array_merge(
            $existingDesktop ? ['nullable'] : ['required'],
            ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:min_width=1200,min_height=500']
        );

        $rules['mobile_image'] = array_merge(
            $existingMobile ? ['nullable'] : ['required'],
            ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:min_width=750,min_height=1000']
        );

        $validated = $request->validate($rules);

        if ($request->hasFile('desktop_image')) {
            $this->deleteStoredFile($existingDesktop);
            $desktopPath = $request->file('desktop_image')->store('home-second-slider', 'public');
            Setting::setValue('home_second_slider_desktop_image_path', $desktopPath);
        }

        if ($request->hasFile('mobile_image')) {
            $this->deleteStoredFile($existingMobile);
            $mobilePath = $request->file('mobile_image')->store('home-second-slider', 'public');
            Setting::setValue('home_second_slider_mobile_image_path', $mobilePath);
        }

        Setting::setValue('home_second_slider_is_active', $request->boolean('is_active') ? '1' : '0');
        Setting::setValue('home_second_slider_alt_text', $validated['alt_text'] ?? '');
        Setting::setValue('home_second_slider_button_link', $validated['button_link'] ?? '');

        return redirect()
            ->route('admin.cms.home.index')
            ->with('success', 'Second section slider updated successfully.')
            ->with('active_tab', $request->input('active_tab', 'second'));
    }

    public function productSlidesSettings(): View
    {
        $selectedIds = collect(Setting::getValue('home_product_slides', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $selectedProducts = Product::query()
            ->whereIn('id', $selectedIds)
            ->select(['id', 'name', 'is_active'])
            ->get()
            ->keyBy('id');

        $orderedSelections = $selectedIds
            ->map(fn ($id) => $selectedProducts->get($id))
            ->filter()
            ->values();

        $productOptions = Product::query()
            ->orderBy('name')
            ->select(['id', 'name', 'is_active'])
            ->get();

        return view('admin.cms.home-product-slides.settings', [
            'selectedProducts' => $orderedSelections,
            'productOptions' => $productOptions,
            'maxSlides' => self::MAX_PRODUCT_SLIDES,
        ]);
    }

    public function updateProductSlidesSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_slides' => ['nullable', 'array', 'max:' . self::MAX_PRODUCT_SLIDES],
            'product_slides.*' => ['integer', 'exists:products,id'],
        ]);

        $orderedUnique = collect($validated['product_slides'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        Setting::setValue('home_product_slides', $orderedUnique->all(), 'json');

        return redirect()
            ->route('admin.cms.home.index')
            ->with('success', 'Product slides updated successfully.')
            ->with('active_tab', $request->input('active_tab', 'product'));
    }

    public function thirdSliderSettings(): View
    {
        $thirdSlider = [
            'desktop_image_path' => Setting::getValue('home_third_slider_desktop_image_path', ''),
            'mobile_image_path' => Setting::getValue('home_third_slider_mobile_image_path', ''),
            'alt_text' => Setting::getValue('home_third_slider_alt_text', ''),
            'button_link' => Setting::getValue('home_third_slider_button_link', ''),
            'is_active' => Setting::getValue('home_third_slider_is_active', '1') !== '0',
        ];

        return view('admin.cms.home-third-slider.settings', compact('thirdSlider'));
    }

    public function updateThirdSliderSettings(Request $request): RedirectResponse
    {
        $existingDesktop = Setting::getValue('home_third_slider_desktop_image_path');
        $existingMobile = Setting::getValue('home_third_slider_mobile_image_path');

        $rules = [
            'is_active' => ['nullable', 'boolean'],
            'alt_text' => ['nullable', 'string', 'max:255'],
            'button_link' => ['nullable', 'url', 'max:2048'],
        ];

        $rules['desktop_image'] = array_merge(
            $existingDesktop ? ['nullable'] : ['required'],
            ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:min_width=1200,min_height=500']
        );

        $rules['mobile_image'] = array_merge(
            $existingMobile ? ['nullable'] : ['required'],
            ['image', 'mimes:jpg,jpeg,png,webp', 'max:4096', 'dimensions:min_width=750,min_height=1000']
        );

        $validated = $request->validate($rules);

        if ($request->hasFile('desktop_image')) {
            $this->deleteStoredFile($existingDesktop);
            $desktopPath = $request->file('desktop_image')->store('home-third-slider', 'public');
            Setting::setValue('home_third_slider_desktop_image_path', $desktopPath);
        }

        if ($request->hasFile('mobile_image')) {
            $this->deleteStoredFile($existingMobile);
            $mobilePath = $request->file('mobile_image')->store('home-third-slider', 'public');
            Setting::setValue('home_third_slider_mobile_image_path', $mobilePath);
        }

        Setting::setValue('home_third_slider_is_active', $request->boolean('is_active') ? '1' : '0');
        Setting::setValue('home_third_slider_alt_text', $validated['alt_text'] ?? '');
        Setting::setValue('home_third_slider_button_link', $validated['button_link'] ?? '');

        return redirect()
            ->route('admin.cms.home.index')
            ->with('success', 'Third section slider updated successfully.')
            ->with('active_tab', $request->input('active_tab', 'third'));
    }

    public function productSlider2Settings(): View
    {
        $selectedIds = collect(Setting::getValue('home_product_slider2', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $selectedProducts = Product::query()
            ->whereIn('id', $selectedIds)
            ->select(['id', 'name', 'is_active'])
            ->get()
            ->keyBy('id');

        $orderedSelections = $selectedIds
            ->map(fn ($id) => $selectedProducts->get($id))
            ->filter()
            ->values();

        $productOptions = Product::query()
            ->orderBy('name')
            ->select(['id', 'name', 'is_active'])
            ->get();

        return view('admin.cms.home-product-slider2.settings', [
            'selectedProducts' => $orderedSelections,
            'productOptions' => $productOptions,
            'maxProducts' => self::MAX_PRODUCT_SLIDER2_PRODUCTS,
        ]);
    }

    public function updateProductSlider2Settings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'product_slider2' => ['nullable', 'array', 'max:' . self::MAX_PRODUCT_SLIDER2_PRODUCTS],
            'product_slider2.*' => ['integer', 'exists:products,id'],
        ]);

        $orderedUnique = collect($validated['product_slider2'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        Setting::setValue('home_product_slider2', $orderedUnique->all(), 'json');

        return redirect()
            ->route('admin.cms.home.index')
            ->with('success', 'Product Slider 2 updated successfully.')
            ->with('active_tab', $request->input('active_tab', 'product2'));
    }

    public function homeReviewsSettings(): View
    {
        $selectedIds = collect(Setting::getValue('home_reviews', []))
            ->map(fn ($id) => (int) $id)
            ->filter();

        $selectedReviews = ProductReview::query()
            ->with('product')
            ->whereIn('id', $selectedIds)
            ->get()
            ->keyBy('id');

        $orderedSelections = $selectedIds
            ->map(fn ($id) => $selectedReviews->get($id))
            ->filter()
            ->values();

        $reviewOptions = ProductReview::query()
            ->approved()
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.cms.home-reviews.settings', [
            'selectedReviews' => $orderedSelections,
            'reviewOptions' => $reviewOptions,
            'maxReviews' => self::MAX_HOME_REVIEWS,
            'title' => Setting::getValue('home_reviews_title', ''),
            'description' => Setting::getValue('home_reviews_description', ''),
        ]);
    }

    public function updateHomeReviewsSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'home_reviews' => ['nullable', 'array', 'max:' . self::MAX_HOME_REVIEWS],
            'home_reviews.*' => ['integer', 'exists:product_reviews,id'],
        ]);

        $orderedUnique = collect($validated['home_reviews'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        Setting::setValue('home_reviews', $orderedUnique->all(), 'json');
        Setting::setValue('home_reviews_title', $validated['title'] ?? '');
        Setting::setValue('home_reviews_description', $validated['description'] ?? '');

        return redirect()
            ->route('admin.cms.home.index')
            ->with('success', 'Home reviews updated successfully.')
            ->with('active_tab', $request->input('active_tab', 'reviews'));
    }

    private function deleteStoredFile(?string $path): void
    {
        if (!$path) {
            return;
        }

        Storage::disk('public')->delete($path);
    }
}

