<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Product;
use App\Category;
use App\Http\Resources\Product as ProductsResources;
use App\Http\Resources\Category as CategoryResources;
use DB;

class ProductsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $products = DB::table('products')
        ->join('categories', 'categories.id', '=', 'products.category_id')
        ->select('products.id', 'products.product_name', 'products.bar_code', 'products.srp', 'products.product_price', 'products.product_qty',  'categories.category_name')
        ->get();

        return new ProductsResources($products);
    }

    public function get_products_joined(Request $request)
    {
        $columns = ['product_name', 'bar_code', 'srp', 'product_price', 'product_price', 'product_qty', 'category_name',];

        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $searchValue = $request->input('search');

        $query = DB::table('products')
        ->join('categories', 'categories.id', '=', 'products.category_id')
        ->select('products.id', 'products.product_name', 'products.bar_code', 'products.srp', 'products.product_price', 'products.product_qty',  'categories.category_name')
        ->orderBy($columns[$column], $dir);

        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('product_name', 'like', '%' . $searchValue . '%')->
                orWhere('bar_code', 'like', '%' . $searchValue . '%');
            });
        }
        $products = $query->paginate($length);
        return ['data' => $products, 'draw' => $request->input('draw')];
    }

    public function get_products(Request $request)
    {
        $columns = ['product_name', 'product_price', 'product_qty'];

        $length = $request->input('length');
        $column = $request->input('column');
        $dir = $request->input('dir');
        $searchValue = $request->input('search');

        $query = Product::select('id', 'product_name', 'bar_code', 'product_price', 'product_qty' , 'category_id')->orderBy($columns[$column], $dir);

        if($searchValue){
            $query->where(function($query) use ($searchValue){
                $query->where('product_name', 'like', '%' . $searchValue . '%')->
                orWhere('bar_code', 'like', '%' . $searchValue . '%');
            });
        }
        $products = $query->paginate($length);
        return ['data' => $products, 'draw' => $request->input('draw')];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = $request->isMethod('put') ? Product::findOrFail($request->id) : new Product;
        
        $product->product_name = $request->input('product_name');
        $product->bar_code = $request->input('bar_code');
        $product->srp = $request->input('srp');
        $product->product_price = $request->input('product_price');
        $product->product_qty = $request->input('product_qty');
        $product->category_id = $request->input('category_id');
        
        if($product->save()){
            return new ProductsResources($product);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if($product->delete()){
            return new ProductsResources($product);
        }
    }

    public function categories()
    {
        $category = Category::all();
        return CategoryResources::collection($category);
    }

    public function store_category(Request $request)
    {
        $category = $request->isMethod('put') ? Category::findOrFail($request->id) : new Category;
        
        $category->category_name = $request->input('category_name');
        
        if($category->save()){
            return new CategoryResources($category);
        }
    }

    public function destroy_category($id)
    {
        $category = Category::findOrFail($id);

        if($category->delete()){
            return new CategoryResources($category);
        }
    }
}
