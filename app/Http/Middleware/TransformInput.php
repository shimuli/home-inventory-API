<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $transformer)
    {
        $transformedInput =[];
        foreach($request->request->all() as $input => $value){
            $transformedInput[$transformer::originalData($input)] = $value;
        }
        $request->replace($transformedInput);
        $response =  $next($request);

        // check if there is an error
        if(isset($response->exception) & $response->exception instanceof ValidationException){
            $data = $response->getData();

            $transformedErrors =[];
            foreach($data->error as $field=>$error){
                $transformedField = $transformer::transformData($field);

                $transformedErrors[$transformedField] = str_replace($field, $transformedField, $error);
            }

            $data->error = $transformedErrors;
            $response->setData($data);

        }
        return $response;
    }
}
