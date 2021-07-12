<?php

namespace App\Models;

use App\Transformers\CategoryTransformer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $transformer = CategoryTransformer::class;

    public function products()
    {
        return $this->belongsToMany(Products::class);
    }

    protected $fillable=['name'];

    protected $hidden = ['deleted_at'];
}
