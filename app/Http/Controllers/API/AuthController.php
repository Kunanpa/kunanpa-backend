<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\SignupRequest;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Crear nuevo usuario
     *
     * @param SignupRequest $signupRequest
     */
    public function signup(SignupRequest $signupRequest){
        // Registro de un recurso en la tabla personas
        // $persona = Persona::create($signupRequest->except(['email','email']));
        $persona = Persona::create(array_filter($signupRequest->except(['email','email']), 'strlen'));
        // $persona = array_filter($signupRequest->except(['email','email']), 'strlen');

        // Registro en la tabla users
        $user =new User();
        $user->idPersona = $persona->id;
        $user->email = $signupRequest->email;
        $user->password =Crypt::encrypt($signupRequest->password);
        $user->save();

        return response()->json([
            'data' => $signupRequest,
            'persona' => $persona,
            'user' => $user
        ]);
    }

    /**
     *  Iniciar sesion
     *
     * @param LoginRequest $request
     */
    public function login(LoginRequest $request){
        $user = User::where('email', $request->email)->first();
        $validPassword = false;
        if ($user) {
            $validPassword = ($request->password == Crypt::decrypt($user->password)) ? true : false;
        }

        if (!$user || !$validPassword) {
            throw ValidationException::withMessages([
                'statusCode' => 204,
                'message' => ['Login invalido, email o password incorrecto']
            ]);
        }
        $persona = Persona::where('id', $user->idPersona)->first();
        $token = $user->createToken($request->email)->plainTextToken;
        // TODO: PENDIENTE CREAR JWT
        return response()->json([
            'user' => $user,
            'persona' => $persona,
            'token' => $token,
            'valid' => $validPassword
        ]);
    }

    /**
     *  Cerrar sesion
     *
     * @param Request $request
     */
    public function logout(Request $request){
        /*$request->user()->currentAccessToken()->delete();
        return response()->json([
            'statusCode' => 200,
            'message' => 'Token delete successful',
            'data' => $request->user()
        ]);*/
    }
}
