<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TicketPriority;
use App\Http\Controllers\Controller;
use App\Models\TicketCategory;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportCategoryController extends Controller
{
    public function index(): View
    {
        $categories = TicketCategory::query()
            ->withCount('tickets')
            ->orderBy('name')
            ->paginate(12);

        return view('admin.support.categories.index', [
            'categories' => $categories,
        ]);
    }

    public function create(): View
    {
        return view('admin.support.categories.create', [
            'priorities' => TicketPriority::cases(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'default_priority' => ['nullable', 'in:'.implode(',', TicketPriority::values())],
            'is_active' => ['nullable', 'boolean'],
        ]);

        TicketCategory::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'default_priority' => $data['default_priority'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.support.categories.index')->with('success', 'Category created successfully.');
    }

    public function edit(TicketCategory $category): View
    {
        return view('admin.support.categories.edit', [
            'category' => $category,
            'priorities' => TicketPriority::cases(),
        ]);
    }

    public function update(Request $request, TicketCategory $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:500'],
            'default_priority' => ['nullable', 'in:'.implode(',', TicketPriority::values())],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'default_priority' => $data['default_priority'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.support.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(TicketCategory $category): RedirectResponse
    {
        $category->update(['is_active' => false]);

        return redirect()->route('admin.support.categories.index')->with('success', 'Category archived successfully.');
    }
}

