<?php

namespace App\Http\Controllers\V2\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return response()->json([
            'data' => [
                'app' => config('app.name'),
                'version' => 'v2',
                'user' => [
                    'name' => 'Julian'
                ]
            ]
        ]);
    }
}
