<?php

namespace App\Transformers;

use App\Models\Products;
use League\Fractal\TransformerAbstract;

class ProductsTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Products $products)
    {
        return [
            'productId'=>(int)$products->id,
            'userId'=>(int)$products->user_id,
            'productName'=> (string)$products->name,
            'brandName'=> (string)$products->brand,
            'productQuantity'=>(int)$products->quantity,
            'previousPrice'=> (string)$products->approx_price,
            'newPrice'=> (string)$products->current_price,
            'priceDifference'=>(double)$products->current_price - (double)$products->approx_price,
            'productStatus'=> (string)$products->status,
            'createdDate'=> (string)$products->created_at,
            'updatedDate'=> (string)$products->updated_at,

        ];

        //  "id": 1,
        // "user_id": 1,
        // "name": "iusto",
        // "brand": "quis",
        // "quantity": 16,
        // "approx_price": "1261",
        // "current_price": "849",
        // "status": "available",
        // "created_at": "2021-07-08T18:22:25.000000Z",
        // "updated_at": "2021-07-08T18:22:25.000000Z",
        // "deleted_at": null
    }


      public static function originalData($index){
        $attributes=[
            'productId'=>'id',
            'userId'=>'user_id',
            'productName'=> 'name',
            'brandName'=> 'brand',
            'productQuantity'=>'quantity',
            'previousPrice'=> 'approx_price',
            'newPrice'=> 'current_price',
            'productStatus'=> 'status',
            'createdDate'=> 'created_at',
            'updatedDate'=> 'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
