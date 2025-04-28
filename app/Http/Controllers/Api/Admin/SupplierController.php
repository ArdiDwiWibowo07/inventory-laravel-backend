<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SupplierResource;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get suppliers
        $suppliers = Supplier::when(request()->search, function ($suppliers) {
            $suppliers = $suppliers->where('name', 'like', '%' . request()->search . '%');
        })->latest()->paginate(5);

        //append query string to pagination links
        $suppliers->appends(['search' => request()->search]);

        //return with Api Resource
        return new SupplierResource(true, 'List Data Suppliers', $suppliers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|unique:suppliers',
            'telp'    => 'required',
            'address'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //create supplier
        
        $supplier = supplier::create([
            'name' => $request->name,
            'telp' => $request->telp,
            'address' => $request->address,

        ]);

        if ($supplier) {
            //return success with Api Resource
            return new supplierResource(true, 'Data Supplier Berhasil Disimpan!', $supplier);
        }

        //return failed with Api Resource
        return new supplierResource(false, 'Data Supplier Gagal Disimpan!', null);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $supplier = Supplier::whereId($id)->first();

        if ($supplier) {
            //return success with Api Resource
            return new SupplierResource(true, 'Detail Data Supplier!', $supplier);
        }

        //return failed with Api Resource
        return new SupplierResource(false, 'Detail Data Supplier Tidak DItemukan!', null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|unique:suppliers,name,' . $supplier->id,
            'telp' => 'required',
            'address' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        //update supplier
        $supplier->update([
            'name' => $request->name,
            'telp' => $request->telp,
            'address' => $request->address,
        ]);

        if ($supplier) {
            //return success with Api Resource
            return new SupplierResource(true, 'Data Supplier Berhasil Diupdate!', $supplier);
        }

        //return failed with Api Resource
        return new SupplierResource(false, 'Data supplier Gagal Diupdate!', null);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supplier $supplier)
    {
        if ($supplier->delete()) {
            //return success with Api Resource
            return new SupplierResource(true, 'Data Supplier Berhasil Dihapus!', null);
        }

        //return failed with Api Resource
        return new SupplierResource(false, 'Data Supplier Gagal Dihapus!', null);
    }
}
