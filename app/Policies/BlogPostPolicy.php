<?php

namespace App\Policies;

use App\Models\BlogPost;
use App\Models\User;

class BlogPostPolicy
{
    public function before(?User $user, string $ability): ?bool
    {
        if ($user?->is_admin) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return false;
    }

    public function view(User $user, BlogPost $blogPost): bool
    {
        return false;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, BlogPost $blogPost): bool
    {
        return false;
    }

    public function delete(User $user, BlogPost $blogPost): bool
    {
        return false;
    }
}


