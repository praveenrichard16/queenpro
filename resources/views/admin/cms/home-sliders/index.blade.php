@extends('layouts.admin')

@section('title', 'Home Slider')

@section('content')
    <div class="row g-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="h3 mb-0">Homepage Slider</h1>
                <p class="text-muted mb-0">Manage up to three hero slides for the storefront homepage.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.cms.home.seo.settings') }}" class="btn btn-outline-secondary">
                    <iconify-icon icon="solar:seo-linear" class="me-1"></iconify-icon>
                    SEO Settings
                </a>
                <a href="{{ route('admin.cms.home.sliders.create') }}"
                   class="btn btn-primary {{ $canCreate ? '' : 'disabled' }}"
                   {{ $canCreate ? '' : 'aria-disabled=true' }}>
                    <iconify-icon icon="solar:add-circle-linear" class="me-1"></iconify-icon>
                    New Slide
                </a>
                @unless($canCreate)
                    <p class="small text-muted mb-0 mt-2">Maximum of three slides reached.</p>
                @endunless
            </div>
        </div>

        <div class="col-12">
            <div class="card radius-16 border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="bg-light">
                            <tr>
                                <th style="width: 80px;">Order</th>
                                <th>Preview</th>
                                <th>Title</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th class="text-end" style="width: 160px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($sliders as $slider)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $slider->sort_order }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ asset('storage/' . $slider->desktop_image_path) }}"
                                                 alt="{{ $slider->alt_text ?? $slider->title ?? 'Slide preview' }}"
                                                 class="rounded border"
                                                 style="width:120px;height:68px;object-fit:cover;"
                                                 onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'120\' height=\'68\'%3E%3Crect fill=\'%23ddd\' width=\'120\' height=\'68\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\' font-size=\'12\'%3EImage not found%3C/text%3E%3C/svg%3E';">
                                            <div class="text-muted small">
                                                <div>
                                                    <iconify-icon icon="solar:monitor-line-duotone" class="me-1"></iconify-icon>
                                                    1600×900
                                                </div>
                                                <div>
                                                    <iconify-icon icon="solar:phone-line-duotone" class="me-1"></iconify-icon>
                                                    800×1300
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $slider->title ?? 'Untitled slide' }}</div>
                                        @if($slider->button_text && $slider->button_link)
                                            <div class="text-muted small">{{ $slider->button_text }} → {{ $slider->button_link }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($slider->is_active)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Hidden</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        {{ $slider->updated_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.cms.home.sliders.edit', $slider) }}" class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.cms.home.sliders.destroy', $slider) }}" method="POST" onsubmit="return confirm('Delete this slide?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        No slides created yet. Click “New Slide” to add your first hero image.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

