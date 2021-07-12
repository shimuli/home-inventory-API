<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;



trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function returnAll(Collection $collection, $code = 200)
    {

        // check if collection is empty
        if ($collection->isEmpty()) {
            return $this->successResponse($collection, $code);
        }

        // obtain transformer from collection
        $transformer = $collection->first()->transformer;

        // filter data
        $collection = $this->filterData($collection, $transformer);

        // sort data
        $collection = $this->sortData($collection, $transformer);

        // paginate
        $collection = $this->paginate($collection);

        $collection = $this->transformData($collection, $transformer);
        return $this->successResponse($collection, $code);
    }

    protected function returnOne(Model $model, $code)
    {
        $transformer = $model->transformer;
        $model = $this->transformData($model, $transformer);
        return $this->successResponse($model, $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }

    protected function sortData(Collection $collection, $transformer)
    {
        if (request()->has('sort_by')) {
            $sortAttribute = $transformer::originalData(request()->sort_by);
            $collection = $collection->sortBy->{$sortAttribute};
        }
        return $collection;
    }

    public function filterData(Collection $collection, $transformer)
    {
        foreach (request()->query() as $query => $value) {
            $attribute = $transformer::originalData($query);
            if (isset($attribute, $value)) {
                $collection = $collection->where($attribute, $value);
            }
        }

        return $collection;

    }

    protected function transformData($data, $transformer)
    {
        $transformation = fractal($data, new $transformer);

        return $transformation->toArray();
    }

    protected function paginate(Collection $collection)
    {
        $rules = [
            'per_page' => 'integer|min:3|max:50',
        ];
        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        if (request()->has('per_page')) {
            $perPage = (int) request()->per_page;
        }
        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        $paginated->appends(request()->all());
        return $paginated;
    }
}
