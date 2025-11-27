<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogComment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class BlogCommentController extends Controller
{
    public function index(Request $request): View
    {
        if (!Schema::hasTable('blog_comments')) {
            $comments = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 20, 1);
            $posts = \App\Models\BlogPost::orderBy('title')->get();
            return view('admin.comments.index', compact('comments', 'posts'));
        }

        $query = BlogComment::with(['blogPost', 'user', 'parent']);

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true)->where('is_spam', false);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false)->where('is_spam', false);
            } elseif ($request->status === 'spam') {
                $query->where('is_spam', true);
            }
        }

        if ($request->filled('post_id')) {
            $query->where('blog_post_id', $request->post_id);
        }

        $comments = $query->orderBy('created_at', 'desc')->paginate(20);
        $posts = \App\Models\BlogPost::orderBy('title')->get();

        return view('admin.comments.index', compact('comments', 'posts'));
    }

    public function approve(BlogComment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => true, 'is_spam' => false]);

        return back()->with('success', 'Comment approved successfully.');
    }

    public function reject(BlogComment $comment): RedirectResponse
    {
        $comment->update(['is_approved' => false]);

        return back()->with('success', 'Comment rejected.');
    }

    public function markSpam(BlogComment $comment): RedirectResponse
    {
        $comment->update(['is_spam' => true, 'is_approved' => false]);

        return back()->with('success', 'Comment marked as spam.');
    }

    public function destroy(BlogComment $comment): RedirectResponse
    {
        $comment->delete();

        return back()->with('success', 'Comment deleted successfully.');
    }
}

