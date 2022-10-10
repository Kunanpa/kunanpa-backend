<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ShopingRequest;
use App\Http\Requests\StatusOrderRequest;
use App\Models\Compra;
use App\Models\CompraFlore;
use App\Models\Flore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use MercadoPago;

class ShoppingController extends Controller
{
    public function __construct()
    {
        MercadoPago\SDK::setAccessToken(config('services.mercadopago.token'));
    }

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
        $datos_compra = $shopingRequest->except('arreglos');
        $new_compra = Compra::create($datos_compra);
        $pedidos = $shopingRequest->only('arreglos');

        // Crea un objeto de preferencia
        $preference = new MercadoPago\Preference();

        foreach ($pedidos['arreglos'] as $pedido){
            $new_pedido = CompraFlore::create([
                'cantidad' => $pedido['cantidad'],
                'costo' => $pedido['costo'],
                'idCompra' => $new_compra->id,
                'idFlor' => $pedido['idFlor']
            ]);
            DB::table('flores')->where('id', '=', $pedido['idFlor'])->increment('numVentas');
            $flores = DB::table('flores')->where('id','=', $pedido['idFlor'])->get(['id', 'nombre', 'precioFinal']);

            // Crea un ítem en la preferencia
            $item = new MercadoPago\Item();
            $item->title = $flores[0]->nombre;
            $item->quantity = $pedido['cantidad'];
            $item->unit_price = $flores[0]->precioFinal;

            $products[] = $item;
            $pedidosId[] = $new_pedido->id;
        }

        $preference->items = $products;
        $preference->metadata = ['idPedido' => $pedidosId];
        $preference->save();

        return response()->json([
            'message' => 'Pedido realizado correctamente.',
            'MP-link' => $preference->init_point,
            'key-client-side' => [
                'public-key' => config('services.mercadopago.key'),
                'preference-id' => $preference->id
            ]
        ]);
    }
    //TODO: Pendiente decidir los datos de retorno del API

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
     * Display a listing of orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getOrders($idStore)
    {
        $data = DB::table('compras')
            ->join('compra_flores', 'compras.id', '=', 'compra_flores.idCompra')
            ->join('flores', 'flores.id', '=', 'compra_flores.idFlor')
            // ->select('compras.id', 'compras.nombres', 'compras.created_at', 'compra_flores.estado', 'compras.total')
            ->select('compras.id AS idPedido', DB::raw("CONCAT('# ', compras.id, ' ', compras.nombres) AS pedido"),'compras.created_at AS fecha', 'compra_flores.estado', 'compras.total')
            ->groupBy('compras.id')
            ->where('flores.idVendedor', '=', $idStore)
            ->get();

        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Display a listing of orders.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ordersDetails($idOrder)
    {
        $compras = DB::table('compras')
            ->where('id', '=', $idOrder)
            ->get();

        $email = DB::table('users')
            ->join('personas', 'users.idPersona', 'personas.id')
            ->where('users.idPersona', '=', $compras[0]->idCliente)
            ->get('email');

        $compra_flores = DB::table('compra_flores')
            ->where('idCompra', '=', $idOrder)
            ->get();

        $flores = array();
        foreach ($compra_flores as $compra) {
            $flores[] = collect(DB::table('flores')
                ->join('imagenes', 'imagenes.idFlor', '=', 'flores.id')
                ->groupBy('flores.id')
                ->select('imagenes.urlImagen' ,'flores..nombre', 'flores.precioFinal AS costo')
                ->where('flores.id', '=', $compra->idFlor)
                ->get()[0])->merge(['cantidad' => $compra->cantidad, 'total' => $compra->costo]);
        }

        $data = [
            'idCompra' => $compras[0]->id,
            'fecha' => $compras[0]->created_at,
            'estado' => $compra_flores[0]->estado,
            'facturacion' => $compras[0]->direccion.' - '.$compras[0]->distrito.' - '.$compras[0]->codigoPostal.' - '.$compras[0]->pais,
            'email' => $email[0]->email,
            'telefono' => $compras[0]->numTelefono,
            'direccion' => $compras[0]->direccion.' - '.$compras[0]->distrito,
            'nota' => $compras[0]->nota,
            'articulos' => $flores
        ];

        return response()->json(['data' => $data]);
    }

    /**
     * Display a listing of orders.
     *
     * @param StatusOrderRequest $request
     * @return JsonResponse
     */
    public function changeStatus(StatusOrderRequest $request)
    {
        DB::table('compra_flores')->where('idCompra', '=', $request->idCompra)->update(['estado' => $request->nuevoEstado]);
        return response()->json([
            'message' => "Estado actualizado a '{$request->nuevoEstado}'"
        ]);
    }

    /**
     * Display a listing of orders.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function webhookMP(Request $request)
    {
        $data = $request->get('data');

        $response = Http::withToken(config('services.mercadopago.token'))->get('https://api.mercadopago.com/v1/payments/'.$data['id']);
        //Storage::disk('local')->put('webhook.txt',json_encode($response['metadata']['id_pedido']));
        //Storage::disk('local')->put('webhook.txt',json_encode($response['status']));

        foreach ($response['metadata']['id_pedido'] as $idPedido){
            DB::table('compra_flores')->where('id', '=', $idPedido)->update(['estado' => $response['status']]);
        }


        return response()->json([
            'message' => "Ok"
        ]);
    }
}
