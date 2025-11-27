<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProductReviewController extends Controller
{
    public function index(Request $request): View
    {
        if (!Schema::hasTable('product_reviews')) {
            $reviews = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1);
            $products = \App\Models\Product::orderBy('name')->get();
            return view('admin.reviews.index', compact('reviews', 'products'));
        }

        $query = ProductReview::with(['product', 'user']);

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(20);
        $products = \App\Models\Product::orderBy('name')->get();

        return view('admin.reviews.index', compact('reviews', 'products'));
    }

    public function approve(ProductReview $review): RedirectResponse
    {
        $review->update(['is_approved' => true]);

        return back()->with('success', 'Review approved successfully.');
    }

    public function reject(ProductReview $review): RedirectResponse
    {
        $review->update(['is_approved' => false]);

        return back()->with('success', 'Review rejected.');
    }

    public function feature(ProductReview $review): RedirectResponse
    {
        $review->update(['is_featured' => !$review->is_featured]);

        return back()->with('success', 'Review featured status updated.');
    }

    public function destroy(ProductReview $review): RedirectResponse
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}

