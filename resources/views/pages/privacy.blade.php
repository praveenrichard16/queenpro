@extends('layouts.app')

@section('title', 'Privacy Policy')

@php
    $updatedAt = now()->format('F j, Y');
@endphp

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Privacy Policy']
        ]"
        eyebrow="Your data, protected"
        title="We treat your personal information with discretion."
        description="This policy explains why we collect data, how it’s safeguarded, and the rights you have over your information."
    />

    <section class="page-shell pt-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="page-card p-4 p-lg-5">
                        <p class="small text-soft text-end mb-4">Last updated: {{ $updatedAt }}</p>
                        <article class="d-grid gap-4">
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">1. Overview</h3>
                                <p class="text-soft mb-0">
                                    This policy outlines how {{ config('app.name') }} (“we”, “us”) collects, uses, and protects your information whenever you browse or make a purchase through our digital channels. By using our services, you consent to the practices described below.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">2. Data we collect</h3>
                                <ul class="text-soft mb-0">
                                    <li>Account details such as name, email address, delivery location, and contact number.</li>
                                    <li>Payment and transaction information processed securely through PCI-DSS compliant partners.</li>
                                    <li>Browsing behaviour, device identifiers, and analytics data captured via cookies to improve your experience.</li>
                                </ul>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">3. How we use your information</h3>
                                <p class="text-soft mb-0">
                                    We use collected data to fulfil orders, personalise recommendations, refine site performance, prevent fraudulent activity, and communicate important updates or promotions you opt into.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">4. Sharing &amp; third parties</h3>
                                <p class="text-soft mb-0">
                                    We never sell your personal data. Information is shared only with logistics partners, payment processors, and technology providers who enable our core services—all bound by strict confidentiality obligations.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">5. Your rights</h3>
                                <p class="text-soft mb-0">
                                    You may request access, correction, or deletion of the personal data we hold about you. You can withdraw marketing consent at any time using the unsubscribe links inside our communications or by contacting us directly.
                                </p>
                            </section>
                            <section>
                                <h3 class="h6 fw-semibold text-dark mb-2">6. Questions</h3>
                                <p class="text-soft mb-0">
                                    Email <a href="mailto:privacy@{{ \Illuminate\Support\Str::slug(config('app.name'), '') }}.com">privacy@{{ \Illuminate\Support\Str::slug(config('app.name'), '') }}.com</a> or reach our compliance team via the <a href="{{ route('contact') }}">contact page</a>.
                                </p>
                            </section>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

