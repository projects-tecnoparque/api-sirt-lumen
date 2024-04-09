<?php

namespace App\Exceptions\JsonApi;

use Exception;
use Illuminate\Http\Response;

class AuthenticateException extends Exception
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
                'title' => "Unauthorized",
                'detail' => $this->getMessage(),
                'status' =>  (string) Response::HTTP_UNAUTHORIZED
                ]
            ]
        ], Response::HTTP_UNAUTHORIZED);
    }
}
