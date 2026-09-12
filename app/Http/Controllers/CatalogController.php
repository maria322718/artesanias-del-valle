<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::query();

        if ($request->filled('region')) {
            $query->where('origin_region', 'like', '%' . $request->input('region') . '%');
        }

        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('description', 'like', "%{$term}%")
                  ->orWhere('artisan_name', 'like', "%{$term}%");
            });
        }

        $products = $query->orderBy('price', 'desc')->get();
        $regions = Product::select('origin_region')->distinct()->pluck('origin_region');

        return view('catalog.index', compact('products', 'regions'));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('catalog.show', compact('product'));
    }
}
