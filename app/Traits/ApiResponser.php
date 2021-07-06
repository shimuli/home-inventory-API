<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;


trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

     protected function errorResponse($message, $code)
    {
        return response()->json(['error'=>$message, 'code'=>$code], $code);
    }

    protected function returnAll(Collection $collection, $code =200){
        return $this->successResponse($collection, $code);
    }

    protected function returnOne(Model $model, $code){
        return $this->successResponse($model, $code);
    }
}
