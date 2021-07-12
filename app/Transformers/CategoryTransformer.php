<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;

class CategoryTransformer extends TransformerAbstract
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
    public function transform(Category $category)
    {
        return [
            'categoryId'=>(int)$category->id,
            'categoryName'=>(string)$category->name,
            'createdDate'=>(string)$category->updated_at,
            'updatedDate'=>(string)$category->updated_at,
        ];

    }

      public static function originalData($index){
        $attributes=[
             'categoryId'=>'id',
            'categoryName'=>'name',
            'createdDate'=>'updated_at',
            'updatedDate'=>'updated_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;

    }
}
