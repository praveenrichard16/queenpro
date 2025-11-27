@extends('layouts.admin')

@section('title', 'Product Slides')

@section('content')
    <div class="dashboard-main-body">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-24">
            <div>
                <h6 class="fw-semibold mb-1">Homepage Product Slides</h6>
                <p class="text-secondary-light mb-0">Select the exact products to highlight after the 2nd section slider. Order is preserved.</p>
            </div>
            <a href="{{ route('admin.cms.home.sliders.index') }}" class="btn btn-outline-secondary radius-12">
                <iconify-icon icon="solar:alt-arrow-left-linear" class="me-1"></iconify-icon>
                Back to home sliders
            </a>
        </div>

        <div class="card border-0">
            <div class="card-body p-24">
                <form method="POST" action="{{ route('admin.cms.home.product-slides.update') }}" class="row g-4">
                    @csrf

                    <div class="col-lg-5">
                        <label class="form-label text-secondary-light">Add products to the carousel</label>
                        <select id="productSelect" class="form-select bg-neutral-50 radius-12 h-56-px">
                            <option value="">Search or choose a product</option>
                            @foreach($productOptions as $product)
                                <option value="{{ $product->id }}"
                                        data-active="{{ $product->is_active ? '1' : '0' }}">
                                    {{ $product->name }} @unless($product->is_active) — Inactive @endunless
                                </option>
                            @endforeach
                        </select>
                        <div class="d-flex gap-2 mt-3">
                            <button type="button" id="addProductBtn" class="btn btn-primary radius-12 flex-grow-1">
                                <iconify-icon icon="solar:add-circle-linear" class="me-1"></iconify-icon>
                                Add to list
                            </button>
                            <button type="button" id="clearProductsBtn" class="btn btn-outline-secondary radius-12">
                                Clear all
                            </button>
                        </div>
                        <div class="form-text mt-2" id="productSlidesLimitMessage">
                            You can feature up to {{ $maxSlides }} products. Scroll order = display order on the storefront.
                        </div>
                        @error('product_slides') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                        @error('product_slides.*') <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-lg-7">
                        <label class="form-label text-secondary-light d-flex justify-content-between">
                            <span>Selected products</span>
                            <span class="text-muted small">Use ↑ / ↓ controls to reorder</span>
                        </label>
                        <div id="selectedProductsEmpty" class="border rounded-3 p-4 text-center text-secondary-light {{ $selectedProducts->isNotEmpty() ? 'd-none' : '' }}" style="border-style: dashed;">
                            No products selected yet.
                        </div>

                        <ul class="list-group gap-3" id="selectedProductsList">
                            @foreach($selectedProducts as $product)
                                <li class="list-group-item d-flex align-items-center justify-content-between radius-12 border flex-wrap gap-3 product-slide-item" data-id="{{ $product->id }}">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="badge bg-neutral-100 text-secondary-light border order-index"></span>
                                        <div>
                                            <div class="fw-semibold">{{ $product->name }}</div>
                                            <div class="text-secondary-light small">
                                                @unless($product->is_active)
                                                    <span class="text-danger">Inactive</span>
                                                @endunless
                                            </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 ms-auto">
                                        <button type="button" class="btn btn-sm btn-outline-secondary move-product-btn" data-direction="-1">
                                            <iconify-icon icon="solar:alt-arrow-up-linear"></iconify-icon>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary move-product-btn" data-direction="1">
                                            <iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-product-btn">
                                            <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                                        </button>
                                    </div>
                                    <input type="hidden" name="product_slides[]" value="{{ $product->id }}">
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="col-12 d-flex gap-3 mt-3">
                        <button type="submit" class="btn btn-primary radius-12 px-24">
                            <iconify-icon icon="solar:floppy-disk-line-duotone" class="me-1"></iconify-icon>
                            Save product slides
                        </button>
                        <a href="{{ route('admin.cms.home.sliders.index') }}" class="btn btn-outline-secondary radius-12 px-24">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const maxSlides = {{ $maxSlides }};
        const selectedList = document.getElementById('selectedProductsList');
        const selectedEmpty = document.getElementById('selectedProductsEmpty');
        const productSelect = document.getElementById('productSelect');
        const addBtn = document.getElementById('addProductBtn');
        const clearBtn = document.getElementById('clearProductsBtn');
        const selectedIds = new Set(@json($selectedProducts->pluck('id')));

        const updateEmptyState = () => {
            selectedEmpty.classList.toggle('d-none', selectedList.children.length > 0);
            updateOrderBadges();
        };

        const updateOrderBadges = () => {
            [...selectedList.children].forEach((item, index) => {
                const badge = item.querySelector('.order-index');
                if (badge) {
                    badge.textContent = index + 1;
                }
            });
        };

        const createListItem = (product) => {
            const li = document.createElement('li');
            li.className = 'list-group-item d-flex align-items-center justify-content-between radius-12 border flex-wrap gap-3 product-slide-item';
            li.dataset.id = product.id;
            li.innerHTML = `
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-neutral-100 text-secondary-light border order-index"></span>
                    <div>
                        <div class="fw-semibold">${product.name}</div>
                        <div class="text-secondary-light small">
                            ${product.is_active ? '' : '<span class="text-danger">Inactive</span>'}
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-secondary move-product-btn" data-direction="-1">
                        <iconify-icon icon="solar:alt-arrow-up-linear"></iconify-icon>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary move-product-btn" data-direction="1">
                        <iconify-icon icon="solar:alt-arrow-down-linear"></iconify-icon>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger remove-product-btn">
                        <iconify-icon icon="solar:trash-bin-trash-line-duotone"></iconify-icon>
                    </button>
                </div>
                <input type="hidden" name="product_slides[]" value="${product.id}">
            `;
            return li;
        };

        addBtn.addEventListener('click', () => {
            const productId = parseInt(productSelect.value, 10);
            if (!productId || selectedIds.has(productId)) {
                return;
            }

            if (selectedIds.size >= maxSlides) {
                alert(`You can only feature up to ${maxSlides} products.`);
                return;
            }

            const option = productSelect.options[productSelect.selectedIndex];
            const product = {
                id: productId,
                name: option.textContent.trim(),
                is_active: option.dataset.active === '1',
            };

            selectedIds.add(productId);
            selectedList.appendChild(createListItem(product));
            productSelect.value = '';
            updateEmptyState();
        });

        clearBtn.addEventListener('click', () => {
            if (!selectedList.children.length) {
                return;
            }
            if (confirm('Remove all selected products?')) {
                selectedIds.clear();
                selectedList.innerHTML = '';
                updateEmptyState();
            }
        });

        selectedList.addEventListener('click', (event) => {
            const button = event.target.closest('button');
            if (!button) {
                return;
            }

            const item = button.closest('.product-slide-item');
            if (!item) {
                return;
            }

            if (button.classList.contains('remove-product-btn')) {
                const id = parseInt(item.dataset.id, 10);
                selectedIds.delete(id);
                item.remove();
                updateEmptyState();
                return;
            }

            if (button.classList.contains('move-product-btn')) {
                const direction = parseInt(button.dataset.direction, 10);
                if (direction === -1 && item.previousElementSibling) {
                    selectedList.insertBefore(item, item.previousElementSibling);
                } else if (direction === 1 && item.nextElementSibling) {
                    selectedList.insertBefore(item.nextElementSibling, item);
                }
                updateOrderBadges();
            }
        });

        updateEmptyState();
    });
</script>
@endpush

