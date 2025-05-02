<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportResource;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Http\Request;
use PDF;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // validate request
        $request->validate([
            'product' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);

        // request from_date
        $from_date = $request->from_date;

        // request to_date
        $to_date = $request->to_date;

        // request product
        $product = $request->product;

        // get first date stock
        $min_date_stock = Stock::select('created_at')->orderBy('created_at', 'asc')->first();

        // get all products data
        $products = Product::select('id', 'name')->orderBy('name')->get();

        // get products with stocks
        $reports = $this->getProductWithStocks($from_date, $to_date, $product);

        // get first stock product
        $first_stock = $this->getFirstStock($from_date);

        $data = [];

        $data['products'] = $products;
        $data['reports'] = $reports;
        $data['first_stock'] = $first_stock;

        return new ReportResource(true, 'List Data Report', $data);
    }
    /**
     * getFirstStock
     */
    private function getFirstStock($from_date)
    {
        $get_first_stocks = Product::with(['stock' => function ($query) use ($from_date) {
            $query->selectRaw('product_id, created_at, SUM(CASE WHEN type = "in" THEN quantity ELSE quantity*-1 END) as stock')
                ->whereDate('created_at', '<', $from_date)
                ->groupBy('product_id', 'created_at');
        }])->get();

        // first stock
        $first_stock = [];

        // loop first stock product data
        foreach ($get_first_stocks as $item) {
            // check if stock is not null
            if ($item->stock != null)
                // assign first stock with stock
                $first_stock[$item->id] = [
                    'stok' => $item->stock->stock,
                    'date' => $item->stock->created_at->format('Y-m-d'),
                ];
        }

        return $first_stock;
    }

    /**
     * getProductWithStocks
     */
    private function getProductWithStocks($from_date, $to_date, $product)
    {
        $query = Product::with(['stocks' => function ($query) use ($from_date, $to_date) {
            $query->whereDate('created_at', '>=', $from_date)
                ->whereDate('created_at', '<=', $to_date)
                ->orderBy('created_at');
        }, 'stock' => function ($query) {
            $query->selectRaw('product_id, created_at, SUM(CASE WHEN type = "in" THEN quantity ELSE quantity*-1 END) as stock')
                ->groupBy('product_id', 'created_at');
        }]);

        // check when request product is not all
        if ($product !== 'all')
            // get product data by id
            $query->where('id', $product);

        // get product data
        $reports = $query->get();

        return $reports;
    }

    public function download($type, $from_date, $to_date)
    {
        // get products with stocks
        $reports = $this->getProductWithStocks($from_date, $to_date, $type);

        // get first stock product
        $first_stock = $this->getFirstStock($from_date);

        // load pdf view
        $pdf = PDF::loadView('reports.download', compact('from_date', 'to_date', 'reports', 'first_stock'))->setPaper('a4', 'landscape');

        // download pdf
        return $pdf->download('Laporan - ' . Carbon::parse($from_date)->format('d M Y') . ' - ' . Carbon::parse($to_date)->format('d M Y') . '.pdf');
    }
}
