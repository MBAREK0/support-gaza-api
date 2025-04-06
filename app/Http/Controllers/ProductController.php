<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'alternative_brands' => 'nullable|array',
            'description' => 'nullable|string',
            // For file uploads, use the file validation rules
            'logo' => 'nullable|file|image|max:2048', // Max 2MB
            'product_images.*' => 'nullable|file|image|max:2048', // Each product image max 2MB
        ]);

        // Handle logo upload
        $logoPath = null;
        if ($request->hasFile('logo') && $request->file('logo')->isValid()) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // Handle product images upload
        $productImagePaths = [];
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $image) {
                if ($image->isValid()) {
                    $productImagePaths[] = $image->store('product_images', 'public');
                }
            }
        }

        // Create the product
        $product = Product::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'alternative_brands' => $request->alternative_brands ?? null,
            'description' => $request->description ?? null,
            'logo' => $logoPath,
            'product_images' => $productImagePaths
        ]);

        return response()->json($product, 201);
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        return response()->json($product);
    }



    /**
     * Remove the specified product.
     */
    public function destroy(Product $product)
    {



        $product->delete();
        return response()->json(null, 204);
    }
}
