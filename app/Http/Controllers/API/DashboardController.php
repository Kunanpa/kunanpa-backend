<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Todo pedido realizado sin importar el estado de la venta
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ventas()
    {
        /*$data = DB::table('compra_flores')
            ->groupBy('month(created_at)')
            ->select('created_at')->count('*');*/
        $data = DB::select('SELECT COUNT(*) num, created_at FROM compra_flores  GROUP BY month(created_at)');
        $res = [
            'labels' => [ 'Aug', 'Sep', 'Oct', 'Nov'],
            'datasets' => [
                [
                    'label' => 'Performance',
                    'data' => [$data[0]->num, 0, $data[1]->num, $data[2]->num]
                ]
            ]
        ];
        return response()->json($res);
    }

    /**
     * Todo pedido que se encuentre en estado aprovado
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pedidos()
    {
        $data = DB::table('flores')
            ->join('imagenes', 'flores.id', '=', 'imagenes.idFlor')
            ->where('flores.disponible', '=', 'true')
            ->select('flores.id', 'flores.nombre', 'flores.descripcion', 'flores.precioFinal', 'flores.descuento', 'flores.precioInicial', 'flores.stock', 'imagenes.urlimagen')
            ->groupBy('flores.id')
            ->paginate(5);

        return response()->json($data[0]);
    }
}
