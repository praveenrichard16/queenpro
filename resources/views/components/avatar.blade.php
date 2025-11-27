@props(['user', 'size' => 'md', 'class' => ''])

@php
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
@endphp

@if($hasAvatar && $avatarUrl)
    <img src="{{ $avatarUrl }}" alt="{{ $name }}" class="rounded-circle object-fit-cover {{ $class }}" title="{{ $name }}">
@else
    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-semibold {{ $bgColor }} {{ $class }}" title="{{ $name }}" style="flex-shrink: 0; font-size: {{ $fontSize }};">
        {{ $initials }}
    </div>
@endif

