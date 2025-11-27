<?php

namespace App\Http\Controllers;

use App\Models\BlogComment;
use App\Models\BlogPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlogCommentController extends Controller
{
    public function store(Request $request, BlogPost $post): RedirectResponse
    {
        $request->validate([
            'content' => ['required', 'string'],
            'author_name' => ['required', 'string', 'max:255'],
            'author_email' => ['required', 'email', 'max:255'],
            'parent_id' => ['nullable', 'exists:blog_comments,id'],
        ]);

        BlogComment::create([
            'blog_post_id' => $post->id,
            'user_id' => Auth::id(),
            'parent_id' => $request->parent_id,
            'author_name' => $request->author_name,
            'author_email' => $request->author_email,
            'content' => $request->content,
            'is_approved' => false, // Requires admin approval
        ]);

        return back()->with('success', 'Thank you for your comment! It will be published after approval.');
    }
}

