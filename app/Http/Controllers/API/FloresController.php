<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FloresRequest;
use App\Models\FlorCategoria;
use App\Models\Flore;
use App\Models\Imagene;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Illuminate\Http\JsonResponse;
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
        // $imagenes = $floresRequest->only('imagenes');
        // $imagenes = $floresRequest->file('imagenes');
        $categorias = $floresRequest->only('categorias');

        // Creacion de nueva flores
        $newFlor = Flore::create($flores);

        // Adicion de las imagenes
        /*foreach ($imagenes as $imagen){
            $urlImagen = Cloudinary::upload($imagen->getRealPath())->getSecurePath();
            $newImagen = new Imagene();
            $newImagen->urlImagen = $urlImagen;
            $newImagen->idFlor = $newFlor->id;
            $newImagen->save();
        }*/
        $newImagen = new Imagene();
        $newImagen->urlImagen = 'https://res.cloudinary.com/yachayhuasi/image/upload/v1661008213/2ab50bd7-11e7-403d-a02e-ef2cb39bae39_ekmwdy.jpg';
        $newImagen->idFlor = $newFlor->id;
        $newImagen->save();

        // Adicion de las categorias
        foreach ($categorias['categorias'] as $cate){
            $newCate = new FlorCategoria();
            $newCate->idFlor = $newFlor->id;
            $newCate->idCategoria = $cate;
            $newCate->save();
        }

        return response()->json([
            'message' => 'Arreglo de flores registrado'
        ]);
        // TODO: Pendiente documetar el api para cargar flores - arreglar el request
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $flor = Flore::find($id);
        if ($flor) {
            $imagenes = Imagene::where('idFlor', $flor->id)->get('urlImagen');
            $categorias = DB::table('flor_categorias')
                ->join('categorias', 'flor_categorias.idCategoria', '=', 'categorias.id')
                ->where('flor_categorias.idFlor', '=', $flor->id)
                ->get(['categorias.id', 'categorias.nombre']);
            $data = collect($flor)->merge(['imagenes' => $imagenes])->merge(['categorias' => $categorias]);
            return response()->json([
                /*'flor' => $flor,
                'imagenes' => $imagenes,
                'categorias' => $categorias,*/
                'data' => $data
            ]);
        } else {
            return response()->json([
                'data' => null
            ]);
        }

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

    /**
     * Display the specified resource.
     *
     * @param $idCategoria
     * @return \Illuminate\Http\JsonResponse
     */
    public function bySpecialCategory($idCategoria)
    {
        if ($idCategoria == 1) {
            // $data = Flore::orderBy('numVentas', 'DESC')->take(4)->get();
            $data = DB::table('flores')
                ->join('imagenes', 'flores.id', '=', 'imagenes.idFlor')
                ->select('flores.id', 'flores.nombre', 'flores.descripcion', 'flores.precioFinal', 'flores.descuento', 'flores.precioInicial', 'flores.stock', 'imagenes.urlimagen')
                ->groupBy('flores.id')
                ->orderBy('flores.numVentas', 'DESC')
                ->take(4)
                ->get();
        } elseif ($idCategoria == 2) {
            $data = DB::table('flores')
                ->join('imagenes', 'flores.id', '=', 'imagenes.idFlor')
                ->select('flores.id', 'flores.nombre', 'flores.descripcion', 'flores.precioFinal', 'flores.descuento', 'flores.precioInicial', 'flores.stock', 'imagenes.urlimagen')
                ->groupBy('flores.id')
                ->orderBy('flores.updated_at', 'DESC')
                ->take(4)
                ->get();
        } elseif ($idCategoria == 3) {
            $dataHombre = DB::table('flores')
                ->join('imagenes', 'flores.id', '=', 'imagenes.idFlor')
                ->join('flor_categorias', 'flores.id', '=', 'flor_categorias.idFlor')
                ->where('flor_categorias.idCategoria', '=', 7)
                ->select('flores.id', 'flores.nombre', 'flores.descripcion', 'flores.precioFinal', 'flores.descuento', 'flores.precioInicial', 'flores.stock', 'imagenes.urlimagen')
                ->groupBy('flores.id')
                ->take(2)
                ->get();
            $dataMujer = DB::table('flores')
                ->join('imagenes', 'flores.id', '=', 'imagenes.idFlor')
                ->join('flor_categorias', 'flores.id', '=', 'flor_categorias.idFlor')
                ->where('flor_categorias.idCategoria', '=', 8)
                ->select('flores.id', 'flores.nombre', 'flores.descripcion', 'flores.precioFinal', 'flores.descuento', 'flores.precioInicial', 'flores.stock', 'imagenes.urlimagen')
                ->groupBy('flores.id')
                ->take(2)
                ->get();
            $data = collect($dataHombre)->merge($dataMujer);
        } else {
            $data = null;
        }


        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @return JsonResponse
     */
    public function byStore($idStore)
    {
        $data = DB::table('flores')
            ->join('imagenes', 'flores.id', '=', 'imagenes.idFlor')
            ->join('flor_categorias', 'flores.id', '=', 'flor_categorias.idFlor')
            ->join('categorias', 'flor_categorias.idCategoria', '=', 'categorias.id')
            ->where('flores.idVendedor', '=', $idStore)
            ->select('flores.id', 'flores.nombre', 'flores.descripcion', 'flores.precioFinal', 'flores.descuento', 'flores.precioInicial', 'flores.stock', 'imagenes.urlimagen', 'categorias.nombre AS categoria')
            ->groupBy('flores.id')
            ->paginate(10);

        return response()->json($data);
    }
}
