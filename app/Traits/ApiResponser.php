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

        // check if collection is empty
        if($collection->isEmpty()){
            return $this->successResponse($collection, $code);
        }

        // obtain transformer from collection
        $transformer = $collection->first()->transformer;
        $collection = $this->transformData($collection, $transformer);
        return $this->successResponse($collection, $code);
    }

    protected function returnOne(Model $model, $code){
        $transformer = $model->transformer;
        $model = $this->transformData($model, $transformer);
        return $this->successResponse($model, $code);
    }

     protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    protected function transformData($data, $transformer){
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }
}
