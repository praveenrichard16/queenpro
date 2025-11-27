<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use App\Models\Product;
use App\Services\DripCampaignService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login', [
                'redirect' => route('products.show', $request->input('product_id')),
            ]);
        }

        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        $product = Product::findOrFail($data['product_id']);

        $enquiry = Enquiry::create([
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'subject' => 'Product enquiry: ' . $product->name,
            'message' => $data['message'] ?? null,
            'status' => 'new',
        ]);

        // Trigger drip campaigns for new enquiry
        try {
            app(DripCampaignService::class)->triggerForEnquiry($enquiry);
        } catch (\Exception $e) {
            // Log error but don't fail the enquiry creation
            \Log::error('Failed to trigger drip campaign for enquiry: ' . $e->getMessage());
        }

        return back()->with('success', 'Your enquiry has been submitted. Our team will contact you soon.');
    }
}
