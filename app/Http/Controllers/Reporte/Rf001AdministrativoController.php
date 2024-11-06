<?php

namespace App\Http\Controllers\Reporte;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\Reporterf001Interface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Reportes\Rf001Model;
use App\Models\Tokens_icti;
use App\Services\ReportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use setasign\Fpdi\Fpdi;
use Illuminate\Support\Facades\Http;
use setasign\Fpdi\PdfParser\StreamReader;

class Rf001AdministrativoController extends Controller
{
    private Reporterf001Interface $rf001Repository;
    private $path_files;
    public function __construct(Reporterf001Interface $rf001Repository)
    {
        $this->rf001Repository = $rf001Repository;
        $this->path_files = env("APP_URL").'/storage/';
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $datos = $this->rf001Repository->getFirmadoFormat($request);
        return view('reportes.rf001.formatofirma', compact('datos'))->render();
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
        //
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
        $getConcentrado = $this->rf001Repository->getDetailRF001Format($id);
        $getFirmante = $this->rf001Repository->getSigner(Auth::user()->id);
        $memorandum = $getConcentrado->memorandum;
        $cadenaOriginal = DB::table('documentos_firmar')->select('cadena_original', 'id', 'documento')->where('numero_o_clave', $memorandum)->first();

        // crear un arreglo

        if ($cadenaOriginal) {
            $data = [
                'cadenaOriginal' => $cadenaOriginal->cadena_original,
                'indice' => $cadenaOriginal->id,
                'baseXml' => base64_decode($cadenaOriginal->documento)
            ];
        } else {
            $data = [
                'cadenaOriginal' => null,
                'indice' => null,
                'baseXml' => null,
            ];
        }

        $getToken = Tokens_icti::latest()->first();

        if (!isset($token)) {
            // no hay registros
            $token = (new ReportService())->generarToken();
        } else {
            $token = $getToken->token;
        }

        // obtener revision
        $revisionLocal = collect(json_decode($getConcentrado->movimiento, true))->first(function ($item) {
            return isset($item['tipo']) && $item['tipo'] === 'REVISION_GENERAL';
        });
        $pathFile = $this->path_files;
        $curpFirmante = $getFirmante->curp;
        return view('reportes.rf001.revision.detalle_revision', compact('getConcentrado', 'pathFile', 'id', 'data', 'token', 'curpFirmante', 'revisionLocal'))->render();
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

    public function sendBack(Request $request)
    {
        $id = $request->get('idRf001');
        $this->rf001Repository->regresarEstadoRecibo($id);
        return response()->json([
            'resp' => $this->rf001Repository->reenviarSolicitud($request),
            'message' => 'Documento Regresado para Corrección!',
         ], Response::HTTP_CREATED);
    }

    public function firmar(Request $request)
    {
        $firma = $this->rf001Repository->firmarDocumento($request);
        if ($firma == 1) {
            # TODO : - listo a mostrar el resultado
            return redirect()->route('administrativo.index')->with('message', 'Documento Firmando y espera de Sello!');
        }
    }

    public function sellado(Request $request)
    {
        try {
            $sello = $this->rf001Repository->sellarDocumento($request);
            if ($sello == 1) {
                return redirect()->route('administrativo.index')->with('message', 'Documento sellado exitosamente!');
            } else {
                return redirect()->back()->with('error', $sello);
            }

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al momento de sellar: '.$th);
        }
    }

    public function aprobar(Request $request)
    {
        $estado = 'APROBADO';
        $id = $request->get('idRf001');
        $this->rf001Repository->actualizarRecibo($id);
        return response()->json([
            'resp' => $this->rf001Repository->actualizarEstado($id, $estado),
            'message' => 'Documento Aprobado para proceso de efirma!',
        ], Response::HTTP_CREATED);
    }

    public function generarMasivo($id)
    {
        try {
            $data = $this->rf001Repository->generarPdfMasivo($id);
            $pdf = new Fpdi();
            foreach ($data as $key) {
                // $rutaLocal = str_replace("https://sivyc.icatech.gob.mx/storage", storage_path('app/public'), $key);
                // if (!file_exists($rutaLocal)) {
                //     return response()->json(['error' => "El documento PDF en la ruta ". $key ." no se encontró"], 404);
                // }

                $response = Http::get($key);

                // $pdf->setSourceFile($rutaLocal);
                // $totalPaginas = $pdf->setSourceFile($key);

                // // Importar cada página del documento PDF
                // for ($i = 1; $i <= $totalPaginas; $i++) {
                //     $pdf->AddPage();
                //     $paginaId = $pdf->importPage($i);
                //     $pdf->useTemplate($paginaId, 10, 10, 190); // Ajusta la posición y tamaño según necesites
                // }

                if ($response->ok()) {
                    $pdfContent = $response->body();

                    // Cargar el contenido PDF en FPDI usando StreamReader
                    $pdf->setSourceFile(StreamReader::createByString($pdfContent));
                    $totalPaginas = $pdf->setSourceFile(StreamReader::createByString($pdfContent));

                    // Importar cada página del PDF
                    for ($i = 1; $i <= $totalPaginas; $i++) {
                        $pdf->AddPage();
                        $paginaId = $pdf->importPage($i);
                        $pdf->useTemplate($paginaId, 10, 10, 190); // Ajusta la posición y tamaño si es necesario
                    }
                } else {
                    return response()->json(['error' => "No se pudo cargar el archivo desde la URL: $url"], 404);
                }
            }

             // Salida del PDF combinado
            return response()->make($pdf->Output('S'), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="documento_concentrado_recibos_Rf001.pdf"',
            ]);
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Ocurrió un error al generar el documento masivo: '.$th);
        }
    }
}
