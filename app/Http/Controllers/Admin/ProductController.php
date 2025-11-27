<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Tag;
use App\Models\TaxClass;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductController extends Controller
{
	public function index(Request $request)
	{
		$query = Product::query()->with('category');

		if ($request->filled('search')) {
			$term = $request->get('search');
			$query->where(function($q) use ($term) {
				$q->where('name', 'like', "%$term%")->orWhere('description', 'like', "%$term%");
			});
		}

		if ($request->filled('category')) {
			$query->where('category_id', $request->get('category'));
		}

		if ($request->filled('status')) {
			$query->where('is_active', $request->boolean('status'));
		}

		$products = $query->orderBy('created_at', 'desc')->paginate(10)->appends($request->query());
		$categories = Category::orderBy('name')->get();

		return view('admin/products/index', compact('products', 'categories'));
	}

	protected function categoryOptions(): array
	{
		$categories = Category::with('children.children')->orderBy('name')->get();
		$root = $categories->whereNull('parent_id');

		$options = [];

		$walker = function ($items, $depth = 0) use (&$walker, &$options) {
			foreach ($items as $item) {
				$options[$item->id] = str_repeat('— ', $depth) . $item->name;
				if ($item->children->isNotEmpty()) {
					$walker($item->children, $depth + 1);
				}
			}
		};

		$walker($root);

		return $options;
	}

	public function create()
	{
		$categories = $this->categoryOptions();
		$tags = Tag::orderBy('name')->get();
		$brands = Brand::active()->orderBy('name')->get();
		$attributes = Attribute::active()->with('values')->orderBy('name')->get();
		$taxClasses = Schema::hasTable('tax_classes') ? TaxClass::active()->orderBy('name')->get() : collect([]);
		$product = new Product();
		return view('admin/products/form', compact('product', 'categories', 'tags', 'brands', 'attributes', 'taxClasses'));
	}

	public function store(Request $request)
	{
		$data = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:products,slug'],
			'description' => ['nullable', 'string'],
			'short_description' => ['nullable', 'string'],
			'long_description' => ['nullable', 'string'],
			'specification' => ['nullable', 'string'],
			'price' => ['required', 'numeric', 'min:0'],
			'selling_price' => ['nullable', 'numeric', 'min:0'],
			'category_id' => ['nullable', 'exists:categories,id'],
			'categories' => ['nullable', 'array'],
			'categories.*' => ['exists:categories,id'],
			'brand_id' => ['nullable', 'exists:brands,id'],
			'stock_quantity' => ['required', 'integer', 'min:0'],
			'is_active' => ['sometimes', 'boolean'],
			'is_featured' => ['sometimes', 'boolean'],
			'enable_countdown_timer' => ['sometimes', 'boolean'],
			'countdown_timer_end' => ['nullable', 'required_if:enable_countdown_timer,1', 'date', 'after:now'],
			'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:width=1080,height=1080'],
			'tags' => ['nullable', 'array'],
			'tags.*' => ['exists:tags,id'],
			'attributes' => ['nullable', 'array'],
			'attributes.*.attribute_id' => ['required', 'exists:attributes,id'],
			'attributes.*.attribute_value_id' => ['nullable', 'exists:attribute_values,id'],
			'attributes.*.custom_value' => ['nullable', 'string', 'max:255'],
			'gallery' => ['nullable', 'array'],
			'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:width=1080,height=1080'],
			'tax_class_id' => ['nullable', 'exists:tax_classes,id'],
			'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
		]);

		// Convert empty strings to null for nullable fields
		$data['brand_id'] = $data['brand_id'] ?? null;
		$data['brand_id'] = $data['brand_id'] === '' ? null : $data['brand_id'];
		$data['tax_class_id'] = $data['tax_class_id'] ?? null;
		$data['tax_class_id'] = $data['tax_class_id'] === '' ? null : $data['tax_class_id'];
		$data['tax_rate'] = $data['tax_rate'] ?? null;
		$data['tax_rate'] = $data['tax_rate'] === '' ? null : $data['tax_rate'];
		$data['selling_price'] = $data['selling_price'] ?? null;
		$data['selling_price'] = $data['selling_price'] === '' ? null : $data['selling_price'];

		if ($request->hasFile('image')) {
			$path = $request->file('image')->store('products', 'public');
			$data['image'] = Storage::url($path);
		}

		// Handle slug: use manual entry if provided, otherwise auto-generate (model will handle this)
		if (empty($data['slug'])) {
			unset($data['slug']); // Let model auto-generate
		}
		
		$data['is_active'] = $request->boolean('is_active');
		$data['is_featured'] = $request->boolean('is_featured');
		$data['enable_countdown_timer'] = $request->boolean('enable_countdown_timer');
		
		// Handle countdown timer end date
		if ($data['enable_countdown_timer'] && $request->has('countdown_timer_end') && $request->countdown_timer_end) {
			$data['countdown_timer_end'] = $request->countdown_timer_end;
		} else {
			$data['countdown_timer_end'] = null;
		}

		$product = Product::create($data);
		$product->tags()->sync($request->input('tags', []));
		$product->categories()->sync($request->input('categories', []));
		
		// Set category_id to first category for backward compatibility
		if ($request->has('categories') && count($request->input('categories', [])) > 0) {
			$product->update(['category_id' => $request->input('categories')[0]]);
		}

		// Sync attributes
		if ($request->has('attributes')) {
			$attributesData = [];
			foreach ($request->input('attributes', []) as $attr) {
				if (!empty($attr['attribute_id'])) {
					$attributeValueId = $attr['attribute_value_id'] ?? null;
					$attributeValueId = ($attributeValueId === '') ? null : $attributeValueId;
					$customValue = $attr['custom_value'] ?? null;
					$customValue = ($customValue === '') ? null : $customValue;
					
					// Only include attribute if we have at least one value
					if ($attributeValueId !== null || $customValue !== null) {
						$attributesData[$attr['attribute_id']] = [
							'attribute_value_id' => $attributeValueId,
							'custom_value' => $customValue,
						];
					}
				}
			}
			$product->attributes()->sync($attributesData);
		}

		if ($request->hasFile('gallery')) {
			foreach ($request->file('gallery') as $index => $galleryImage) {
				$path = $galleryImage->store('products/gallery', 'public');
				$product->images()->create([
					'path' => Storage::url($path),
					'position' => $index,
				]);
			}
		}

		return redirect()->route('admin.products.index')->with('success', 'Product created');
	}

	public function edit(Product $product)
	{
		// Load all relationships to ensure they're available in the view
		$product->load([
			'images' => function($query) {
				$query->orderBy('position');
			},
			'tags',
			'attributes' => function($query) {
				$query->with('values');
			},
			'categories',
			'brand'
		]);
		
		$categories = $this->categoryOptions();
		$tags = Tag::orderBy('name')->get();
		$brands = Brand::active()->orderBy('name')->get();
		$attributes = Attribute::active()->with('values')->orderBy('name')->get();
		$taxClasses = Schema::hasTable('tax_classes') ? TaxClass::active()->orderBy('name')->get() : collect([]);
		
		return view('admin/products/form', compact('product', 'categories', 'tags', 'brands', 'attributes', 'taxClasses'));
	}

	public function update(Request $request, Product $product)
	{
		try {
		$data = $request->validate([
			'name' => ['required', 'string', 'max:255'],
			'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/', 'unique:products,slug,' . $product->id],
			'description' => ['nullable', 'string'],
			'short_description' => ['nullable', 'string'],
			'long_description' => ['nullable', 'string'],
			'specification' => ['nullable', 'string'],
			'price' => ['required', 'numeric', 'min:0'],
			'selling_price' => ['nullable', 'numeric', 'min:0'],
			'category_id' => ['nullable', 'exists:categories,id'],
			'categories' => ['nullable', 'array'],
			'categories.*' => ['exists:categories,id'],
			'brand_id' => ['nullable', 'exists:brands,id'],
			'stock_quantity' => ['required', 'integer', 'min:0'],
			'is_active' => ['sometimes', 'boolean'],
			'is_featured' => ['sometimes', 'boolean'],
			'enable_countdown_timer' => ['sometimes', 'boolean'],
			'countdown_timer_end' => ['nullable', 'required_if:enable_countdown_timer,1', 'date', 'after:now'],
			'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:width=1080,height=1080'],
			'tags' => ['nullable', 'array'],
			'tags.*' => ['exists:tags,id'],
			'attributes' => ['nullable', 'array'],
			'attributes.*.attribute_id' => ['required', 'exists:attributes,id'],
			'attributes.*.attribute_value_id' => ['nullable', 'exists:attribute_values,id'],
			'attributes.*.custom_value' => ['nullable', 'string', 'max:255'],
			'gallery' => ['nullable', 'array'],
			'gallery.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:width=1080,height=1080'],
			'tax_class_id' => ['nullable', 'exists:tax_classes,id'],
			'tax_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
		]);

			// Convert empty strings to null for nullable fields
			$data['brand_id'] = ($data['brand_id'] ?? '') === '' ? null : $data['brand_id'];
			$data['tax_class_id'] = ($data['tax_class_id'] ?? '') === '' ? null : $data['tax_class_id'];
			$data['tax_rate'] = ($data['tax_rate'] ?? '') === '' ? null : $data['tax_rate'];
			$data['selling_price'] = ($data['selling_price'] ?? '') === '' ? null : $data['selling_price'];

			if ($request->hasFile('image')) {
				$path = $request->file('image')->store('products', 'public');
				$data['image'] = Storage::url($path);
			}

		$data['is_active'] = $request->boolean('is_active');
		$data['is_featured'] = $request->boolean('is_featured');
		$data['enable_countdown_timer'] = $request->boolean('enable_countdown_timer');
		
		// Handle countdown timer end date
		if ($data['enable_countdown_timer'] && $request->has('countdown_timer_end') && $request->countdown_timer_end) {
			$data['countdown_timer_end'] = $request->countdown_timer_end;
		} else {
			$data['countdown_timer_end'] = null;
		}

		// Handle slug: use manual entry if provided, otherwise let model auto-generate on name change
			if (empty($data['slug'])) {
				unset($data['slug']); // Let model auto-generate if name changed
			}

			// Remove category_id from data since we handle it separately via categories array
			unset($data['category_id']);

			// Update product basic data
			$product->update($data);

			// Sync tags - ensure empty array if no tags provided
			$tags = $request->input('tags', []);
			$product->tags()->sync(is_array($tags) ? $tags : []);

			// Sync categories - ensure empty array if no categories provided
			$categories = $request->input('categories', []);
			$product->categories()->sync(is_array($categories) ? $categories : []);
			
			// Set category_id to first category for backward compatibility
			if (is_array($categories) && count($categories) > 0) {
				$product->update(['category_id' => $categories[0]]);
			} else {
				$product->update(['category_id' => null]);
			}

			// Sync attributes
			$attributesData = [];
			if ($request->has('attributes') && is_array($request->input('attributes'))) {
				foreach ($request->input('attributes', []) as $key => $attr) {
					// Handle both numeric keys and attribute ID keys
					$attributeId = $attr['attribute_id'] ?? $key;
					
					if (!empty($attributeId)) {
						$attributeValueId = $attr['attribute_value_id'] ?? null;
						$attributeValueId = ($attributeValueId === '' || $attributeValueId === null) ? null : $attributeValueId;
						$customValue = $attr['custom_value'] ?? null;
						$customValue = ($customValue === '' || $customValue === null) ? null : trim($customValue);
						
						// Only include attribute if we have at least one value
						if ($attributeValueId !== null || ($customValue !== null && $customValue !== '')) {
							$attributesData[$attributeId] = [
								'attribute_value_id' => $attributeValueId,
								'custom_value' => $customValue,
							];
						}
					}
				}
			}
			$product->attributes()->sync($attributesData);

			// Handle gallery images
			if ($request->hasFile('gallery')) {
				$maxPosition = $product->images()->max('position') ?? 0;
				$position = (int) $maxPosition + 1;
				foreach ($request->file('gallery') as $galleryImage) {
					$path = $galleryImage->store('products/gallery', 'public');
					$product->images()->create([
						'path' => Storage::url($path),
						'position' => $position++,
					]);
				}
			}

			return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
		} catch (\Illuminate\Validation\ValidationException $e) {
			return redirect()->back()
				->withErrors($e->errors())
				->withInput();
		} catch (\Exception $e) {
			return redirect()->back()
				->with('error', 'Error updating product: ' . $e->getMessage())
				->withInput();
		}
	}

	public function destroyImage(Product $product, \App\Models\ProductImage $image): RedirectResponse
	{
		if ($image->product_id !== $product->id) {
			abort(404);
		}

		if ($image->path) {
			$relative = ltrim(str_replace('/storage/', '', $image->path), '/');
			Storage::disk('public')->delete($relative);
		}

		$image->delete();

		return back()->with('success', 'Image removed successfully.');
	}

	public function destroy(Product $product)
	{
		$product->delete();
		return redirect()->route('admin.products.index')->with('success', 'Product deleted');
	}

	public function export(): StreamedResponse
	{
		$filename = 'products-' . now()->format('Ymd-His') . '.csv';
		$headers = [
			'Content-Type' => 'text/csv',
			'Content-Disposition' => "attachment; filename=\"{$filename}\"",
		];

		$columns = ['name', 'description', 'price', 'category', 'stock_quantity', 'is_active'];

		$callback = function () use ($columns) {
			$handle = fopen('php://output', 'w');
			fputcsv($handle, $columns);

			Product::with('category')
				->orderBy('name')
				->chunk(200, function ($products) use ($handle) {
					foreach ($products as $product) {
						fputcsv($handle, [
							$product->name,
							$product->description,
							$product->price,
							optional($product->category)->name,
							$product->stock_quantity,
							$product->is_active ? 1 : 0,
						]);
					}
				});

			fclose($handle);
		};

		return response()->stream($callback, 200, $headers);
	}

	public function import(Request $request): RedirectResponse
	{
		$data = $request->validate([
			'file' => ['required', 'file', 'mimes:csv,txt'],
		]);

		$path = $data['file']->getRealPath();
		$handle = fopen($path, 'r');

		if ($handle === false) {
			return back()->with('error', 'Unable to read the uploaded file.');
		}

		$header = fgetcsv($handle);
		$expected = ['name', 'description', 'price', 'category', 'stock_quantity', 'is_active'];
		if (!$header || array_map('strtolower', $header) !== $expected) {
			fclose($handle);
			return back()->with('error', 'Invalid CSV format. Expected headers: ' . implode(', ', $expected));
		}

		$created = 0;
		$updated = 0;

		while (($row = fgetcsv($handle)) !== false) {
			[$name, $description, $price, $categoryName, $stock, $isActive] = $row;
			if (blank($name)) {
				continue;
			}

			$category = null;
			if ($categoryName) {
				$category = Category::firstOrCreate(['name' => $categoryName], [
					'slug' => Str::slug($categoryName) . '-' . Str::random(4),
				]);
			}

			$product = Product::query()->firstOrNew(['name' => $name]);
			$product->fill([
				'description' => $description,
				'price' => (float) $price,
				'stock_quantity' => (int) $stock,
				'is_active' => (int) $isActive === 1,
			]);

			if ($category) {
				$product->category_id = $category->id;
			}

			if (!$product->exists || !$product->slug) {
				$product->slug = Str::slug($name) . '-' . Str::random(4);
			}

			$product->save();

			$product->wasRecentlyCreated ? $created++ : $updated++;
		}

		fclose($handle);

		return back()->with('success', "Import completed. Created: {$created}, Updated: {$updated}.");
	}
}
