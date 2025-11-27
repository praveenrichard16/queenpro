<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreOfferRequest;
use App\Http\Requests\Admin\UpdateOfferRequest;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Coupon::with(['products', 'categories', 'brands', 'users']);

        // Search by code
        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        // Filter by offer type
        if ($request->filled('offer_type')) {
            $query->where('offer_type', $request->offer_type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user segment
        if ($request->filled('user_segment')) {
            $query->where('user_segment', $request->user_segment);
        }

        $offers = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        return view('admin.offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $offer = new Coupon();
        $products = Product::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $users = User::where('is_admin', false)->orderBy('name')->get();

        return view('admin.offers.form', compact('offer', 'products', 'categories', 'brands', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOfferRequest $request): RedirectResponse
    {
        $data = $request->validated();

        // Create the coupon/offer
        $offer = Coupon::create($data);

        // Attach relationships based on offer type
        $this->attachRelationships($offer, $request);

        return redirect()->route('admin.offers.index')
            ->with('success', 'Offer created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $offer): View
    {
        $products = Product::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $brands = Brand::where('is_active', true)->orderBy('name')->get();
        $users = User::where('is_admin', false)->orderBy('name')->get();

        // Load relationships
        $offer->load(['products', 'categories', 'brands', 'users']);

        return view('admin.offers.form', compact('offer', 'products', 'categories', 'brands', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOfferRequest $request, Coupon $offer): RedirectResponse
    {
        $data = $request->validated();

        // Update the coupon/offer
        $offer->update($data);

        // Sync relationships based on offer type
        $this->syncRelationships($offer, $request);

        return redirect()->route('admin.offers.index')
            ->with('success', 'Offer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $offer): RedirectResponse
    {
        $offer->delete();

        return redirect()->route('admin.offers.index')
            ->with('success', 'Offer deleted successfully.');
    }

    /**
     * Attach relationships for a new offer
     */
    protected function attachRelationships(Coupon $offer, Request $request): void
    {
        switch ($offer->offer_type) {
            case 'product':
                if ($request->filled('product_ids')) {
                    $offer->products()->attach($request->product_ids);
                }
                break;

            case 'category':
                if ($request->filled('category_ids')) {
                    $offer->categories()->attach($request->category_ids);
                }
                break;

            case 'brand':
                if ($request->filled('brand_ids')) {
                    $offer->brands()->attach($request->brand_ids);
                }
                break;

            case 'user':
                if ($request->filled('user_ids')) {
                    $offer->users()->attach($request->user_ids);
                }
                break;
        }
    }

    /**
     * Sync relationships for an existing offer
     */
    protected function syncRelationships(Coupon $offer, Request $request): void
    {
        switch ($offer->offer_type) {
            case 'product':
                $offer->products()->sync($request->product_ids ?? []);
                break;

            case 'category':
                $offer->categories()->sync($request->category_ids ?? []);
                break;

            case 'brand':
                $offer->brands()->sync($request->brand_ids ?? []);
                break;

            case 'user':
                $offer->users()->sync($request->user_ids ?? []);
                break;

            default:
                // Clear all relationships for non-specific offer types
                $offer->products()->detach();
                $offer->categories()->detach();
                $offer->brands()->detach();
                $offer->users()->detach();
                break;
        }
    }
}

