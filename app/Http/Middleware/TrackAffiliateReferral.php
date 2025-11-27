<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Affiliate;
use App\Models\Setting;

class TrackAffiliateReferral
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if referral code exists in URL
        if ($request->has('ref')) {
            $referralCode = $request->query('ref');
            
            try {
                // Verify affiliate exists and is active
                $affiliate = Affiliate::where('affiliate_code', $referralCode)
                    ->where('status', 'active')
                    ->first();
                
                if ($affiliate) {
                    // Get cookie period from settings (default: 30 days)
                    $cookiePeriod = (int) Setting::getValue('affiliate_cookie_period', 30);
                    
                    // Store in cookie (configurable period in days)
                    cookie()->queue('affiliate_ref', $referralCode, 60 * 24 * $cookiePeriod);
                    
                    // Also store in session (only if session is available)
                    if ($request->hasSession()) {
                        session(['affiliate_ref' => $referralCode]);
                    }
                }
            } catch (\Exception $e) {
                // Silently fail if there's a database issue
                // Don't break the request flow
            }
        }
        
        return $next($request);
    }
}
