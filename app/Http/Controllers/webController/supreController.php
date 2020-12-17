<?php
// Creado Por Orlando Chavez
namespace App\Http\Controllers\webController;

use App\Models\instructor;
use App\Models\supre;
use App\Models\folio;
use App\Models\tbl_curso;
use Illuminate\Support\Facades\Storage;
use App\ProductoStock;
use App\Models\cursoValidado;
use App\Models\supre_directorio;
use App\Models\directorio;
use App\Models\criterio_pago;
use App\Models\tbl_unidades;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use function PHPSTORM_META\type;
use Carbon\Carbon;

class supreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    Public function opcion(){
        return view('layouts.pages.vstasolicitudopc');
    }

    public function solicitud_supre_inicio(Request $request) {
        /**
         * parametros de busqueda
         */
        $busqueda_suficiencia = $request->get('busquedaporSuficiencia');
        $tipoSuficiencia = $request->get('tipo_suficiencia');

        $supre = new supre();
        $data = $supre::BusquedaSupre($tipoSuficiencia, $busqueda_suficiencia)->where('id', '!=', '0')->latest()->get();



        return view('layouts.pages.vstasolicitudsupre', compact('data'));
    }

    public function solicitud_folios(){
        $supre = new supre();
        $data2 = $supre::SELECT('tabla_supre.id','tabla_supre.no_memo','tabla_supre.unidad_capacitacion','tabla_supre.fecha','folios.status','folios.id_folios',
        'folios.folio_validacion')
                        ->where('folios.status', '!=', 'x')
                        ->LEFTJOIN('folios', 'tabla_supre.id', '=', 'folios.id_supre')
                        ->get();

        return view('layouts.pages.vstasolicitudfolio', compact('data2'));
    }

    public function frm_formulario() {
        $unidades = tbl_unidades::SELECT('unidad')->WHERE('id', '!=', '0')->GET();

        return view('layouts.pages.delegacionadmin', compact('unidades'));
    }

    public function store(Request $request) {
        $supre = new supre();
        $curso_validado = new tbl_curso();
        $directorio = new supre_directorio();

        //Guarda Solicitud
        $supre->unidad_capacitacion = strtoupper($request->unidad);
        $supre->no_memo = strtoupper($request->memorandum);
        $supre->fecha = strtoupper($request->fecha);
        $supre->status = 'En_Proceso';
        $supre->fecha_status = strtoupper($request->fecha);
        $supre->save();

       $id = $supre->id;
       $directorio->supre_dest = $request->id_destino;
       $directorio->supre_rem = $request->id_remitente;
       $directorio->supre_valida = $request->id_valida;
       $directorio->supre_elabora = $request->id_elabora;
       $directorio->supre_ccp1 = $request->id_ccp1;
       $directorio->supre_ccp2 = $request->id_ccp2;
       $directorio->id_supre = $id;
       $directorio->save();

        //Guarda Folios
        foreach ($request->addmore as $key => $value){
            $folio = new folio();
            $folio->folio_validacion = strtoupper($value['folio']);
            $folio->iva = $value['iva'];
            $folio->comentario = $value['comentario'];
            $clave = strtoupper($value['clavecurso']);
            $hora = $curso_validado->SELECT('tbl_cursos.dura','tbl_cursos.id')
                    ->WHERE('tbl_cursos.clave', '=', $clave)
                    ->FIRST();
            $importe = $value['importe']/1.16;
            $X = $hora->dura;
            if ($X != NULL)
            {
                if (strpos($hora->dura, " ")) {
                    # si tiene un espacio en blanco la cadena
                    $str_horas = explode (" ", $hora->dura);
                    $horas = (int) $str_horas[0];
                } else {
                    $horas = (int) $hora->dura;
                }
                $importe_hora = $importe / $horas;
                $folio->importe_hora = $importe_hora;
                $folio->importe_total = $value['importe'];
                $folio->id_supre = $id;
                $folio->id_cursos = $hora->id;
                $folio->status = 'En_Proceso';
                $folio->save();
            }
            else
            {
                supre::WHERE('id', '=', $id)->DELETE();
                supre_directorio::WHERE('id_supre', '=', $id)->DELETE();
                return redirect()->route('supre-inicio')
                        ->with('success','Error Interno. Intentelo mas tarde.');
            }
        }


        return redirect()->route('supre-inicio')
                        ->with('success','Solicitud de Suficiencia Presupuestal agregado');
    }

    public function solicitud_modificar($id)
    {
        $supre = new supre();
        $folio = new folio();
        $getdestino = null;
        $getremitente = null;
        $getvalida = null;
        $getelabora = null;
        $getccp1 = null;
        $getccp2 = null;

        $directorio = supre_directorio::WHERE('id_supre', '=', $id)->FIRST();
        $getsupre = $supre::WHERE('id', '=', $id)->FIRST();

        $unidadsel = tbl_unidades::SELECT('unidad')->WHERE('unidad', '=', $getsupre->unidad_capacitacion)->FIRST();
        $unidadlist = tbl_unidades::SELECT('unidad')->WHERE('unidad', '!=', $getsupre->unidad_capacitacion)->GET();

        $getfolios = $folio::SELECT('folios.id_folios','folios.folio_validacion','folios.comentario',
                                    'folios.importe_total','folios.iva','tbl_cursos.clave')
                            ->WHERE('id_supre','=', $getsupre->id)
                            ->LEFTJOIN('tbl_cursos', 'tbl_cursos.id', '=', 'folios.id_cursos')
                            ->GET();
        if($directorio->supre_dest != NULL)
        {
            $getdestino = directorio::WHERE('id', '=', $directorio->supre_dest)->FIRST();
        }
        if($directorio->supre_rem != NULL)
        {
            $getremitente = directorio::WHERE('id', '=', $directorio->supre_rem)->FIRST();
        }
        if($directorio->supre_valida != NULL)
        {
            $getvalida = directorio::WHERE('id', '=', $directorio->supre_valida)->FIRST();
        }
        if($directorio->supre_elabora != NULL)
        {
            $getelabora = directorio::WHERE('id', '=', $directorio->supre_elabora)->FIRST();
        }
        if($directorio->supre_ccp1 != NULL)
        {
            $getccp1 = directorio::WHERE('id', '=', $directorio->supre_ccp1)->FIRST();
        }
        if($directorio->supre_ccp2 != NULL)
        {
            $getccp2 = directorio::WHERE('id', '=', $directorio->supre_ccp2)->FIRST();
        }






        return view('layouts.pages.modsupre',compact('getsupre','getfolios','getdestino','getremitente','getvalida','getelabora','getccp1','getccp2','directorio', 'unidadsel','unidadlist'));
    }

    public function solicitud_mod_guardar(Request $request)
    {
        //dd($request);
        $supre = new supre();
        $curso_validado = new tbl_curso();

        supre::where('id', '=', $request->id_supre)
        ->update(['status' => 'En_Proceso',
                  'unidad_capacitacion' => $request->unidad,
                  'no_memo' => $request->no_memo,
                  'fecha' => $request->fecha,
                  'fecha_status' => carbon::now()]);

        supre_directorio::where('id', '=', $request->id_directorio)
        ->update(['supre_dest' => $request->id_destino,
                  'supre_rem' => $request->id_remitente,
                  'supre_valida' => $request->id_valida,
                  'supre_elabora' => $request->id_elabora,
                  'supre_ccp1' => $request->id_ccp1,
                  'supre_ccp2' => $request->id_ccp2,]);

            if($request->id_supre != NULL)
            {
                folio::WHERE('id_supre', '=', $request->id_supre)->DELETE();
            }
            $id = $supre::SELECT('id')->WHERE('no_memo', '=', $request->no_memo)->FIRST();
        //Guarda Folios
        foreach ($request->addmore as $key => $value){
            $folio = new folio();
            $folio->folio_validacion = $value['folio'];
            $folio->iva = $value['iva'];
            $folio->comentario = $value['comentario'];
            $clave = $value['clavecurso'];
            $hora = $curso_validado->SELECT('tbl_cursos.dura','tbl_cursos.id')
                    ->WHERE('tbl_cursos.clave', '=', $clave)
                    ->FIRST();
            $importe = $value['importe']/1.16;
            $importe_hora = $importe / $hora->dura;
            $folio->importe_hora = $importe_hora;
            $folio->importe_total = $value['importe'];
            $folio->id_supre = $id->id;
            $folio->id_cursos = $hora->id;
            $folio->status = 'En_Proceso';
            $folio->save();
        }

        return redirect()->route('supre-inicio')
                        ->with('success','Solicitud de Suficiencia Presupuestal agregado');
    }

    public function validacion_supre_inicio(){
        return view('layouts.pages.initvalsupre');
    }

    public function validacion($id){
        $supre = new supre();
        $data =  $supre::WHERE('id', '=', $id)->FIRST();
        $directorio = supre_directorio::WHERE('id_supre', '=', $id)->FIRST();
        $getremitente = directorio::WHERE('id', '=', $directorio->supre_rem)->FIRST();

        return view('layouts.pages.valsupre',compact('data','getremitente','directorio'));
    }

    public function supre_rechazo(Request $request){
        $supre = supre::find($request->id);
        $supre->observacion = $request->comentario_rechazo;
        $supre->fecha_status = carbon::now();
        $supre->status = 'Rechazado';
        //dd($supre);
        $supre->save();
            return redirect()->route('supre-inicio')
                    ->with('success','Suficiencia Presupuestal Rechazado');
    }

    public function supre_validado(Request $request){
        $supre = supre::find($request->id);
        $supre->status = 'Validado';
        $supre->folio_validacion = $request->folio_validacion;
        $supre->fecha_validacion = $request->fecha_val;
        $supre->fecha_status = carbon::now();
        $supre->save();

        supre_directorio::where('id', '=', $request->directorio_id)
        ->update(['val_firmante' => $request->id_firmante,
                  'val_ccp1' => $request->id_ccp1,
                  'val_ccp2' => $request->id_ccp2,
                  'val_ccp3' => $request->id_ccp3,
                  'val_ccp4' => $request->id_ccp4,]);

        folio::where('id_supre', '=', $request->id)
        ->update(['status' => 'Validado']);

        $id = $request->id;
        $directorio_id = $request->directorio_id;
        return view('layouts.pages.valsuprecheck', compact('id', 'directorio_id'));
    }

    public function valsupre_checkmod(Request $request){
        $data = supre::find($request->id);
        $directorio = supre_directorio::find($request->directorio_id);
        $getfirmante = directorio::WHERE('id', '=', $directorio->val_firmante)->FIRST();
        $getremitente = directorio::WHERE('id', '=', $directorio->supre_rem)->FIRST();
        $getccp1 = directorio::WHERE('id', '=', $directorio->val_ccp1)->FIRST();
        $getccp2 = directorio::WHERE('id', '=', $directorio->val_ccp2)->FIRST();
        $getccp3 = directorio::WHERE('id', '=', $directorio->val_ccp3)->FIRST();
        $getccp4 = directorio::WHERE('id', '=', $directorio->val_ccp4)->FIRST();

        return view('layouts.pages.valsupremod', compact('data', 'directorio','getremitente','getfirmante','getccp1','getccp2','getccp3','getccp4'));
    }

    public function delete($id)
    {
        supre_directorio::WHERE('id_supre', '=', $id)->DELETE();
        folio::where('id_supre', '=', $id)->delete();
        supre::where('id', '=', $id)->delete();

        return redirect()->route('supre-inicio')
                    ->with('success','Suficiencia Presupuestal Eliminada');
    }

    protected function getcursostats(Request $request)
    {
        if (isset($request->valor)){
            /*Aquí si hace falta habrá que incluir la clase municipios con include*/
            $claveCurso = $request->valor;
            $Curso = new tbl_curso();
            $Cursos = $Curso->SELECT('tbl_cursos.ze','tbl_cursos.cp','tbl_cursos.dura')
                                    ->WHERE('clave', '=', $claveCurso)->FIRST();

            if($Cursos != NULL)
            {
                if ($Cursos->ze == 'II')
                {
                    $criterio = criterio_pago::SELECT('monto_hora_ze2 AS monto')->WHERE('id', '=' , $Cursos->cp)->FIRST();
                }
                else
                {
                    $criterio = criterio_pago::SELECT('monto_hora_ze3 AS monto')->WHERE('id', '=' , $Cursos->cp)->FIRST();
                }

                if($criterio != NULL)
                {
                    $total = $criterio->monto * $Cursos->dura;
                }
                else
                {
                    $total = 'N/A';
                }
            }
            else
            {
                $total = 'N/A';
            }
            $json=json_encode($total);
        }else{
            $json=json_encode(array('error'=>'No se recibió un valor de id de Especialidad para filtar'));
        }


        return $json;
    }

    public function doc_valsupre_upload(Request $request)
    {
        if ($request->hasFile('doc_validado')) {
            $supre = supre::find($request->idinsmod);
            $doc = $request->file('doc_validado'); # obtenemos el archivo
            $urldoc = $this->pdf_upload($doc, $request->idinsmod, 'valsupre_firmado'); # invocamos el método
            $supre->doc_validado = $urldoc; # guardamos el path
            $supre->save();
            return redirect()->route('supre-inicio')
                    ->with('success','Validación de Suficiencia Presupuestal Firmada ha sido cargada con Extio');
        }
    }

    public function supre_pdf($id){
        $supre = new supre();
        $folio = new folio();
        $data_supre = $supre::WHERE('id', '=', $id)->FIRST();
        $data_folio = $folio::WHERE('id_supre', '=', $id)->GET();
        $date = strtotime($data_supre->fecha);
        $D = date('d', $date);
        $MO = date('m',$date);
        $M = $this->monthToString(date('m',$date));
        $Y = date("Y",$date);

        $directorio = supre_directorio::WHERE('id_supre', '=', $id)->FIRST();
        $getdestino = directorio::WHERE('id', '=', $directorio->supre_dest)->FIRST();
        $getremitente = directorio::WHERE('id', '=', $directorio->supre_rem)->FIRST();
        $getvalida = directorio::WHERE('id', '=', $directorio->supre_valida)->FIRST();
        $getelabora = directorio::WHERE('id', '=', $directorio->supre_elabora)->FIRST();
        $getccp1 = directorio::WHERE('id', '=', $directorio->supre_ccp1)->FIRST();
        $getccp2 = directorio::WHERE('id', '=', $directorio->supre_ccp2)->FIRST();

        $pdf = PDF::loadView('layouts.pdfpages.presupuestaria',compact('data_supre','data_folio','D','M','Y','getdestino','getremitente','getvalida','getelabora','getccp1','getccp2','directorio'));
        return  $pdf->stream('medium.pdf');
    }

    public function tablasupre_pdf($id){
        $supre = new supre;
        $curso = new tbl_curso;
        $data = supre::SELECT('tabla_supre.fecha','folios.folio_validacion','folios.importe_hora','folios.iva','folios.importe_total',
                        'folios.comentario','instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','tbl_cursos.unidad',
                        'tbl_cursos.curso AS curso_nombre','tbl_cursos.clave','tbl_cursos.ze','tbl_cursos.dura')
                    ->WHERE('id_supre', '=', $id )
                    ->LEFTJOIN('folios', 'folios.id_supre', '=', 'tabla_supre.id')
                    ->LEFTJOIN('tbl_cursos', 'tbl_cursos.id', '=', 'folios.id_cursos')
                    ->LEFTJOIN('instructores', 'instructores.id', '=', 'tbl_cursos.id_instructor')
                    ->GET();
        $data2 = supre::WHERE('id', '=', $id)->FIRST();

        $directorio = supre_directorio::WHERE('id_supre', '=', $id)->FIRST();
        $getremitente = directorio::WHERE('id', '=', $directorio->supre_rem)->FIRST();

        $date = strtotime($data2->fecha);
        $D = date('d', $date);
        $M = $this->monthToString(date('m',$date));
        $Y = date("Y",$date);

        $datev = strtotime($data2->fecha_validacion);
        $Dv = date('d', $datev);
        $Mv = $this->monthToString(date('m',$datev));
        $Yv = date("Y",$datev);

        $pdf = PDF::loadView('layouts.pdfpages.solicitudsuficiencia', compact('data','data2','D','M','Y','Dv','Mv','Yv','getremitente'));
        $pdf->setPaper('A4', 'Landscape');

        return $pdf->stream('download.pdf');

        return view('layouts.pdfpages.solicitudsuficiencia', compact('data','data2'));
    }

    public function valsupre_pdf($id){
        $supre = new supre;
        $curso = new tbl_curso;
        $recursos = array();
        $i = 0;
        $data = supre::SELECT('tabla_supre.fecha','folios.folio_validacion','folios.importe_hora','folios.iva','folios.importe_total',
                        'folios.comentario','instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','tbl_cursos.unidad',
                        'cursos.nombre_curso AS curso_nombre','tbl_cursos.clave','tbl_cursos.ze','tbl_cursos.dura','tbl_cursos.hombre','tbl_cursos.mujer')
                    ->WHERE('id_supre', '=', $id )
                    ->LEFTJOIN('folios', 'folios.id_supre', '=', 'tabla_supre.id')
                    ->LEFTJOIN('tbl_cursos', 'tbl_cursos.id', '=', 'folios.id_cursos')
                    ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                    ->LEFTJOIN('instructores', 'instructores.id', '=', 'tbl_cursos.id_instructor')
                    ->GET();
        $data2 = supre::WHERE('id', '=', $id)->FIRST();

        $cadwell = folio::SELECT('id_cursos')->WHERE('id_supre', '=', $id)->GET();
        foreach ($cadwell as $item)
        {
            $h = tbl_curso::SELECT('hombre')->WHERE('id', '=', $item->id_cursos)->FIRST();
            $m = tbl_curso::SELECT('mujer')->WHERE('id', '=', $item->id_cursos)->FIRST();
            $hm = $h->hombre+$m->mujer;
            if ($hm < 10)
            {
                $recursos[$i] = "Estatal";
            }
            else
            {
                $recursos[$i] = "Federal";
            }
            $i++;
        }


        $date = strtotime($data2->fecha);
        $D = date('d', $date);
        $M = $this->monthToString(date('m',$date));
        $Y = date("Y",$date);

        $datev = strtotime($data2->fecha_validacion);
        $Dv = date('d', $datev);
        $Mv = $this->monthToString(date('m',$datev));
        $Yv = date("Y",$datev);

        $directorio = supre_directorio::WHERE('id_supre', '=', $id)->FIRST();
        $getremitente = directorio::WHERE('id', '=', $directorio->supre_rem)->FIRST();
        $getfirmante = directorio::WHERE('id', '=', $directorio->val_firmante)->FIRST();
        $getccp1 = directorio::WHERE('id', '=', $directorio->val_ccp1)->FIRST();
        $getccp2 = directorio::WHERE('id', '=', $directorio->val_ccp2)->FIRST();
        $getccp3 = directorio::WHERE('id', '=', $directorio->val_ccp3)->FIRST();
        $getccp4 = directorio::WHERE('id', '=', $directorio->val_ccp4)->FIRST();

        $pdf = PDF::loadView('layouts.pdfpages.valsupre', compact('data','data2','D','M','Y','Dv','Mv','Yv','getremitente','getfirmante','getccp1','getccp2','getccp3','getccp4','recursos'));
        $pdf->setPaper('A4', 'Landscape');
        return $pdf->stream('medium.pdf');

        return view('layouts.pdfpages.valsupre', compact('data','data2','D','M','Y','Dv','Mv','Yv','getremitente','getfirmante','getccp1','getccp2','getccp3','getccp4'));
    }

    protected function monthToString($month)
    {
        switch ($month)
        {
            case 1:
                return 'ENERO';
            break;

            case 2:
                return 'FEBRERO';
            break;

            case 3:
                return 'MARZO';
            break;

            case 4:
                return 'ABRIL';
            break;

            case 5:
                return 'MAYO';
            break;

            case 6:
                return 'JUNIO';
            break;

            case 7:
                return 'JULIO';
            break;

            case 8:
                return 'AGOSTO';
            break;

            case 9:
                return 'SEPTIEMBRE';
            break;

            case 10:
                return 'OCTUBRE';
            break;

            case 11:
                return 'NOVIEMBRE';
            break;

            case 12:
                return 'DICIEMBRE';
            break;
        }
    }

    protected function pdf_upload($pdf, $id, $nom)
    {
        # nuevo nombre del archivo
        $pdfFile = trim($nom."_".date('YmdHis')."_".$id.".pdf");
        dd($pdfFile);
        $pdf->storeAs('/uploadFiles/supre/'.$id, $pdfFile); // guardamos el archivo en la carpeta storage
        $pdfUrl = Storage::url('/uploadFiles/supre/'.$id."/".$pdfFile); // obtenemos la url donde se encuentra el archivo almacenado en el servidor.
        return $pdfUrl;
    }
}
/*if ($request->hasFile('file_upload')) {

                // obtenemos el valor de acta_nacimiento
                $ficha_cerss = DB::table('alumnos_pre')->WHERE('id', $idPreInscripcion)->VALUE('ficha_cerss');
                // checamos que no sea nulo
                if (!is_null($ficha_cerss)) {
                    # si no está nulo
                    if(!empty($ficha_cerss)){
                        $docFichaCerss = explode("/",$ficha_cerss, 5);
                        if (Storage::exists($docFichaCerss[4])) {
                            # checamos si hay un documento de ser así procedemos a eliminarlo
                            Storage::delete($docFichaCerss[4]);
                        }
                    }
                }

                $ficha_cerss = $request->file('file_upload'); # obtenemos el archivo
                $url_ficha_cerss = $this->uploaded_file($ficha_cerss, $idPreInscripcion, 'ficha_cerss'); #invocamos el método
                $chk_ficha_cerss = true;
                // creamos un arreglo
                $arregloDocs = [
                    'ficha_cerss' => $url_ficha_cerss,
                    'chk_ficha_cerss' => $chk_ficha_cerss
                ];

                // vamos a actualizar el registro con el arreglo que trae diferentes variables y carga de archivos
                DB::table('alumnos_pre')->WHERE('id', $idPreInscripcion)->update($arregloDocs);

                // limpiamos el arreglo
                unset($arregloDocs);
            } */
