<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'product_name' => 'required|max:100',
            'product_type' => 'required|in:snake,drink,makeup,drugs',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date' 
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages())->setStatusCode(404);
        }

        $validated = $validator->validated();
        Product::create([
            'product_name' => $validated['product_name'],
            'product_type' => $validated['product_type'],
            'product_price' => $validated['product_price'],
            'expired_at' => $validated['expired_at']
        ]);

        return response()->json('data product berhasil di simpan')->setStatusCode(201);
    }

    function showAll(){
        $products = Product::all();

        return response()->json([
            'msg' => 'Data Produk keseluruhan',
            'data' => $products
        ],200);
    }

    function showById($id){

        $product = Product::where('id',$id)->first();

        if($product) {

            return response()->json([
                'msg' => 'Data produk dengan ID: '.$id,
                'data' => $product
            ],200);

        }

        return response()->json([
            'msg' => 'Data produk dengan ID: '.$id.'tidak ditemukan',
        ],404);
    }
    

    function showByName($product_name){

        $product = Product::where('product_name','LIKE','%'.$product_name.'%')->get();

        if($product->count() > 0) {

            return response()->json([
                'msg' => 'Data produk dengan nama yang mirip: '.$product_name,
                'data' => $product
            ],200);

        }

        return response()->json([
            'msg' => 'Data produk dengan nama yang mirip: '.$product_name.' tidak ditemukan',
        ],404);
    }

    public function update(Request $request,$id) {

        $validator = Validator::make($request->all(), [
            'product_name' => 'required|max:100',
            'product_type' => 'required|in:snake,drink,makeup,drugs',
            'product_price' => 'required|numeric',
            'expired_at' => 'required|date' 
        ]);

        if($validator->fails()) {

            return response()->json($validator->messages())->setStatursCode(422);
        }

        $validated = $validator->validated();
        Product::where('id',$id)->update([
            'product_name' => $validated['product_name'],
            'product_type' => $validated['product_type'],
            'product_price' => $validated['product_price'],
            'expired_at' => $validated['expired_at']
        ]);

        return response()->json([
            'msg' => 'Data produk berhasil diubah'
        ],201);
    }

    public function delete($id) {

        $product = Product::where('id',$id)->get();

        if($product){

            Product::where('id',$id)->delete();

            return response()->json([
                'msg' => 'Data produk dengan ID: '.$id.' berhasil dihapus'
            ],200);
        }

        return response()->json([
            'msg' => 'Data produk dengan ID: '.$id.'tidak ditemukan'        
        ],404);
    }
}


