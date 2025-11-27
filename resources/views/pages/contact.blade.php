@extends('layouts.app')

@section('title', 'Contact')

@section('content')
    <x-page-hero
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Contact']
        ]"
        eyebrow="Concierge support"
        title="We’re here to help—styling, sizing, gifting, or anything in between."
        description="Drop us a note, call the studio, or swing by our Business Bay lounge. We respond to every enquiry within one business day."
    />

    <section class="py-10 md:py-16">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-5">
                    <div class="flex flex-col gap-6">
                        <div class="bg-white rounded-2xl p-6">
                            <h3 class="heading6 text-black mb-6">Talk to us directly</h3>
                            <ul class="flex flex-col gap-4 list-none p-0 mb-0">
                                <li>
                                    <span class="caption2 bg-green text-black px-3 py-1 rounded-full inline-block mb-2">Phone</span>
                                    <p class="caption1 text-secondary mb-0"><a href="tel:+971555123456" class="text-black duration-300 hover:text-green">+971 555 123 456</a></p>
                                </li>
                                <li>
                                    <span class="caption2 bg-green text-black px-3 py-1 rounded-full inline-block mb-2">Email</span>
                                    <p class="caption1 text-secondary mb-0"><a href="mailto:support@{{ \Illuminate\Support\Str::slug(config('app.name'), '') }}.com" class="text-black duration-300 hover:text-green">support@{{ \Illuminate\Support\Str::slug(config('app.name'), '') }}.com</a></p>
                                </li>
                                <li>
                                    <span class="caption2 bg-green text-black px-3 py-1 rounded-full inline-block mb-2">WhatsApp</span>
                                    <p class="caption1 text-secondary mb-0"><a href="https://wa.me/971555123456" target="_blank" rel="noopener" class="text-black duration-300 hover:text-green">Chat instantly</a></p>
                                </li>
                                <li>
                                    <span class="caption2 bg-green text-black px-3 py-1 rounded-full inline-block mb-2">Support hours</span>
                                    <p class="caption1 text-secondary mb-0">Sunday – Thursday · 09:00 – 21:00 GST</p>
                                </li>
                            </ul>
                        </div>
                        <div class="bg-white rounded-2xl overflow-hidden">
                            <div class="p-6 pb-0">
                                <h3 class="heading6 text-black mb-3">Flagship showroom</h3>
                                <p class="caption1 text-secondary mb-4">Level 29, Vision Tower, Business Bay, Dubai, United Arab Emirates</p>
                            </div>
                            <div class="aspect-[16/9] overflow-hidden">
                                <iframe
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3608.34949646277!2d55.27218781501436!3d25.19951483787019!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f43086c0a7a61%3A0x311a2f50b0c0356!2sBusiness%20Bay%2C%20Dubai!5e0!3m2!1sen!2sae!4v1731111111111"
                                    allowfullscreen
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                    class="w-full h-full"
                                ></iframe>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-2xl p-6 md:p-10 h-full">
                        <h3 class="heading6 text-black mb-4">Send us a message</h3>
                        <p class="caption1 text-secondary mb-6">Tell us how we can help—one of our stylists or concierge specialists will get back to you within 24 hours.</p>
                        <form class="flex flex-col gap-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="contact-name" class="caption2 text-secondary block mb-2">Name</label>
                                    <input type="text" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line" id="contact-name" placeholder="Full name">
                                </div>
                                <div>
                                    <label for="contact-email" class="caption2 text-secondary block mb-2">Email</label>
                                    <input type="email" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line" id="contact-email" placeholder="you@example.com">
                                </div>
                            </div>
                            <div>
                                <label for="contact-subject" class="caption2 text-secondary block mb-2">Subject</label>
                                <input type="text" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line" id="contact-subject" placeholder="How can we assist?">
                            </div>
                            <div>
                                <label for="contact-message" class="caption2 text-secondary block mb-2">Message</label>
                                <textarea id="contact-message" class="caption1 w-full pl-4 pr-4 pt-3 pb-3 rounded-xl border border-line" rows="5" placeholder="Share details and our concierge team will reply shortly."></textarea>
                            </div>
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <button type="submit" class="button-main">Send message</button>
                                <p class="caption2 text-secondary mb-0">By messaging us you agree to our <a href="{{ route('privacy') }}" class="text-black underline">privacy policy</a>.</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

