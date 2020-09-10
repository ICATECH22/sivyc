<?php
/* Creador: Orlando Chavez */
namespace App\Http\Controllers\webController;

use App\Models\pago;
use App\Models\instructor;
use App\Models\contratos;
use App\Models\folio;
use App\Models\directorio;
use App\Models\contrato_directorio;
use Illuminate\Http\Request;
use Redirect,Response;
use App\Http\Controllers\Controller;
use PDF;

class PagoController extends Controller
{
    public function fill(Request $request)
    {
        $instructor = new instructor();
        $input = $request->numero_contrato;
        $newsAll = $instructor::where('id', $input)->first();
        return response()->json($newsAll, 200);
    }

    public function index()
    {
        $contrato = new contratos();

        $contratos_folios = $contrato::SELECT('contratos.id_contrato', 'contratos.numero_contrato', 'contratos.cantidad_letras1',
        'contratos.unidad_capacitacion', 'contratos.municipio',
        'contratos.fecha_firma', 'contratos.docs', 'contratos.observacion', 'folios.status', 'folios.id_folios','folios.id_supre')
        ->WHEREIN('folios.status', ['Verificando_Pago','Pago_Verificado','Pago_Rechazado','Finalizado'])
        ->LEFTJOIN('folios','folios.id_folios', '=', 'contratos.id_folios')
        ->GET();


        return view('layouts.pages.vstapago', compact('contratos_folios'));
    }

    public function crear_pago($id)
    {
        $data = contratos::SELECT('instructores.numero_control','instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno',
                                  'tbl_cursos.curso','tbl_cursos.clave','contratos.unidad_capacitacion','folios.id_folios','folios.importe_total','folios.iva','pagos.id AS id_pago')
                                    ->WHERE('contratos.id_contrato', '=', $id)
                                    ->LEFTJOIN('folios', 'folios.id_folios', '=', 'contratos.id_folios')
                                    ->LEFTJOIN('tbl_cursos', 'tbl_cursos.id', 'folios.id_cursos')
                                    ->LEFTJOIN('instructores', 'instructores.id', 'tbl_cursos.id_instructor')
                                    ->LEFTJOIN('pagos', 'pagos.id_contrato', '=', 'contratos.id_contrato')
                                    ->FIRST();

        $nomins = $data->nombre . ' ' . $data->apellidoPaterno . ' ' . $data->apellidoMaterno;
        $importe = round($data->importe_total-$data->iva, 2);
        return view('layouts.pages.frmpago', compact('data', 'nomins','importe'));
    }

    public function modificar_pago()
    {
        return view('layouts.pages.modpago');
    }

    public function verificar_pago($idfolios)
    {
        $folio = folio::findOrfail($idfolios);
        $folio->status = 'Pago_Verificado';
        $folio->save();
        return redirect()->route('pago-inicio');
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
        $contrato = new contratos();

        $contratos = $contrato::SELECT('contratos.id_contrato', 'contratos.numero_contrato', 'contratos.cantidad_numero',
        'contratos.unidad_capacitacion', 'contratos.municipio', 'contratos.fecha_firma','contratos.arch_factura',
        'folios.status', 'folios.id_folios','tbl_cursos.id_instructor','instructores.id AS idins','instructores.archivo_bancario')
        ->WHERE('contratos.id_contrato', '=', $id)
        ->LEFTJOIN('folios','folios.id_folios', '=', 'contratos.id_folios')
        ->LEFTJOIN('tbl_cursos','tbl_cursos.id', '=', 'folios.id_cursos')
        ->LEFTJOIN('instructores','instructores.id', '=', 'tbl_cursos.id_instructor')
        ->FIRST();

        $datapago = pago::WHERE('id_contrato', '=', $id)->FIRST();

        $data_directorio = contrato_directorio::WHERE('id_contrato', '=', $contratos->id_contrato)->FIRST();
        $director = directorio::SELECT('nombre','apellidoPaterno','apellidoMaterno','id')->WHERE('id', '=', $data_directorio->contrato_iddirector)->FIRST();

        return view('layouts.pages.vstvalidarpago', compact('contratos','director','datapago'));
    }

    public function guardar_pago(Request $request)
    {
        pago::where('id', '=', $request->id_pago)
        ->update(['no_pago' => $request->numero_pago,
                  'fecha' => $request->fecha_pago,
                  'descripcion' => $request->concepto]);

        folio::WHERE('id_folios', '=', $request->id_folio)
        ->update(['status' => 'Finalizado']);


        return redirect()->route('pago-inicio');
    }

    public function pago_validar($idfolio)
    {
        $folio = folio::findOrfail($idfolio);
        $folio->status = 'Pago_Verificado';
        $folio->save();
        return redirect()->route('pago-inicio')->with('info', 'El pago ha sido verificado exitosamente.');
    }

    public function mostrar_pago($id)
    {
        $data = contratos::SELECT('instructores.numero_control','instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno',
                                  'tbl_cursos.curso','tbl_cursos.clave','contratos.unidad_capacitacion','folios.id_folios','folios.importe_total','folios.iva',
                                  'pagos.id AS id_pago','pagos.no_memo','pagos.fecha','pagos.no_pago','pagos.descripcion')
                           ->WHERE('contratos.id_contrato', '=', $id)
                           ->LEFTJOIN('folios', 'folios.id_folios', '=', 'contratos.id_folios')
                           ->LEFTJOIN('tbl_cursos', 'tbl_cursos.id', 'folios.id_cursos')
                           ->LEFTJOIN('instructores', 'instructores.id', 'tbl_cursos.id_instructor')
                           ->LEFTJOIN('pagos', 'pagos.id_contrato', '=', 'contratos.id_contrato')
                           ->FIRST();

        $nomins = $data->nombre . ' ' . $data->apellidoPaterno . ' ' . $data->apellidoMaterno;

        //return view('layouts.pages.vstapagofinalizado', compact('data', 'nomins'));
        $pdf = PDF::loadView('layouts.pages.vstapagofinalizado', compact('data', 'nomins'));

        return $pdf->download('medium.pdf');
    }
}
