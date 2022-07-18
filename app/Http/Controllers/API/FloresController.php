<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FloresRequest;
use App\Models\FlorCategoria;
use App\Models\Flore;
use App\Models\Imagene;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FloresController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $data = DB::table('flores')
            ->join('imagenes', 'flores.id', '=', 'imagenes.idFlor')
            ->select('flores.id', 'flores.nombre', 'flores.descripcion', 'flores.precioFinal', 'flores.descuento', 'flores.precioInicial', 'flores.stock', 'imagenes.urlimagen')
            ->groupBy('flores.id')
            ->paginate(5);
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param FloresRequest $floresRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(FloresRequest $floresRequest)
    {
        $flores = $floresRequest->except(['imagenes', 'categorias']);
        $imagenes = $floresRequest->only('imagenes');
        $categorias = $floresRequest->only('categorias');

        // Creacion de nueva flores
        $newFlor = Flore::create($flores);

        // Adicion de las imagenes
        foreach ($imagenes['imagenes'] as $imagen){
            $newImagen = new Imagene();
            $newImagen->urlImagen = $imagen['urlImagen'];
            $newImagen->idFlor = $newFlor->id;
            $newImagen->save();
        }

        // Adicion de las categorias
        foreach ($categorias['categorias'] as $cate){
            $newCate = new FlorCategoria();
            $newCate->idFlor = $newFlor->id;
            $newCate->idCategoria = $cate['idCategoria'];
            $newCate->save();
        }

        return response()->json([
            'message' => 'Registrado'
            //'data' => $floresRequest->all(),
            //'flores' => $flores,
            //'imagenes' => $newImagen,
            //'categorias' => $cate,
            //'nuevaFlor' => $newFlor
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

    /**
     * Display the specified resource.
     *
     * @param $idCategoria
     * @return \Illuminate\Http\JsonResponse
     */
    public function byCategory($idCategoria)
    {
        $data = DB::table('flores')
            ->join('imagenes', 'flores.id', '=', 'imagenes.idFlor')
            ->join('flor_categorias', 'flores.id', '=', 'flor_categorias.idFlor')
            ->where('flor_categorias.idCategoria', '=', $idCategoria)
            ->select('flores.id', 'flores.nombre', 'flores.descripcion', 'flores.precioFinal', 'flores.descuento', 'flores.precioInicial', 'flores.stock', 'imagenes.urlimagen')
            ->groupBy('flores.id')
            ->paginate(5);

        return response()->json($data);
    }
}
