<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Supplier;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        //count categories
        $categories = Category::count();

        // count suppliers data
        $suppliers = Supplier::count();

        //count products
        $products = Product::count();

        $productsOutStock = Product::with(['stock' => function ($query) {
            $query->selectRaw(
                'product_id,
                    SUM(
                        CASE
                            WHEN
                                type = "in" THEN quantity
                            WHEN
                                type = "out" THEN -quantity
                            ELSE
                                0 END
                    ) as total_quantity'
            )
                ->groupBy('product_id');
        }])
            ->whereHas('stock', function ($query) {
                $query->selectRaw('product_id')
                    ->groupBy('product_id')
                    ->havingRaw('SUM(CASE WHEN type = "in" THEN quantity WHEN type = "out" THEN -quantity ELSE 0 END) <= 10');
            })->paginate(10);


        //return response json
        return response()->json([
            'success'   => true,
            'message'   => 'List Data on Dashboard',
            'data'      => [
                'categories' => $categories,
                'products'   => $products,
                'suppliers'  => $suppliers,
                'productsOutStock' => $productsOutStock
            ]
        ]);
    }
}
