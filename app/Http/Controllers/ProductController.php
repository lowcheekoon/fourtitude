<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
//use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Validator;
use App\Models\Product;

class ProductController extends Controller
{
    //
    function index() {
        return Inertia::render('Product/Index', [
        //    'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
        //    'status' => session('status'),
        ]);
    }

    function getList(Request $request) {
        $data = Product::select('id', 'code', 'name', 'category', 'brand', 'type', 'description')->paginate( $request->input('size') )->withQueryString();;
        return response()->json(['status' => 'ok', 'data' => $data, 'page' => $request->input('page'), 'total' => $data->count() ]);
    }

    function store(Request $request)  {

        /*
        $validated = $request->validate([
            'name' => 'required|unique:posts|max:255',
            'body' => 'required',
        ]);
        */
        $validator = Validator::make($request->all(), [
            'code' => 'required|unique:products|max:255',
            'name' => 'required|max:255',
            'category' => 'required|max:255',
            'brand' => 'required|max:255',
            'type' => 'required|max:255',
            'description' => 'required|max:255',
        ])->validate();

        Product::create([
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'category' => $request->input('category'),
            'brand' => $request->input('brand'),
            'type' => $request->input('type'),
            'description' => $request->input('description'),
        ]);

        return response()->json(['status' => 'ok', 'data' => []]);
    }

    function edit( $code ) {
        $data = Product::select('code', 'name', 'category', 'brand', 'type', 'description')->where('code', $code)->first();
        return response()->json(['status' => 'ok', 'data' => $data ]);
    }

    function update(Request $request, $code) {

        $validator = Validator::make($request->all(), [
                        'name' => 'required|max:255',
                        'category' => 'required|max:255',
                        'brand' => 'required|max:255',
                        'type' => 'required|max:255',
                        'description' => 'required|max:255',
                    ])->validate();
        
        $product = Product::where('code', $code)->first();
        $product->name = $request->input('name');
        $product->category = $request->input('category');
        $product->brand = $request->input('brand');
        $product->type = $request->input('type');
        $product->description = $request->input('description');
        $product->save();

        return response()->json(['status' => 'ok', 'data' => []]);
    }

    function delete( $code ) {
        Product::where('code', $code)->delete();
        return response()->json(['status' => 'ok', 'data' => []]);
    }

}
