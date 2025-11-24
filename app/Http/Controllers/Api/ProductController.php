<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $paginate = $request->get('paginate', 20);
        $search = $request->get('search');
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');

        $query = Product::query();

        // Filter - search query
        if (!empty($search)) {
            $query->where(function(Builder $q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter - min price
        if (is_numeric($minPrice) && $minPrice > 0) {
            $query->where('price', '>=', $minPrice);
        }

        // filter - max price
        if (is_numeric($maxPrice) && $maxPrice > 0) {
            $query->where('price', '<=', $maxPrice);
        }

        // Retrieve and paginate data
        $data = $query->simplePaginate($paginate, ['*'], 'page', $page);

        return response()->json($data);
    }
}
