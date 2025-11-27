<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffiliateController extends Controller
{
    public function index(): View
    {
        // Check if affiliate program is enabled
        $affiliateEnabled = Setting::getValue('affiliate_enabled', '1') !== '0';
        
        // Get default commission rate for display
        $defaultCommissionRate = Setting::getValue('affiliate_default_commission_rate', '10.00');
        
        // Get program description if available
        $programDescription = Setting::getValue('affiliate_program_description', '');
        
        // Check if user is already an affiliate
        $userAffiliate = null;
        if (auth()->check()) {
            $userAffiliate = auth()->user()->affiliate;
        }
        
        return view('pages.affiliate', compact(
            'affiliateEnabled',
            'defaultCommissionRate',
            'programDescription',
            'userAffiliate'
        ));
    }
}

