@extends('layouts.app')

@section('title', 'Refund & Return Policy')

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Refund & Return Policy']
        ]"
        eyebrow="Shop with confidence"
        title="If something isn’t quite right, we’ll make it right."
        description="Here’s how returns, exchanges, and refunds work at {{ config('app.name') }}. We keep the process effortless and transparent."
    />

    <section class="page-shell pt-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="page-card p-4 p-lg-5">
                        <article class="d-grid gap-4">
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">1. Return window</h3>
                                <p class="text-soft mb-0">
                                    Returns are accepted within 14 days of delivery for UAE orders, and within 21 days for GCC shipments, provided items are unworn, undamaged, and include original packaging with tags intact.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">2. Non-returnable items</h3>
                                <p class="text-soft mb-0">
                                    For hygiene reasons we cannot accept returns on pierced jewellery, intimate apparel, swimwear bottoms, or personalised pieces unless faulty.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">3. How to initiate a return</h3>
                                <ol class="text-soft mb-0">
                                    <li>Log in to your account and navigate to <a href="{{ route('customer.dashboard') }}">My Dashboard</a>.</li>
                                    <li>Select the relevant order and choose <strong>Request Return</strong>.</li>
                                    <li>Our concierge team will schedule a pickup or provide drop-off instructions.</li>
                                </ol>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">4. Refunds</h3>
                                <p class="text-soft mb-0">
                                    Approved returns are refunded to the original payment method within 7 business days of inspection. Prefer store credit? We’ll issue it instantly once the return is approved.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">5. Exchanges</h3>
                                <p class="text-soft mb-0">
                                    Complimentary size exchanges are available on eligible items within the UAE. International exchanges require a new order to be placed once the original item is refunded.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">6. Damaged or incorrect items</h3>
                                <p class="text-soft mb-0">
                                    If you receive a faulty or incorrect item, please contact us within 48 hours at
                                    <a href="mailto:care@{{ \Illuminate\Support\Str::slug(config('app.name'), '') }}.com">care@{{ \Illuminate\Support\Str::slug(config('app.name'), '') }}.com</a>
                                    with photos so we can arrange a swift replacement.
                                </p>
                            </section>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@extends('layouts.app')


