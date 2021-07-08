<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserProductsController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(User $user)
    {
        $products = $user->products;

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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user, Category $category, Products $products)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'approx_price' => 'integer|min:50',
            'current_price' => 'integer|min:50',
            'status' => 'in:' . Products::AVAILABLE_PRODUCT . ',' . Products::UNAVAILABLE_PRODUCT,
        ];

        $this->validate($request, $rules);

// $data = $request->all();

// $data['user_id'] = auth('api')->user()->id;
        $products->user_id = auth('api')->user()->id;
        $products->category_id = $request->category_id;

// check who is updating
        $this->checkSeller($user, $products);

        $products->fill($request->only([
            'name',
            'brand',
            'approx_price',
            'current_price',
            'quantity',
        ]));

        if ($request->has('status')) {
            $products->status = $request->status;

            if ($products->isAvailable() && $products->category()->count() == 0) {
                return $this->errorResponse("Status update failed, the product must be in a category", 409);
            }
        }

        if ($products->isClean()) {
            return $this->errorResponse('Select a product feature to update', 422);
        }

        $products->save();

        return $this->returnOne($products, 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
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
