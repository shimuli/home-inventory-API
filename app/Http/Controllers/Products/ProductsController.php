<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\ApiController;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Products::all();

        return $this->returnAll($products);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $user)
    {
        $rules = [
            'category_id' => 'required|integer|min:1',
            'name' => 'required',
            'brand' => 'required',
            'quantity' => 'required|integer|min:1',
            'approx_price' => 'required|integer|min:50',
            'current_price' => 'required|integer|min:50',
        ];

        // dd($data['user_id'] = auth('api')->user()->id);

        $this->validate($request, $rules);

        $data = $request->all();
        $data['status'] = Products::UNAVAILABLE_PRODUCT;
        //$data['image'] = $request->image->store(''); // add path and name if needed
        $data['user_id'] = auth('api')->user()->id;

        $product = Products::create($data);

        return $this->returnOne($product, 201);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function show(Products $products)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function edit(Products $products)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Products $products, User $user)
    {
       

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Products  $products
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $products)
    {
        //
    }

    protected function checkSeller(User $user, Products $product)
    {
        // $data['user_id'] = auth('api')->user()->id;

        if (auth('api')->user()->id != $product->user_id) {
            dd(auth('api')->user()->id);
            throw new HttpException(422, "You are not authorize to perform this action on this specific product");
        }
    }
}
