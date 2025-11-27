<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag; ?>
<?php foreach($attributes->onlyProps(['user', 'size' => 'md', 'class' => '']) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $attributes = $attributes->exceptProps(['user', 'size' => 'md', 'class' => '']); ?>
<?php foreach (array_filter((['user', 'size' => 'md', 'class' => '']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
} ?>
<?php $__defined_vars = get_defined_vars(); ?>
<?php foreach ($attributes as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
} ?>
<?php unset($__defined_vars); ?>

<?php
    $hasAvatar = $user->has_avatar ?? false;
    $avatarUrl = $user->avatar_url ?? null;
    $initials = $user->avatar_initials ?? '?';
    $name = $user->name ?? 'User';
    
    // Generate a color based on the user's name for consistent avatar colors
    // Using Bootstrap color classes that are available
    $colors = [
        'bg-danger', 'bg-primary', 'bg-success', 'bg-warning', 
        'bg-info', 'bg-secondary', 'bg-dark', 'bg-purple',
    ];
    $colorIndex = crc32($name) % count($colors);
    $bgColor = $colors[$colorIndex];
    
    // Determine font size based on size prop if class doesn't specify
    $fontSizes = [
        'xs' => '0.625rem',
        'sm' => '0.75rem',
        'md' => '0.875rem',
        'lg' => '1rem',
        'xl' => '1.125rem',
        '2xl' => '1.5rem',
    ];
    $fontSize = $fontSizes[$size] ?? '0.875rem';
?>

<?php if($hasAvatar && $avatarUrl): ?>
    <img src="<?php echo e($avatarUrl); ?>" alt="<?php echo e($name); ?>" class="rounded-circle object-fit-cover <?php echo e($class); ?>" title="<?php echo e($name); ?>">
<?php else: ?>
    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold <?php echo e($bgColor); ?> <?php echo e($class); ?>" title="<?php echo e($name); ?>" style="flex-shrink: 0; font-size: <?php echo e($fontSize); ?>;">
        <?php echo e($initials); ?>

    </div>
<?php endif; ?>

<?php /**PATH D:\xampp\htdocs\ecom123\resources\views/components/avatar.blade.php ENDPATH**/ ?>