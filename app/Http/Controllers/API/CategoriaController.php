<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriaRequest;
use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categorias = Categoria::all();
        return response()->json([
            'data' => $categorias
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoriaRequest $categoriaRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoriaRequest $categoriaRequest)
    {
        // $validate = $categoriaRequest->validate('nombre');
        $respuesta = Categoria::create($categoriaRequest->all());
        return response()->json([
            'data' => $categoriaRequest->all(),
            'response' => $respuesta
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
