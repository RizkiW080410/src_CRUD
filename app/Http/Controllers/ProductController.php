<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request) {
        $keyword = $request->get('search');
        $perpage = 5;

        if (!empty($keyword)) {
            $listproduct = Product::where('name', 'LIKE', "%$keyword")
                        ->orWhere('category', 'LIKE', "%$keyword")
                        ->latest()->paginate($perpage);
        } else {
            $listproduct = Product::latest()->paginate($perpage);
        }
        return view('product.index', ['listproduct' => $listproduct])->with('i', (request()->input('page',1) -1) *5);
    }

    public function create() {
        return view('product.create');
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|max:255',
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required|numeric',
            'size' => 'required|max:6',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
    
        $product = new Product;
    
        if ($request->hasFile('image')) {
            $file_name = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $file_name);
            $product->image = $file_name;
        }
    
        $product->name = $request->name;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->quantity = $request->quantity;
        $product->size = $request->size;
    
        $product->save();
        return redirect()->route('product.index')->with('success', 'Product Added Successfully');
    }

    public function edit($id){
        $product = Product::findOrFail($id);
        return view('product.edit', ['product'=>$product]);
    }

    public function update(Request $request, Product $product){
        $request->validate([
            'name' => 'required'
        ]);

        $file_name = $request->hidden_product_image;

        if($request->image != ''){
            $file_name = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $file_name);
            $product->image = $file_name;
        }

        $product = Product::find($request->hidden_id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->category = $request->category;
        $product->quantity = $request->quantity;
        $product->size = $request->size;
    
        $product->save();

        return redirect()->route('product.index')->with('success', 'Product has been update successfully');
    }

    public function destroy($id){
        $product = Product::findOrFail($id);
        $image_path = public_path()."/images/";
        $image = $image_path. $product->image;
        if(file_exists($image)){
            @unlink($image);
        }
        $product->delete();
        return redirect('product')->with('success', 'Product Deleted!');
    }
}
