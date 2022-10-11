<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\WishListRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $idCliente = $request->user()->idPersona;
        $data = DB::table('arreglo_deseos')
            ->join('flores', 'arreglo_deseos.idFlor', '=', 'flores.id')
            ->join('imagenes', 'flores.id', '=', 'imagenes.idFlor')
            ->where('arreglo_deseos.idCliente', '=', $idCliente)
            ->where('flores.stock', '>', 0)
            ->select('flores.id AS idFlor', 'flores.nombre', 'flores.precioFinal', 'flores.descuento', 'flores.precioInicial', 'imagenes.urlimagen')
            ->groupBy('arreglo_deseos.id')
            ->get();
        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param WishListRequest $request
     * @return JsonResponse
     */
    public function store(WishListRequest $request)
    {
        $idCliente = $request->user()->idPersona;
        $exist = DB::table('arreglo_deseos')
            ->where('idCliente', '=', $idCliente)
            ->where('idFlor', '=', $request->idFlor)
            ->exists();
        if (!$exist) {
            DB::table('arreglo_deseos')->insert([
                'idCliente' => $idCliente,
                'idFlor' => $request->idFlor
            ]);
            $message = 'Arreglo agregado a la lista de deseos.';
        } else {
            DB::table('arreglo_deseos')
                ->where('idCliente', '=', $idCliente)
                ->where('idFlor', '=', $request->idFlor)
                ->delete();
            $message = 'Arreglo eliminado de la lista de deseos.';
        }
        return response()->json([
            'message' => $message
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
     * @return JsonResponse
     */
    public function destroy(int $id, Request $request)
    {
        $exist = DB::table('arreglo_deseos')
            ->where('idCliente', '=', $request->user()->idPersona)
            ->where('idFlor', '=', $id)
            ->exists();
        if ($exist) {
            DB::table('arreglo_deseos')
                ->where('idCliente', '=', $request->user()->idPersona)
                ->where('idFlor', '=', $id)
                ->delete();
            $message = 'Arreglo eliminado de la lista de deseos.';

        } else {
            $message = 'El arreglo no se encuentra en la lista de deseos del usuario.';
        }
        return response()->json([
            'message' => $message
        ]);
    }
}
