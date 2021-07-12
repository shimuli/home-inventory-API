<?php

namespace App\Models;

use App\Transformers\ProductsTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Products extends Model
{
    const AVAILABLE_PRODUCT = 'available';
    const UNAVAILABLE_PRODUCT = 'unavailable';
    public $transformer = ProductsTransformer::class;

    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'brand',
        'quantity',
        'approx_price',
        'current_price',
        'status',
    ];

    protected $hidden =[
        'deleted_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsToMany(Category::class);
    }

    // if product is available or not
    public function isAvailable()
    {
        return $this->status == Products::AVAILABLE_PRODUCT;
    }
}
