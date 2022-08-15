<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopingRequest;
use App\Models\Compra;
use App\Models\CompraFlore;
use App\Models\Flore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShoppingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ShopingRequest $shopingRequest
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ShopingRequest $shopingRequest)
    {
        $datos_compra = $shopingRequest->except('arreglos', 'total');
        $new_compra = Compra::create($datos_compra);
        $pedidos = $shopingRequest->only('arreglos');

        foreach ($pedidos['arreglos'] as $pedido){
            $new_pedido = CompraFlore::create([
                'cantidad' => $pedido['cantidad'],
                'costo' => $pedido['costo'],
                'idCompra' => $new_compra->id,
                'idFlor' => $pedido['idFlor']
            ]);
            DB::table('flores')->where('id', '=', $pedido['idFlor'])->increment('numVentas');
        }

        return response()->json([
            'message' => 'Pedido realizado correctamente.'
        ]);
    }// TODO: Pendiente la carga a la bd

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
