@extends('layouts.admin')

@section('title', 'Header Toolbar')

@section('content')
    <div class="row g-4">
        <div class="col-12 d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h1 class="h3 mb-0">Header Toolbar</h1>
                <p class="text-muted mb-0">Manage scrolling toolbar items displayed above the header.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.cms.header.toolbar.settings') }}" class="btn btn-outline-secondary">
                    <iconify-icon icon="solar:settings-linear" class="me-1"></iconify-icon>
                    Settings
                </a>
                <a href="{{ route('admin.cms.header.toolbar.create') }}" class="btn btn-primary">
                    <iconify-icon icon="solar:add-circle-linear" class="me-1"></iconify-icon>
                    New Item
                </a>
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
                                <th>Content</th>
                                <th>Link</th>
                                <th>Colors</th>
                                <th>Status</th>
                                <th>Updated</th>
                                <th class="text-end" style="width: 160px;">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($items as $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-secondary">{{ $item->sort_order }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($item->icon)
                                                <span style="font-size: 1.2rem;">{{ $item->icon }}</span>
                                            @endif
                                            <span class="fw-semibold">{{ $item->text }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->link)
                                            <a href="{{ $item->link }}" target="_blank" class="text-decoration-none small">
                                                <iconify-icon icon="solar:link-external-linear" class="me-1"></iconify-icon>
                                                Link
                                            </a>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="small text-muted">BG:</span>
                                                <div class="rounded" style="width: 24px; height: 24px; background: {{ $item->background_color ? ($item->background_color === 'gradient' ? 'linear-gradient(135deg, var(--brand-accent) 0%, #ff6b35 100%)' : $item->background_color) : 'linear-gradient(135deg, var(--brand-accent) 0%, #ff6b35 100%)' }}; border: 1px solid #ddd;"></div>
                                            </div>
                                            <div class="d-flex align-items-center gap-1">
                                                <span class="small text-muted">Text:</span>
                                                <div class="rounded" style="width: 24px; height: 24px; background: {{ $item->text_color ?: '#ffffff' }}; border: 1px solid #ddd;"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($item->is_active)
                                            <span class="badge bg-success-subtle text-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">Hidden</span>
                                        @endif
                                    </td>
                                    <td class="text-muted">
                                        {{ $item->updated_at->format('d M Y, H:i') }}
                                    </td>
                                    <td class="text-end">
                                        <div class="d-inline-flex gap-2">
                                            <a href="{{ route('admin.cms.header.toolbar.edit', $item) }}" class="btn btn-sm btn-outline-secondary">
                                                Edit
                                            </a>
                                            <form action="{{ route('admin.cms.header.toolbar.destroy', $item) }}" method="POST" onsubmit="return confirm('Delete this toolbar item?');">
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
                                    <td colspan="7" class="text-center py-5 text-muted">
                                        No toolbar items created yet. Click "New Item" to add your first toolbar item.
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

