@props([
    'title' => null,
    'breadcrumbs' => [],
])

@php
    $crumbCollection = collect($breadcrumbs)
        ->map(function ($crumb) {
            return [
                'label' => $crumb['label'] ?? null,
                'url' => $crumb['url'] ?? url()->current(),
            ];
        })
        ->filter(fn ($crumb) => filled($crumb['label']))
        ->values();

    if ($crumbCollection->isEmpty()) {
        $derivedTitle = $title;
        if (!$derivedTitle) {
            $derivedTitle = \Illuminate\Support\Str::title(str_replace('-', ' ', last(request()->segments()) ?? 'Home'));
        }
        $crumbCollection = collect([
            ['label' => 'Home', 'url' => route('home')],
            ['label' => $derivedTitle, 'url' => url()->current()],
        ]);
    }

    $breadcrumbBgImage = \App\Models\Setting::getValue('breadcrumb_background_image', '');
    $breadcrumbOverlayOpacity = \App\Models\Setting::getValue('breadcrumb_overlay_opacity', '0.4');
    $breadcrumbBgColor = \App\Models\Setting::getValue('breadcrumb_background_color', '#f3f4f6');

    $sectionStyle = $breadcrumbBgImage
        ? 'background-image: url(' . \Illuminate\Support\Facades\Storage::url($breadcrumbBgImage) . '); background-size: cover; background-position: center; position: relative;'
        : 'background-color: ' . $breadcrumbBgColor . ';';

    $textClass = $breadcrumbBgImage ? 'text-white' : 'text-secondary';
@endphp

<section class="py-6 border-b border-line text-center" style="{{ $sectionStyle }}">
    @if($breadcrumbBgImage)
        <div style="position:absolute; inset:0; background-color: rgba(0, 0, 0, {{ $breadcrumbOverlayOpacity }});"></div>
    @endif
    <div class="container relative">
        <nav aria-label="breadcrumb">
            <ol class="flex justify-center flex-wrap items-center gap-2 caption1 font-semibold tracking-wide uppercase {{ $textClass }}">
                @foreach($crumbCollection as $crumb)
                    <li class="flex items-center gap-2">
                        <a href="{{ $crumb['url'] }}" class="hover:text-green transition-colors {{ $textClass }}">
                            {{ $crumb['label'] }}
                        </a>
                        @if(!$loop->last)
                            <span>/</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
</section>

