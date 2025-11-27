<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps([
    'title' => null,
    'breadcrumbs' => [],
]) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps([
    'title' => null,
    'breadcrumbs' => [],
]); ?>
<?php foreach (array_filter(([
    'title' => null,
    'breadcrumbs' => [],
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
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
?>

<section class="py-6 border-b border-line text-center" style="<?php echo e($sectionStyle); ?>">
    <?php if($breadcrumbBgImage): ?>
        <div style="position:absolute; inset:0; background-color: rgba(0, 0, 0, <?php echo e($breadcrumbOverlayOpacity); ?>);"></div>
    <?php endif; ?>
    <div class="container relative">
        <nav aria-label="breadcrumb">
            <ol class="flex justify-center flex-wrap items-center gap-2 caption1 font-semibold tracking-wide uppercase <?php echo e($textClass); ?>">
                <?php $__currentLoopData = $crumbCollection; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $crumb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="flex items-center gap-2">
                        <a href="<?php echo e($crumb['url']); ?>" class="hover:text-green transition-colors <?php echo e($textClass); ?>">
                            <?php echo e($crumb['label']); ?>

                        </a>
                        <?php if(!$loop->last): ?>
                            <span>/</span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ol>
        </nav>
    </div>
</section>

<?php /**PATH D:\xampp\htdocs\ecom123\resources\views/components/page-hero.blade.php ENDPATH**/ ?>