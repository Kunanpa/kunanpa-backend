<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignupRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Crear nuevo usuario
     *
     * @param SignupRequest $signupRequest
     */
    public function signup(SignupRequest $signupRequest){

        return response()->json([
            'data' => $signupRequest->all()
        ]);
    }

    /**
     *  Iniciar sesion
     *
     */
    public function login(Request $request){

    }

    /**
     *  Cerrar sesion
     *
     */
    public function logout(Request $request){

    }
}
