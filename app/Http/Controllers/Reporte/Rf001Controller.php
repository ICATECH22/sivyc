<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\Reporterf001Interface;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Filters\ReportFilter;
use App\Filters\FolioFilter;
use App\Filters\StatusFilter;
use App\Filters\RangeDateFilter;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use PDF;
use App\Services\ReportService;

class Rf001Controller extends Controller
{
    private $path;
    private Reporterf001Interface $rfoo1Repository;
    public function __construct(Reporterf001Interface $rfoo1Repository)
    {
        $this->rfoo1Repository = $rfoo1Repository;
        $this->path_files = env("APP_URL").'/storage/';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $concentrado = null)
    {
        if ($concentrado) {
            $getConcentrado = $this->rfoo1Repository->getDetailRF001Format($concentrado);

            // Decodificar el JSON
            $data = json_decode($getConcentrado, true);

            // Obtener los movimientos como un array PHP
            $movimientos = json_decode($data['movimientos'], true);

            // Extraer los folios de los movimientos
            $foliosMovimientos = array_column($movimientos, 'folio');
        }
        else {
            $getConcentrado = null;
            $foliosMovimientos = null;
        }

        // Recuperar los checkboxes seleccionados de los parámetros de consulta
        $selectedCheckboxes = $request->input('seleccionados', []);

        $idUnidad = Auth::user()->unidad;
        $obtenerUnidad = \DB::table('tbl_unidades')->where('id', $idUnidad)->first();
        $unidad = $obtenerUnidad->unidad;
        // Obtener la URL actual sin los parámetros especificados
        $filteredUrl = $request->except(['_token', 'ID', 'filtrar', 'idconcepto']);
        $folioGrupo = $request->get('folio_grupo');
        $getUnidad = $request->get('unidad');
        $user = Auth::user();
        $datos = $this->rfoo1Repository->index($user);
        $filters = [];
        // Crear una instancia de Carbon para la fecha actual
        $fechaActual = Carbon::now();
        // Formatear la fecha al formato deseado

        $periodo = $this->obtenerPrimerYUltimoDiaHabil($fechaActual);
        $data = $this->rfoo1Repository->getReciboQry($obtenerUnidad->unidad);
        #nuevo fechas del periodo que se obtiene la información
        $periodoInicio = $periodo[0];
        $periodoFin = $periodo[4];

        if ( !empty($request->get('fechaInicio')) || !empty($request->get('fechaFin'))) {
            $fechaInicio = $request->get('fechaInicio');
            $fechaFin = $request->get('fechaFin');

            $data->whereBetween('tbl_recibos.fecha_expedicion', [$fechaInicio, $fechaFin]);
        } else {
            $fechaInicio = $periodo[0];
            $fechaFin = $periodo[4];
        }

        if ($getUnidad !== '' && isset($getUnidad)) {
            $data->where('tbl_unidades.unidad', $request->get('unidad'));
        }


        if (isset($folioGrupo) && $folioGrupo !== '') {
            $data->where('tbl_recibos.folio_recibo', '=', $folioGrupo);
        }


        $query = $data->orderBy('tbl_recibos.id', 'ASC')->paginate(25);
        $currentYear = date('Y');
        $path_files = $this->path_files;

        // return response()->json($query);
        // view rf001
        return view('reportes.rf001.index', compact('datos', 'currentYear', 'query', 'idUnidad', 'unidad', 'path_files', 'getConcentrado', 'foliosMovimientos', 'selectedCheckboxes', 'periodoInicio', 'periodoFin', 'fechaInicio', 'fechaFin'))->render();
    }

    public function dashboard(Request $request)
    {
        // obtener unidad
        $idUnidad = Auth::user()->unidad;
        $obtenerUnidad = \DB::table('tbl_unidades')->where('id', $idUnidad)->first();
        $unidad = $obtenerUnidad->unidad;

        return view('reportes.rf001.dashboard', compact('unidad'))->render();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // manejar error de consulta con try catch
        try {
            //siempre trata de ejecutarse el código
            $response = $this->rfoo1Repository->generateRF001Format($request);
            if ($response) {
                # si se ejecutó correctamente lo envíamos a una ruta distinta
                return redirect()->route('reporte.rf001.sent')->with('message', 'Formato de concentrado de ingresos enviado!');
            } else {
                // mandar a una ruta que controle el error
                return back()->withErrors(['sent' => 'Ocurrió un error al enviar la información'])->withInput();
            }
        } catch (\Throwable $th) {
            //lanzar un catch de error ejecución, no sabemos cuál error $th;
            return back()->withErrors(['sistema' => 'Ocurrió un error interno en el sistema '. $th])->withInput();
        }

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
        $getConcentrado = $this->rfoo1Repository->getDetailRF001Format($id);
        $pathFile = $this->path_files;
        return view('reportes.rf001.detalles', compact('getConcentrado', 'pathFile'))->render();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
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
        try {
            $order = $request->only([
                'consecutivo',
                'id_unidad',
                'periodoInicio',
                'periodoFIn',
                'unidad'
            ]);
            $response = $this->rfoo1Repository->updateFormatoRf001($order, $id);
            if ($response) {
                # si la respuesta es satisfactoria
                return redirect()->route('reporte.rf001.sent')->with('message', 'Formato de concentrado de ingresos '.$request['consecutivo'].' actualizado correctamente!');
            } else {
                return back()->withErrors(['sent' => 'Ocurrió un error al actualizar la información.'])->withInput();
            }
        } catch (\Throwable $th) {
            //lanzar un catch de error ejecución, no sabemos cuál error $th;
            return back()->withErrors(['sistema' => 'Ocurrió un error interno en el sistema '. $th])->withInput();
        }
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

    protected function obtenerPrimerYUltimoDiaHabil($startDate)
    {
        // Crear un array para almacenar los días hábiles
        $diasHabiles = [];

        $primerDiaSemana = $startDate->startOfWeek();

        // Iterar sobre los próximos 7 días
        for ($i = 0; $i < 5; $i++) {
            // Obtener el día actual más el número de días de la iteración
            $dia = $primerDiaSemana->copy()->addDays($i);

            // Verificar si el día es hábil (de lunes a viernes)
            if ($dia->isWeekday()) {
                $diasHabiles[] = $dia->toDateString(); // Agregar el día al array de días hábiles
            }
        }
        return $diasHabiles;
    }

    public function getSentFormat(Request $request)
    {
        $data = $this->rfoo1Repository->sentRF001Format($request);
        return view('reportes.rf001.formatos', compact('data'))->render();
    }

    public function deatils(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeData(Request $request): JSONResponse
    {
        $order = $request->only([
            'elemento',
            'details'
        ]);

        return response()->json(
            [
                'data' => $this->rfoo1Repository->storeData($order)
            ],
            Response::HTTP_CREATED
        );
    }

    public function getPdfReport()
    {
        $idOrganismo = Auth::user()->id_organismo; # obtener el organismo administrativo id
        $organismo = \DB::table('organismos_publicos')->select('nombre_titular', 'cargo_fun')->where('id', '=', $idOrganismo)->first();
        $distintivo = \DB::table('tbl_instituto')->value('distintivo'); #texto de encabezado del pdf
        $nombreElaboro = Auth::user()->name;
        $puestoElaboro = Auth::user()->puesto;
        $report = (new ReportService())->getReport($distintivo, $organismo, 26, $nombreElaboro, $puestoElaboro);
        // return response()->json($report);
        // $pdf = PDF::loadView('reportes.rf001.reporterf001');
        return $report->stream('Reporte RF001 de ');
        // return view('reportes.rf001.reporterf001', $data)->render();
    }
}