@extends('layouts.app')

@section('title', 'Terms & Conditions')

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Terms & Conditions']
        ]"
        eyebrow="Our agreement"
        title="Please review these terms before using {{ config('app.name') }}."
        description="By placing an order you agree to the policies below. We keep things clear, transparent, and aligned with UAE regulations."
    />

    <section class="page-shell pt-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="page-card p-4 p-lg-5">
                        <article class="d-grid gap-4">
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">1. Eligibility</h3>
                                <p class="text-soft mb-0">
                                    You must be at least 18 years old—or purchasing under the supervision of a guardian—to use our platform. Orders placed through unauthorised bots or automated scripts are strictly prohibited.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">2. Orders &amp; acceptance</h3>
                                <p class="text-soft mb-0">
                                    All orders are subject to stock availability and payment verification. We reserve the right to cancel any order if items become unavailable or if fraudulent activity is suspected. You will be notified and refunded immediately.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">3. Pricing &amp; payment</h3>
                                <p class="text-soft mb-0">
                                    Prices are listed in {{ \App\Services\CurrencyService::code() }} and inclusive of VAT unless noted otherwise. We accept major credit cards and secure digital wallets. Payment data is processed exclusively by certified gateways and never stored on our servers.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">4. Shipping</h3>
                                <p class="text-soft mb-0">
                                    Orders within the UAE typically ship within 1–3 business days. International shipments may be subject to customs duties and extended processing times that are the responsibility of the recipient.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">5. Returns &amp; exchanges</h3>
                                <p class="text-soft mb-0">
                                    Items may be returned within 14 days of delivery in original condition with tags attached. Please review our
                                    <a href="{{ route('refund') }}">Refund &amp; Return Policy</a> for full details on eligible products and timelines.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">6. Intellectual property</h3>
                                <p class="text-soft mb-0">
                                    All content—including imagery, copy, and logos—remains the property of {{ config('app.name') }} or its partners. You may not reproduce or redistribute materials without express permission.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">7. Governing law</h3>
                                <p class="text-soft mb-0">
                                    These terms are governed by the laws of the United Arab Emirates. Any dispute shall be resolved exclusively through the courts of Dubai.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">8. Questions</h3>
                                <p class="text-soft mb-0">
                                    For clarifications or support please email
                                    <a href="mailto:legal@{{ \Illuminate\Support\Str::slug(config('app.name'), '') }}.com">legal@{{ \Illuminate\Support\Str::slug(config('app.name'), '') }}.com</a>.
                                </p>
                            </section>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

