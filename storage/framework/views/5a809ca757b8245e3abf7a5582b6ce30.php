

<?php $__env->startSection('title', 'Products'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginala9d931d4f11b4d2850df99e991db1dca = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala9d931d4f11b4d2850df99e991db1dca = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.page-hero','data' => ['breadcrumbs' => [
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Shop']
        ],'eyebrow' => 'The collection','title' => 'Find pieces that move with your day.','description' => 'Use the filters to fine-tune silhouettes, price points, and categories. Every item is in stock and ready to ship from Dubai.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? (array) $attributes->getIterator() : [])); ?>
<?php $component->withName('page-hero'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag && $constructor = (new ReflectionClass(Illuminate\View\AnonymousComponent::class))->getConstructor()): ?>
<?php $attributes = $attributes->except(collect($constructor->getParameters())->map->getName()->all()); ?>
<?php endif; ?>
<?php $component->withAttributes(['breadcrumbs' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Shop']
        ]),'eyebrow' => 'The collection','title' => 'Find pieces that move with your day.','description' => 'Use the filters to fine-tune silhouettes, price points, and categories. Every item is in stock and ready to ship from Dubai.']); ?>
        <a href="<?php echo e(route('cart.index')); ?>" class="button-main bg-white text-black border border-black">View cart</a>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala9d931d4f11b4d2850df99e991db1dca)): ?>
<?php $attributes = $__attributesOriginala9d931d4f11b4d2850df99e991db1dca; ?>
<?php unset($__attributesOriginala9d931d4f11b4d2850df99e991db1dca); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala9d931d4f11b4d2850df99e991db1dca)): ?>
<?php $component = $__componentOriginala9d931d4f11b4d2850df99e991db1dca; ?>
<?php unset($__componentOriginala9d931d4f11b4d2850df99e991db1dca); ?>
<?php endif; ?>

    <section class="py-10 md:py-16">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-2xl p-6 sticky top-6">
                        <h2 class="heading6 text-black mb-6">Filter &amp; Sort</h2>
                        <form method="GET" action="<?php echo e(route('products.index')); ?>" class="flex flex-col gap-4">
                            <div>
                                <label for="search" class="caption2 text-secondary block mb-2">Search</label>
                                <input type="text" id="search" name="search" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line" placeholder="E.g. linen dress" value="<?php echo e(request('search')); ?>">
                            </div>
                            <div>
                                <label for="category" class="caption2 text-secondary block mb-2">Category</label>
                                <select id="category" name="category" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line">
                                    <option value="">All</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" <?php echo e(request('category') == $category->id ? 'selected' : ''); ?>>
                                            <?php echo e($category->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <label class="caption2 text-secondary block mb-2">Price range (<?php echo e(\App\Services\CurrencyService::code()); ?>)</label>
                                <div class="flex items-center gap-2">
                                    <input type="number" class="caption1 flex-1 h-[52px] pl-4 pr-4 rounded-xl border border-line" name="min_price" placeholder="Min" value="<?php echo e(request('min_price')); ?>" min="0" step="0.01">
                                    <span class="caption1 text-secondary">—</span>
                                    <input type="number" class="caption1 flex-1 h-[52px] pl-4 pr-4 rounded-xl border border-line" name="max_price" placeholder="Max" value="<?php echo e(request('max_price')); ?>" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label for="sort" class="caption2 text-secondary block mb-2">Sort</label>
                                    <select id="sort" name="sort" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line">
                                        <option value="name" <?php echo e(request('sort') == 'name' ? 'selected' : ''); ?>>Name</option>
                                        <option value="price" <?php echo e(request('sort') == 'price' ? 'selected' : ''); ?>>Price</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="order" class="caption2 text-secondary block mb-2">Order</label>
                                    <select id="order" name="order" class="caption1 w-full h-[52px] pl-4 pr-4 rounded-xl border border-line">
                                        <option value="asc" <?php echo e(request('order') == 'asc' ? 'selected' : ''); ?>>Asc</option>
                                        <option value="desc" <?php echo e(request('order') == 'desc' ? 'selected' : ''); ?>>Desc</option>
                                    </select>
                                </div>
                            </div>
                            <div class="flex flex-col gap-3">
                                <button type="submit" class="button-main">Apply filters</button>
                                <a href="<?php echo e(route('products.index')); ?>" class="button-main bg-white text-black border border-black text-center">Clear all</a>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="lg:col-span-9">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-3 mb-6">
                        <div>
                            <h2 class="heading6 text-black mb-1">
                                Showing <?php echo e($products->firstItem() ?? 0); ?> – <?php echo e($products->lastItem() ?? 0); ?>

                            </h2>
                            <p class="caption2 text-secondary mb-0"><?php echo e($products->total()); ?> products available</p>
                        </div>
                        <span class="caption2 bg-green text-black px-3 py-1 rounded-full">Secure checkout · Same-day Dubai delivery</span>
                    </div>

                    <?php if($products->count()): ?>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    <div class="bg-white rounded-2xl overflow-hidden h-full flex flex-col">
                                        <a href="<?php echo e(route('products.show', $product->id)); ?>" class="aspect-[3/4] overflow-hidden block">
                                            <img src="<?php echo e($product->image ? asset($product->image) : asset('shopus/assets/images/homepage-one/product-img/product-img-2.webp')); ?>" alt="<?php echo e($product->name); ?>" class="w-full h-full object-cover">
                                        </a>
                                        <div class="p-4 flex flex-col gap-2">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <a href="<?php echo e(route('products.show', $product->id)); ?>" class="text-title font-semibold text-black duration-300 hover:text-green block">
                                                        <?php echo e($product->name); ?>

                                                    </a>
                                                    <?php if($product->category): ?>
                                                        <span class="caption1 text-secondary"><?php echo e($product->category->name); ?></span>
                                                    <?php endif; ?>
                                                </div>
                                                <span class="text-title font-semibold">
                                                    <?php echo e($product->formatted_effective_price); ?>

                                                    <?php if($product->has_discount): ?>
                                                        <span class="caption1 text-secondary line-through ml-1"><?php echo e($product->formatted_price); ?></span>
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                            <span class="caption2 text-secondary">
                                                <?php echo e($product->stock_quantity > 0 ? $product->stock_quantity . ' in stock' : 'Sold out'); ?>

                                            </span>
                                            <div class="mt-auto">
                                                <form action="<?php echo e(route('cart.add')); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit" class="button-main w-full flex items-center justify-center gap-2 whitespace-nowrap" <?php echo e($product->stock_quantity <= 0 ? 'disabled' : ''); ?>>
                                                        <iconify-icon icon="solar:bag-heart-linear" class="text-xl"></iconify-icon>
                                                        <span>Add to bag</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <div class="flex justify-center mt-8">
                            <?php echo e($products->appends(request()->query())->links()); ?>

                        </div>
                    <?php else: ?>
                        <div class="bg-white rounded-2xl p-10 text-center">
                            <h3 class="heading6 mb-3 text-black">Nothing matched those filters</h3>
                            <p class="caption1 text-secondary mb-5">Reset the filters or explore our editor's picks for fresh inspiration.</p>
                            <a href="<?php echo e(route('products.index')); ?>" class="button-main">Reset filters</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp\htdocs\ecom123\resources\views/products/index.blade.php ENDPATH**/ ?>