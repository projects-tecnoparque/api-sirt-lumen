<?php

namespace App\Exceptions\JsonApi;

use Exception;
use Illuminate\Http\Response;

class NotFoundHttpException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        return response()->json([
            'errors' => [
                [
                'title' => "Not Found",
                'detail' => "The requested resource was not found",
                'status' =>  (string) Response::HTTP_NOT_FOUND
                ]
            ]
        ], Response::HTTP_NOT_FOUND);
    }
}
