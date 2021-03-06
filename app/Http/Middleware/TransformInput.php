<?php

namespace App\Http\Middleware;

use Closure;
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
    public function handle($request, Closure $next, $transformer)
    {
        $transformInput = [];
        foreach ($request->request->all() as $input => $value) {
            $transformInput[$transformer::originalAttribute($input)] = $vlaue;
        }
        $request->replace($transformInput);
        $response = $next($request);

        if (isset($response->exception) && $response->exception instanceof ValidationException) {
            $data = $response->getData();
            $transformedErrors = [];
            foreach ($data->error as $field => $value) {
                $fieldTransed = $transformer::transformedAttributes($field);
                $transformedErrors[$fieldTransed] = str_replace($field, $fieldTransed, $error);
            }

            $data->error = $transformedErrors;
            $response->setData($data);
        }
        return $response;
    }
}
