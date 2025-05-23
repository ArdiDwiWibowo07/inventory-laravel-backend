<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\StockResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //get all product 
        $products = Product::with(['category', 'stocks', 'stock' => function ($query) {
            $query->selectRaw('product_id, SUM(CASE WHEN type = "in" THEN quantity ELSE quantity*-1 END) as stock')
                ->groupBy('product_id');
        }])->when(request()->search, function ($categories) {
            $categories = $categories->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(10);

        //append query string to pagination links
        $products->appends(['search' => request()->search]);

        //return with Api Resource
        return new StockResource(true, 'List Data Suppliers', $products);
    }
    
    public function stockIn(Product $product, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity'     => 'required|integer|gt:0',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product->stock()->create([
            'type' => 'in',
            'quantity' => $request->quantity
        ]);

        if ($product) {
            return new StockResource(true, 'Data Stock Berhasil Diperbarui', $product);
        }

        return new StockResource(false, 'Data Stock Gagal Diupdate!', null);
    }

    public function stockOut(Product $product, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity'     => 'required|integer|gt:0',
        ]);


        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product->stock()->create([
            'type' => 'out',
            'quantity' => $request->quantity
        ]);

        if ($product) {
            return new StockResource(true, 'Data Stock Berhasil Diperbarui', $product);
        }

        return new StockResource(false, 'Data Stock Gagal Diupdate!', null);
    }
}
