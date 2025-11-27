<?php

namespace App\Http\Controllers;

use App\Models\AboutPage;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function index(): View
    {
        $page = AboutPage::query()->where('is_active', true)->first();

        // If no page exists, return a default empty page object
        if (!$page) {
            $page = new AboutPage();
            $page->title = 'About Us';
            $page->content = '';
            $page->is_active = false;
        }

        return view('pages.about', [
            'page' => $page,
        ]);
    }
}

