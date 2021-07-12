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
    public function update(Request $request, User $user, Products $product)
    {
        $rules = [
            'quantity' => 'integer',
            'approx_price' => 'integer|min:50',
            'current_price' => 'integer|min:50',
            'status' => 'in:' . Products::AVAILABLE_PRODUCT . ',' . Products::UNAVAILABLE_PRODUCT,
        ];

        $this->validate($request, $rules);

        $product->user_id = auth('api')->user()->id;

        // check who is updating
        $this->checkUser($user, $product);

        $product->fill($request->only([
            'name',
            'brand',
            'approx_price',
            'current_price',

        ]));


        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->isAvailable() && $product->category()->count() == 0) {
                return $this->errorResponse("Status update failed, the product must be in a category", 409);
            }
        }

        if ($request->has('quantity')) {
            if($request->quantity < 0 && $product->quantity <=0 ){
                return $this->errorResponse('This product quantity is 0 already', 409);
            }
            $product->quantity += $request->quantity;
            // $product->save();

        }

        if ($product->isClean()) {
            return $this->errorResponse('Select a product feature to update', 422);
        }

        $product->save();

        return $this->returnOne($product, 201);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user, Products $product)
    {
        $product->user_id = auth('api')->user()->id;
        $this->checkUser($user, $product);

        $product->delete();

        //  // delete image
        // Storage::delete($product->image);

        return response()->json([
            "message" => 'Deleted Successfully', 'code' => '204',
        ], 200);

    }

    protected function checkUser(User $user, Products $product)
    {
        // $data['user_id'] = auth('api')->user()->id;
        if ($user->id != $product->user_id) {
            (auth('api')->user()->id);
            throw new HttpException(422, "You are not authorize to perform this action on this specific product");
        }
    }
}
