<?php

namespace App\Http\Controllers\webController;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Alumno;
use App\Models\Alumnopre;
use App\Models\Municipio;
use App\Models\Estado;
use App\Models\especialidad;
use App\Models\curso;
use App\Models\tbl_unidades;
use App\Models\cursoAvailable;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use PDF;
use Carbon\Carbon;
use App\Models\Unidad;
use Illuminate\Support\Facades\DB;
use SebastianBergmann\Environment\Console;
use App\Models\AlumnosSice;
use App\Models\cerss_a;
use PharIo\Manifest\Author;

class AlumnoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $buscar_aspirante = $request->get('busqueda_aspirantepor'); //dd(gettype($buscar_aspirante));

        $tipoaspirante = $request->get('busqueda_aspirante');   //dd(ctype_alpha($buscar_aspirante));
        $tipo=null;
        if(isset($buscar_aspirante)){
            if(ctype_alpha($buscar_aspirante)){
                $tipo='nombre_aspirante';
            }else{
                $cay= str_split($buscar_aspirante);
                $cay= $cay[0].$cay[1].$cay[2].$cay[3];    //dd($cay);
                if(ctype_alpha($cay)){
                    $tipo='curp_aspirante';
                }else{
                    $tipo='matricula_aspirante';
                }
            }
        }
        $retrieveAlumnos = Alumnopre::busquedapor($tipo, $buscar_aspirante)->orderBy('apellido_paterno')
        ->PAGINATE(25, ['id', 'nombre', 'apellido_paterno', 'apellido_materno', 'curp', 'es_cereso','matricula']);
        $contador = $retrieveAlumnos->count();//dd($retrieveAlumnos);
        return view('layouts.pages.vstaalumnos', compact('retrieveAlumnos', 'contador'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showl(Request $request)     //vista inscripción aspirante
    {
        $curp = strtoupper($request->input('curp_val'));    //dd($curp);
        if($curp) {
            
            $curp_d=str_split($curp);
            $sexo=$curp_d[10];
            $hoy=date('y');
            $anio=0;
            if($curp_d[4].$curp_d[5]<=$hoy){
                $i=20;
                $anio= $i.$curp_d[4].$curp_d[5]; 
            }
            else{
                $i=19;
                $anio= $i.$curp_d[4].$curp_d[5];    
            }
            $año=date_create_from_format('Y', $anio)->format("Y"); //dd($año);
            $mes=date_create_from_format('m', $curp_d[6].$curp_d[7])->format('m');
            $dia=date_create_from_format('d', $curp_d[8].$curp_d[9])->format('d');
            $fecha=  $año.$mes.$dia;
            
            $fecha_t=date("Y-m-d ", strtotime($fecha)); //dd($fecha_t);
            $grado_estudio = [
                'PRIMARIA INCONCLUSA' => 'PRIMARIA INCONCLUSA',
                'PRIMARIA TERMINADA' => 'PRIMARIA TERMINADA',
                'SECUNDARIA INCONCLUSA' => 'SECUNDARIA INCONCLUSA',
                'SECUNDARIA TERMINADA' => 'SECUNDARIA TERMINADA',
                'NIVEL MEDIO SUPERIOR INCONCLUSO' => 'NIVEL MEDIO SUPERIOR INCONCLUSO',
                'NIVEL MEDIO SUPERIOR TERMINADO' => 'NIVEL MEDIO SUPERIOR TERMINADO',
                'NIVEL SUPERIOR INCONCLUSO' => 'NIVEL SUPERIOR INCONCLUSO',
                'NIVEL SUPERIOR TERMINADO' => 'NIVEL SUPERIOR TERMINADO',
                'POSTGRADO' => 'POSTGRADO'
            ];
            //$estado = new Estado();
            //$estado = estado::get();
            $estado=DB::table('estados')->select('id','nombre')->get(); //dd($estado);
            foreach($estado as $item){
                $estados[$item->id]= $item->nombre;
            }
             //dd($estados);
            $estado_civil=DB::table('estado_civil')->orderby('nombre','ASC')->pluck('nombre');
            $etnia=$this->etnia= ["AKATECOS"=>"AKATECOS","CH'OLES"=>"CH'OLES","CHUJES"=>"CHUJES","JAKALTECOS"=>"JAKALTECOS","K'ICHES"=>"K'ICHES","LACANDONES"=>"LACANDONES","MAMES"=>"MAMES","MOCHOS"=>"MOCHOS","TEKOS"=>"TEKOS","TOJOLABALES"=>"TOJOLABALES","TSELTALES"=>"TSELTALES","TSOTSILES"=>"TSOTSILES","ZOQUES"=>"ZOQUES"];
            $discapacidad=$this->discapacidad = ["AUDITIVA"=>"AUDITIVA","DEL HABLA"=>"DEL HABLA","INTELECTUAL"=>"INTELECTUAL", "MOTRIZ"=>"MOTRIZ", "VISUAL"=>"VISUAL","NINGUNA"=>"NINGUNA"];
            // ELIMINAR ESPACIOS EN BLANCO EN LA CADENA
            $curp_formateada = trim($curp);//dd($curp_formateada);
            /**
            * checamos la base de datos para saber si ya se encuentra registrada
            */
            $alumnoPre = DB::table('alumnos_pre')->select('curp','id')->where('curp','=', $curp_formateada)->first(); //dd($alumnoPre);
            /**
            * se checa si la consulta arroja un resultado o es nulo,
            * en dado caso de ser nulo se tiene que agregar completamente
            */
            if(is_null($alumnoPre)){
                $a=false;
            return view('layouts.pages.valcurp', compact('estados', 'grado_estudio','estado_civil','etnia','discapacidad','curp','sexo','fecha_t','a'));
            }else {
            # ES FALSO Y SE HACE LA COMPARACIÓN DE LAS CADENAS
            /**
            * checamos la función básica para comparar dos cadenas a nivel binario
            * Tiene en cuenta mayúsculas y minúsculas.
            * Devuelve < 0 si el primer valor dado es menor que el segundo, > 0 si es al revés, y 0 si son iguales:
            */
            if (strcmp($curp_formateada, $alumnoPre->curp) === 0)
            {
            # si coinciden hay que mandar directo un mensaje de que no funciona
                
                $id= $alumnoPre->id; //dd($id);
                return redirect()->route('alumnos.presincripcion-modificar',['id'=>base64_encode($id)])
                ->withErrors(sprintf('LA CURP %s ASOCIADA AL ASPIRANTE YA SE ENCUENTRA REGISTRADA', $curp_formateada));
            }}
        }
        return view('layouts.pages.valcurp', compact('curp'));
    }
    public function showlm(Request $request)    //obtención municipios
    {
        //dd($request->ajax());
        if($request->ajax()){
            //dd($request->estado_id);
            $id= DB::table('estados')->select('id')->where(DB::RAW('TRIM(nombre)'),'=', $request->estado_id)->get(); //dd($id[0]->id);
            
            //$id=$request->estado_id;

            $municipio = municipio::where('id_estado','=',$id[0]->id)->get(); //dd($titular_cerss); 
            foreach($municipio as $titular){
                $municipioArray[$titular->id]= $titular->muni;
            }
            //dd($municipioArray);
            return response()->json($municipioArray);
        }
    }
    public function showlf(Request $request)    //obtención fecha_nac y sexo por curp
    {
        //dd($request->fa);
        $curp_d=str_split($request->fa);
        $sexo=$curp_d[10];
        if($sexo=='H'){
            $sexo='HOMBRE';
        }
        else{
            $sexo='MUJER';
        }
        $hoy=date('y');
        $anio=0;
        if($curp_d[4].$curp_d[5]<=$hoy){
                $i=20;
                $anio= $i.$curp_d[4].$curp_d[5]; 
        }
        else{
                $i=19;
                $anio= $i.$curp_d[4].$curp_d[5];    
        }
        $año=date_create_from_format('Y', $anio)->format("Y"); //dd($año);
        $mes=date_create_from_format('m', $curp_d[6].$curp_d[7])->format('m');
        $dia=date_create_from_format('d', $curp_d[8].$curp_d[9])->format('d');
        $fecha=  $año.$mes.$dia; 
        $fecha_t=date("Y-m-d ", strtotime($fecha));
        $low['fecha']=$fecha_t;
        $low['sexo']=$sexo;
            //dd($low);
        return response()->json($low);

    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */     //insercción de aspiratntes a alumnos_pre//
    public function store(Request $request)
    {
            //dd($request->all());
        $curp= $request->curp; //dd($curp);
        // ELIMINAR ESPACIOS EN BLANCO EN LA CADENA
        $curp_formateada = trim($curp); 
        /**
         * checamos la base de datos para saber si ya se encuentra registrada
         */
        $alumnoPre = DB::table('alumnos_pre')->where('curp', $curp_formateada)->select('curp')->first();
        if (isset($request->empresa)) {
            $empresa = $request->empresa;
        }else{
            $empresa = 'DESEMPLEADO';
        }
            //dd($alumnoPre);
        /**
         * se checa si la consulta arroja un resultado o es nulo,
         * en dado caso de ser nulo se tiene que agregar completamente
         */
        if (is_null($alumnoPre)) {
            # SI ES VERDADERO ESTÁ NULA LA CONSULTA, PROCEDEMOS A INSERTAR EL REGISTRO
            // obtener el usuario que agrega
            $usuario = Auth::user()->name;
            # si la consulta está vacía hacemos la inserción
            $validator = Validator::make($request->all(), [
                'curp'=>'required'
            ]);
            //dd($validator);
            if ($validator->fails()) {
                return redirect('alumnos/valsid')
                            ->withErrors($validator)
                            ->withInput();
            }else{
                #CONSULTAMOS SI HAY REGISTRO EN registro_alumnos-sice, SI HAY SE CAPTURA LA MATRICULA Y LA AGREGAMOS AL NUEVO REGISTRO
                $matricula = DB::table('registro_alumnos_sice')->where('curp','=',$curp_formateada)->where('eliminado','=',false)->select('no_control as matricula')->first() ;    //dd($matricula); 
                #CONTUINAMOS CON LA CAPTURA
                $usuarioUnidad = Auth::user()->unidad;
                $id_user = Auth::user()->id;
                $hoy=date('Y-m-d H:i:s');   //dd($hoy);
                //dd($arregloDocs);
                $arc = $request->file('customFile'); //dd($arc);
                $fotografia=$request->file('fotografia'); //dd($fotografia);
                $AlumnoPreseleccion = new Alumnopre;
                $AlumnoPreseleccion->id_unidad = $usuarioUnidad;
                if($request->cerss_chk == null){
                    $AlumnoPreseleccion->es_cereso = false;
                }else{$AlumnoPreseleccion->es_cereso = $request->cerss_chk;}
                //$AlumnoPreseleccion->es_cereso = $request->cerss_chk;
                if($request->trabajo==null){$AlumnoPreseleccion->empleado=false;}else{$AlumnoPreseleccion->empleado=$request->trabajo;}
                $AlumnoPreseleccion->numero_expediente = $request->num_expediente_cerss;
                $AlumnoPreseleccion->curp = $curp_formateada;
                $AlumnoPreseleccion->nombre = $request->nombre;
                $AlumnoPreseleccion->apellido_paterno = $request->apellidoPaterno;
                $AlumnoPreseleccion->apellido_materno = $request->apellidoMaterno;
                $AlumnoPreseleccion->fecha_nacimiento = $request->fecha_nacimiento;
                $AlumnoPreseleccion->sexo = $request->sexo;
                if(isset($fotografia)){$AlumnoPreseleccion->chk_fotografia=true;}
                $AlumnoPreseleccion->nacionalidad = $request->nacionalidad;
                $AlumnoPreseleccion->telefono_casa = $request->telefono_casa;
                $AlumnoPreseleccion->telefono_personal = $request->telefono_cel;
                $AlumnoPreseleccion->correo = $request->correo;
                $AlumnoPreseleccion->facebook = $request->facebook;
                $AlumnoPreseleccion->twitter = $request->twitter;
                $AlumnoPreseleccion->recibir_publicaciones = $request->recibir_publicaciones;
                $AlumnoPreseleccion->domicilio = $request->domicilio;
                $AlumnoPreseleccion->colonia = $request->colonia;
                $AlumnoPreseleccion->cp = $request->cp;
                $AlumnoPreseleccion->estado = $request->estado;
                $AlumnoPreseleccion->municipio = $request->municipio;
                $AlumnoPreseleccion->estado_civil = $request->estado_civil;
                $AlumnoPreseleccion->discapacidad = $request->discapacidad;
                $AlumnoPreseleccion->madre_soltera = $request->madre_soltera;
                $AlumnoPreseleccion->familia_migrante = $request->familia_migrante;
                $AlumnoPreseleccion->indigena = $request->indigena;
                $AlumnoPreseleccion->inmigrante = $request->inmigrante;
                $AlumnoPreseleccion->etnia = $request->etnia;
                $AlumnoPreseleccion->ultimo_grado_estudios = $request->ultimo_grado_estudios;
                $AlumnoPreseleccion->medio_entero = ($request->input('medio_entero') === "0") ? $request->input('medio_entero_especificar') : $request->input('medio_entero');
                $AlumnoPreseleccion->sistema_capacitacion_especificar = ($request->input('motivos_eleccion_sistema_capacitacion') === "0") ? $request->input('sistema_capacitacion_especificar') : $request->input('motivos_eleccion_sistema_capacitacion');
                $AlumnoPreseleccion->empresa_trabaja = $empresa;
                $AlumnoPreseleccion->puesto_empresa = $request->puesto_empresa;
                $AlumnoPreseleccion->antiguedad = $request->antiguedad;
                $AlumnoPreseleccion->direccion_empresa = $request->direccion_empresa;
                $AlumnoPreseleccion->chk_acta_nacimiento = $request->chk_acta;
                $AlumnoPreseleccion->chk_curp = $request->chk_curp;
                $AlumnoPreseleccion->chk_comprobante_ultimo_grado = $request->chk_escolaridad;
                $AlumnoPreseleccion->chk_comprobante_calidad_migratoria = $request->chk_comprobante_migratorio;
                $AlumnoPreseleccion->activo = true;
                $AlumnoPreseleccion->realizo = $usuario;
                $AlumnoPreseleccion->iduser_created=$id_user;
                $AlumnoPreseleccion->tiene_documentacion = true;
                $AlumnoPreseleccion->created_at= $hoy;
                if(isset($matricula)){
                    $AlumnoPreseleccion->matricula= $matricula->matricula;
                }
                //$AlumnoPreseleccion->acta_nacimiento = $request->file('customFile');
                $AlumnoPreseleccion->save();
                // generamos url para carga de archivo
                $consulta= DB::table('alumnos_pre')->where('curp','=',$curp)->select('id','nombre')->first();
                //dd($consulta);
                $idPre = $consulta->id;
                $url_documento = $this->uploaded_file($arc, $idPre, 'requisitos'); #invocamos el método
                //dd($url_documento);
                $arregloDocs = [
                    'documento'=>$url_documento,
                    'chk_curp' => $request->chk_curp,
                    'chk_acta_nacimiento' => $request->chk_acta,
                    'chk_escolaridad'=>$request->chk_escolaridad,
                    'chk_comprobante_migracion'=>$request->chk_comprobante_migracion,
                    'fecha_expedicion_acta_nacimiento'=>$request->fecha_expedicion_acta_nacimiento,
                    'fecha_expedicion_curp'=>$request->fecha_expedicion_curp,
                    'fecha_vigencia_migratorio'=>$request->fecha_vigencia_migratorio
                ];
                //$resultado = json_encode($arregloDocs, true, 2);
                //dd($resultado);
                //dd($arregloDocs);
                $affected = DB::table('alumnos_pre')->where('id', $idPre)->update(['requisitos' => $arregloDocs]);
                
                if(isset($fotografia)){
                    $url_fotografia=$this->uploaded_file($fotografia,$idPre,'fotografia');  //dd($url_fotografia);
                    $opss= DB::table('alumnos_pre')->where('id',$idPre)->update(['fotografia'=>$url_fotografia]);
                }
                // redireccionamos con un mensaje de éxito
                return redirect()->route('alumnos.valid')->with('success', sprintf('Nuevo Aspirante Agregado Exitosamente!'));
                //return redirect()->action('webController\AlumnoController@updateDoc')->compact('curp_fotmateada');
            }
           
        } else {
            # ES FALSO Y SE HACE LA COMPARACIÓN DE LAS CADENAS
            /**
             * checamos la función básica para comparar dos cadenas a nivel binario
             * Tiene en cuenta mayúsculas y minúsculas.
             * Devuelve < 0 si el primer valor dado es menor que el segundo, > 0 si es al revés, y 0 si son iguales:
             */
            if (strcmp($curp_formateada, $alumnoPre->curp) === 0)
            {
                # si coinciden hay que mandar directo un mensaje de que no funciona
                return redirect()->route('alumnos.valid')
                    ->withErrors(sprintf('LO SENTIMOS, LA CURP %s ASOCIADA AL ASPIRANTE YA SE ENCUENTRA REGISTRADA', $curp_formateada));
            }
        }
    }
        //vista modificación de aspirantes Catalogo aspirantes
    protected function showUpdate($id)
    {
        $id_user = Auth::user()->id;    //dd($id_user);
        $rol = DB::table('role_user')->LEFTJOIN('roles', 'roles.id', '=', 'role_user.role_id')
        ->WHERE('role_user.user_id', '=', $id_user)
            ->value('roles.slug');  //dd($rol);
        //$rol='unidad_vinculacion';
        $grado_estudio = [
            'PRIMARIA INCONCLUSA' => 'PRIMARIA INCONCLUSA',
            'PRIMARIA TERMINADA' => 'PRIMARIA TERMINADA',
            'SECUNDARIA INCONCLUSA' => 'SECUNDARIA INCONCLUSA',
            'SECUNDARIA TERMINADA' => 'SECUNDARIA TERMINADA',
            'NIVEL MEDIO SUPERIOR INCONCLUSO' => 'NIVEL MEDIO SUPERIOR INCONCLUSO',
            'NIVEL MEDIO SUPERIOR TERMINADO' => 'NIVEL MEDIO SUPERIOR TERMINADO',
            'NIVEL SUPERIOR INCONCLUSO' => 'NIVEL SUPERIOR INCONCLUSO',
            'NIVEL SUPERIOR TERMINADO' => 'NIVEL SUPERIOR TERMINADO',
            'POSTGRADO' => 'POSTGRADO',
            'NO ESPECIFICADO' => 'NO ESPECIFICADO'
        ];
        $estado = estado::get();
        foreach($estado as $item){
            $estados[$item->id]= $item->nombre;
        }
        $estado_civil=DB::table('estado_civil')->orderBy('nombre','ASC')->pluck('nombre');
        $etnia=$this->etnia= ["AKATECOS"=>"AKATECOS","CH'OLES"=>"CH'OLES","CHUJES"=>"CHUJES","JAKALTECOS"=>"JAKALTECOS","K'ICHES"=>"K'ICHES","LACANDONES"=>"LACANDONES","MAMES"=>"MAMES","MOCHOS"=>"MOCHOS","TEKOS"=>"TEKOS","TOJOLABALES"=>"TOJOLABALES","TSELTALES"=>"TSELTALES","TSOTSILES"=>"TSOTSILES","ZOQUES"=>"ZOQUES"];
        $discapacidad=$this->discapacidad = ["AUDITIVA"=>"AUDITIVA","DEL HABLA"=>"DEL HABLA","INTELECTUAL"=>"INTELECTUAL", "MOTRIZ"=>"MOTRIZ", "VISUAL"=>"VISUAL","NINGUNA"=>"NINGUNA"];
        $idpre = base64_decode($id);
        $alumnos = new Alumnopre();
        $alumno = $alumnos->findOrfail($idpre);     //dd($alumno->curp);
        $requisitos= json_decode($alumno->requisitos); //dd($requisitos);
        //obtención de vigencia de los rquisitos ingresados en la BD para su actualización
        $vigencia_curp='';
        $vigencia_acta= '';
        $vigencia_migracion='';
        if (isset($requisitos)) {
            $vigencia_doc_curp = $requisitos->fecha_expedicion_curp;
            $vigencia_doc= date('Y-m-d',strtotime($vigencia_doc_curp));
            $vigencia_doc= date_create($vigencia_doc);
            $hoy = date('Y-m-d');  // dd($hoy);
            $hoy= date_create($hoy);
            $vigencia_curp= date_diff($vigencia_doc, $hoy);
            $vigencia_curp= $vigencia_curp->days;
            $vigencia_acta= date('Y-m-d',strtotime($requisitos->fecha_expedicion_acta_nacimiento));
            $vigencia_acta= date_create($vigencia_acta);
            $vigencia_acta= date_diff($vigencia_acta, $hoy);
            $vigencia_acta= $vigencia_acta->days;
            $vigencia_migracion= strtotime($requisitos->fecha_vigencia_migratorio);
            $hoys= strtotime(date("Y-m-d",time()));; //dd($vigencia_migracion);
            if ($vigencia_migracion<$hoys) {
                $vigencia_migracion= true;
            }else{$vigencia_migracion=false;}
            $vigencia_migracion= $vigencia_migracion; //dd($vigencia_migracion);
        }/*else{
            $vigencia_doc=explode('/',$alumno->acta_nacimiento);    
            $vigencia_doc = $vigencia_doc[7];
            $vigencia_doc = explode('_',$vigencia_doc);
            $vigencia_doc= str_split($vigencia_doc[2],8);
            $vigencia_doc= $vigencia_doc[0];
        }*/
        //dd($vigencia_doc);
        $fecha_nac = explode("-", $alumno->fecha_nacimiento);   //dd($alumno);
        $anio_nac = $fecha_nac[0];
        $mes_nac = $fecha_nac[1];
        $dia_nac = $fecha_nac[2];
        $a=true;
        $curp= $alumno->curp;
        return view('layouts.pages.valcurp', compact('alumno', 'estados', 'anio_nac', 'mes_nac', 'dia_nac', 'grado_estudio','estado_civil','etnia','discapacidad','requisitos','vigencia_curp','vigencia_acta','vigencia_migracion','rol','a','curp'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
        // modificación de aspirantes en alumnos_pre
        public function updateSid(Request $request, $idAspirante) {
                //dd($request->all());
            //dd($request->file('customFile'));
            if (isset($idAspirante)) {
                # code...
                //$AlumnoPre = new Alumnopre();   //dd($AlumnoPre);
                $hoy=date('Y-m-d H:i:s');   //dd($hoy);
                //obtener el valor de la empresa
                if (!empty($request->empresa_mod)) {
                    # si no está vacio tenemos que cargar el dato puro
                    $empresa = trim($request->empresa_mod);
                } else {
                    # si está vacio tenemos que checar lo siguiente
                    $empresa = 'DESEMPLEADO';
                }
                if(is_null($request->cerss_chk_mod)){$chk_cerss=false;}else{$chk_cerss=$request->cerss_chk_mod;}
                if(is_null($request->trabajo_mod)){$empleado=false;}else{$empleado=$request->trabajo_mod;}       
                    //dd($chk_cerss);
                    $AspiranteId = base64_decode($idAspirante);
                if($request->sexo_mod=='HOMBRE'){
                    $sexo='MASCULINO';
                }
                if($request->sexo_mod=='MUJER'){
                    $sexo='FEMENINO';
                }
                    
                $array = [
                    'id_unidad'=>Auth::user()->unidad,
                    'es_cereso'=>$chk_cerss,
                    'numero_expediente'=>$request->num_expediente_cerss_mod,
                    'curp'=>$request->curp_mod,
                    'empleado'=>$empleado,
                    'nombre' => $request->nombre_mod,
                    'apellido_paterno' => trim($request->apellidoPaterno_mod),
                    'apellido_materno' => trim($request->apellidoMaterno_mod),
                    'sexo' => $sexo,
                    'fecha_nacimiento' =>$request->fecha_nacimiento_mod,
                    'nacionalidad'=>trim($request->nacionalidad_mod),
                    'telefono_casa' => $request->telefono_casa_mod,
                    'telefono_personal'=>$request->telefono_cel_mod,
                    'correo'=>$request->correo_mod,
                    'facebook'=>$request->facebook_mod,
                    'twitter'=>$request->twitter_mod,
                    'recibir_publicaciones'=>$request->recibir_publicaciones_mod,
                    'domicilio' => trim($request->domicilio_mod),
                    'colonia'=>trim($request->colonia_mod),
                    'cp' => $request->cp_mod,
                    'estado' => $request->estados_mod,
                    'municipio' => $request->municipios_mod,
                    'estado_civil' => $request->estado_civil_mod,
                    'discapacidad' => $request->discapacidad_mod,
                    'madre_soltera'=>$request->madre_soltera_mod,
                    'familia_migrante'=>$request->familia_migrante_mod,
                    'indigena'=>$request->indigena_mod,
                    'inmigrante'=>$request->inmigrante_mod,
                    'etnia'=>$request->etnia_mod,
                    'ultimo_grado_estudios' => $request->ultimo_grado_estudios_mod,
                    'medio_entero' => ($request->input('medio_entero_mod') === "0") ? $request->input('medio_especificar_mod') : $request->input('medio_entero_mod'),
                    'sistema_capacitacion_especificar' => ($request->input('motivos_eleccion_sistema_capacitacion_mod') === "0") ? $request->input('motivo_sistema_capacitacion_especificar_mod') : $request->input('motivos_eleccion_sistema_capacitacion_mod'),
                    'empresa_trabaja' => $empresa,
                    'antiguedad' => trim($request->antiguedad_mod),
                    'puesto_empresa' => trim($request->puesto_empresa_mod),
                    'direccion_empresa' => trim($request->direccion_empresa_mod),
                    'chk_acta_nacimiento'=>$request->chk_acta_mod,
                    'chk_curp'=>$request->chk_curp_mod,
                    'chk_comprobante_ultimo_grado'=>$request->chk_escolaridad_mod,
                    'chk_comprobante_calidad_migratoria'=>$request->chk_comprobante_migratorio_mod,
                    
                    'iduser_updated'=>Auth::user()->id,
                    'tiene_documentacion'=> true,
                    'updated_at'=>$hoy
                ];

                     //dd($array);

                $AlumnoPre_mod=DB::table('alumnos_pre')->WHERE('id', '=', $AspiranteId)->UPDATE($array);
                //si se pretende cargar nuevos archivos
                if (isset($request->customFile_mod)) {
                    $arc = $request->file('customFile_mod'); //dd($arc);
                    $url_documento = $this->uploaded_file($arc, $AspiranteId, 'requisitos'); #invocamos el método
                    $arregloDocs = [
                        'documento'=>$url_documento,
                        'chk_curp' => $request->chk_curp_mod,
                        'chk_acta_nacimiento' => $request->chk_acta_mod,
                        'chk_escolaridad'=>$request->chk_escolaridad_mod,
                        'chk_comprobante_migracion'=>$request->chk_comprobante_migracion_mod,
                        'fecha_expedicion_acta_nacimiento'=>$request->fecha_expedicion_acta_nacimiento_mod,
                        'fecha_expedicion_curp'=>$request->fecha_expedicion_curp_mod,
                        'fecha_vigencia_comprobante_migratorio'=>$request->fecha_vigencia_migratorio_mod
                    ];
                    $affected = DB::table('alumnos_pre')->where('id', $AspiranteId)->update(['requisitos' => $arregloDocs]);
                }
                if(isset($request->fotografia_mod)){
                    $url=$request->fotografia_mod;
                    $url_fotografia_mod= $this->uploaded_file($url,$AspiranteId,'fotografia');
                    $opps = DB::table('alumnos_pre')->where('id', $AspiranteId)->update(['fotografia' => $url_fotografia_mod,'chk_fotografia'=>true]);
                }

                $curpAlumno = $request->curp_mod;
                return redirect()->route('alumnos.index')
                    ->with('success', sprintf('ASPIRANTE %s  MODIFICADO EXTIOSAMENTE!', $curpAlumno));
            }
    }
    public function updateSidJefeUnidad(Request $request, $idAspirante){
            //dd($request->all());
        if (isset($idAspirante)) {
            # code...
            $AlumnoPre = new Alumnopre();   //dd($idAspirante);
            $hoy=date('Y-m-d H:i:s');   //dd($hoy);
            //obtener el valor de la empresa
            if (!empty($request->empresa_vin_mod)) {
                # si no está vacio tenemos que cargar el dato puro
                $empresa = trim($request->empresa_vin_mod);
            } else {
                # si está vacio tenemos que checar lo siguiente
                $empresa = 'DESEMPLEADO';
            }
            if(is_null($request->cerss_chk_vin_mod)){$chk_cerss=false;}else{$chk_cerss=$request->cerss_chk_vin_mod;}
            if(is_null($request->trabajo_vin_mod)){$empleado=false;}else{$empleado=$request->trabajo_vin_mod;}       
                //dd($empresa);
            $array = [
                'id_unidad'=>Auth::user()->unidad,
                'es_cereso'=>$chk_cerss,
                'numero_expediente'=>$request->num_expediente_cerss_vin_mod,
                'empleado'=>$empleado,
                'nombre' => trim($request->nombre_vin_mod),
                'apellido_paterno' => trim($request->apellidoPaterno_vin_mod),
                'apellido_materno' => trim($request->apellidoMaterno_vin_mod),
                'nacionalidad'=>trim($request->nacionalidad_vin_mod),
                'telefono_casa' => $request->telefono_casa_vin_mod,
                'telefono_personal'=>$request->telefono_cel_vin_mod,
                'correo'=>$request->correo_vin_mod,
                'facebook'=>$request->facebook_vin_mod,
                'twitter'=>$request->twitter_vin_mod,
                'recibir_publicaciones'=>$request->recibir_publicaciones_vin_mod,
                'domicilio' => trim($request->domicilio_vin_mod),
                'colonia'=>trim($request->colonia_vin_mod),
                'cp' => $request->cp_vin_mod,
                'estado' => trim($request->estado_vin_mod),
                'municipio' => trim($request->municipio_vin_mod),
                'estado_civil' => trim($request->estado_civil_vin_mod),
                'discapacidad' => trim($request->discapacidad_vin_mod),
                'madre_soltera'=>$request->madre_soltera_vin_mod,
                'familia_migrante'=>$request->familia_migrante_vin_mod,
                'indigena'=>$request->indigena_vin_mod,
                'inmigrante'=>$request->inmigrante_vin_mod,
                'etnia'=>$request->etnia_vin_mod,
                'ultimo_grado_estudios' => $request->ultimo_grado_estudios_vin_mod,
                'medio_entero' => ($request->input('medio_entero_vin_mod') === "0") ? $request->input('medio_entero_especificar_vin_mod') : $request->input('medio_entero_vin_mod'),
                'sistema_capacitacion_especificar' => ($request->input('motivos_eleccion_sistema_capacitacion_vin_mod') === "0") ? $request->input('sistema_capacitacion_especificar_vin_mod') : $request->input('motivos_eleccion_sistema_capacitacion_vin_mod'),
                'empresa_trabaja' => $empresa,
                'antiguedad' => trim($request->antiguedad_vin_mod),
                'puesto_empresa' => trim($request->puesto_empresa_vin_mod),
                'direccion_empresa' => trim($request->direccion_empresa_vin_mod),
                'chk_acta_nacimiento'=>$request->chk_acta_vin_mod,
                'chk_curp'=>$request->chk_curp_vin_mod,
                'chk_comprobante_ultimo_grado'=>$request->chk_escolaridad_vin_mod,
                'chk_comprobante_calidad_migratoria'=>$request->chk_comprobante_migratorio_vin_mod,
                
                'iduser_updated'=>Auth::user()->id,
                'tiene_documentacion'=> true,
                'updated_at'=>$hoy
            ];

            $AspiranteId = base64_decode($idAspirante);     //dd($AspiranteId);

            $AlumnoPre->WHERE('id', '=', $AspiranteId)->UPDATE($array);
            //si se pretende cargar nuevos archivos
            if (isset($request->customFile_vin_mod)) {
                $arc = $request->file('customFile_vin_mod'); //dd($arc);
                $url_documento = $this->uploaded_file($arc, $AspiranteId, 'requisitos'); #invocamos el método
                $arregloDocs = [
                    'documento'=>$url_documento,
                    'chk_curp' => $request->chk_curp_vin_mod,
                    'chk_acta_nacimiento' => $request->chk_acta_vin_mod,
                    'chk_escolaridad'=>$request->chk_escolaridad_vin_mod,
                    'chk_comprobante_migracion'=>$request->chk_comprobante_migracion_vin_mod,
                    'fecha_expedicion_acta_nacimiento'=>$request->fecha_expedicion_acta_nacimiento_vin_mod,
                    'fecha_expedicion_curp'=>$request->fecha_expedicion_curp_vin_mod,
                    'fecha_vigencia_comprobante_migratorio'=>$request->fecha_vigencia_migratorio_vin_mod
                ];
                $affected = DB::table('alumnos_pre')->where('id', $AspiranteId)->update(['requisitos' => $arregloDocs]);
            }
            if(isset($request->fotografia_vin_mod)){
                $url=$request->fotografia_vin_mod;  //dd($url);
                $url_fotografia_mod= $this->uploaded_file($url,$AspiranteId,'fotografia');
                $opps = DB::table('alumnos_pre')->where('id', $AspiranteId)->update(['fotografia' => $url_fotografia_mod,'chk_fotografia'=>true]);
            }
            

            $curpAlumno = $request->curp_vin_mod;
            return redirect()->route('alumnos.index')
                ->with('success', sprintf('ASPIRANTE %s  MODIFICADO EXTIOSAMENTE!', $curpAlumno));
        }
    }

    public function create(Request $request)
    {
        //
        $grado_estudio = [
            'PRIMARIA INCONCLUSA' => 'PRIMARIA INCONCLUSA',
            'PRIMARIA TERMINADA' => 'PRIMARIA TERMINADA',
            'SECUNDARIA INCONCLUSA' => 'SECUNDARIA INCONCLUSA',
            'SECUNDARIA TERMINADA' => 'SECUNDARIA TERMINADA',
            'NIVEL MEDIO SUPERIOR INCONCLUSO' => 'NIVEL MEDIO SUPERIOR INCONCLUSO',
            'NIVEL MEDIO SUPERIOR TERMINADO' => 'NIVEL MEDIO SUPERIOR TERMINADO',
            'NIVEL SUPERIOR INCONCLUSO' => 'NIVEL SUPERIOR INCONCLUSO',
            'NIVEL SUPERIOR TERMINADO' => 'NIVEL SUPERIOR TERMINADO',
            'POSTGRADO' => 'POSTGRADO'
        ];
        //$estado = new Estado();
        $estado = estado::get();
        foreach($estado as $item){
            $estados[$item->id]= $item->nombre;
        }
        // dd($estados);
        $estado_civil=DB::table('estado_civil')->select('nombre')->orderby('nombre','ASC')->get();
        $etnia=$this->etnia= ["AKATECOS"=>"AKATECOS","CH'OLES"=>"CH'OLES","CHUJES"=>"CHUJES","JAKALTECOS"=>"JAKALTECOS","K'ICHES"=>"K'ICHES","LACANDONES"=>"LACANDONES","MAMES"=>"MAMES","MOCHOS"=>"MOCHOS","TEKOS"=>"TEKOS","TOJOLABALES"=>"TOJOLABALES","TSELTALES"=>"TSELTALES","TSOTSILES"=>"TSOTSILES","ZOQUES"=>"ZOQUES"];
        $discapacidad=$this->discapacidad = ["AUDITIVA"=>"AUDITIVA","DEL HABLA"=>"DEL HABLA","INTELECTUAL"=>"INTELECTUAL", "MOTRIZ"=>"MOTRIZ", "VISUAL"=>"VISUAL","NINGUNA"=>"NINGUNA"];
        $curp = strtoupper($request->input('curp'));//dd($curp);
        $curp_d=str_split($curp);
        $sexo=$curp_d[10];
        $año=date_create_from_format('y', $curp_d[4].$curp_d[5])->format("Y"); //dd($año);
        $mes=date_create_from_format('m', $curp_d[6].$curp_d[7])->format('m');
        $dia=date_create_from_format('d', $curp_d[8].$curp_d[9])->format('d'); 
        $fecha=  $año.$mes.$dia; 
        $fecha_t=date("Y-m-d ", strtotime($fecha)); //dd(gettype($fecha_t));
        //dd($sexo);
        // ELIMINAR ESPACIOS EN BLANCO EN LA CADENA
        $curp_formateada = trim($curp);//dd($curp_formateada);
        /**
         * checamos la base de datos para saber si ya se encuentra registrada
         */
        $alumnoPre = DB::table('alumnos_pre')->select('curp')->where('curp','=', $curp_formateada)->first(); //dd($alumnoPre);
        /**
         * se checa si la consulta arroja un resultado o es nulo,
         * en dado caso de ser nulo se tiene que agregar completamente
         */
        if(is_null($alumnoPre)){
            return view('layouts.pages.sid', compact('estados', 'grado_estudio','estado_civil','etnia','discapacidad','curp','sexo','fecha_t'));
        }else {
        # ES FALSO Y SE HACE LA COMPARACIÓN DE LAS CADENAS
        /**
         * checamos la función básica para comparar dos cadenas a nivel binario
         * Tiene en cuenta mayúsculas y minúsculas.
         * Devuelve < 0 si el primer valor dado es menor que el segundo, > 0 si es al revés, y 0 si son iguales:
         */
        if (strcmp($curp_formateada, $alumnoPre->curp) === 0)
        {
            # si coinciden hay que mandar directo un mensaje de que no funciona
            return redirect()->route('alumnos.valid')
                ->withErrors(sprintf('LO SENTIMOS, LA CURP %s ASOCIADA AL ASPIRANTE YA SE ENCUENTRA REGISTRADA', $curp_formateada));
        }}
        
    }
    public function createcerss()
    {
        // nueva modificacion
        $grado_estudio = [
            'PRIMARIA INCONCLUSA' => 'PRIMARIA INCONCLUSA',
            'PRIMARIA TERMINADA' => 'PRIMARIA TERMINADA',
            'SECUNDARIA INCONCLUSA' => 'SECUNDARIA INCONCLUSA',
            'SECUNDARIA TERMINADA' => 'SECUNDARIA TERMINADA',
            'NIVEL MEDIO SUPERIOR INCONCLUSO' => 'NIVEL MEDIO SUPERIOR INCONCLUSO',
            'NIVEL MEDIO SUPERIOR TERMINADO' => 'NIVEL MEDIO SUPERIOR TERMINADO',
            'NIVEL SUPERIOR INCONCLUSO' => 'NIVEL SUPERIOR INCONCLUSO',
            'NIVEL SUPERIOR TERMINADO' => 'NIVEL SUPERIOR TERMINADO',
            'POSTGRADO' => 'POSTGRADO'
        ];
        $estado = new Estado();
        $estados = $estado->all(['id', 'nombre']);

        return view('layouts.pages.sid_cerss', compact('estados', 'grado_estudio'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storecerss(Request $request)
    {
        $usuarioUnidad = Auth::user()->unidad;

        $rules = [
            'nombre_cerss' => ['required', 'min:4'],
            'nombre_aspirante_cerss' => ['required', 'min:2', ],
            'genero_cerss' => 'required',
            'anio_cerss' => 'max:4',
            'file_upload' => 'required|mimes:pdf|file:1',
            'numero_expediente_cerss' => 'required',
        ];

        $messages = [
            'nombre_cerss.required' => 'EL NOMBRE DEL CERSS ES REQUERIDO',
            'nombre_cerss.min' => 'LA LONGITUD DEL NOMBRE DEL CERSS NO PUEDE SER MENOR A 3 CARACTERES',
            'nombre_aspirante_cerss.required' => 'EL NOMBRE DEL ASPIRANTE ES REQUERIDO',
            'nombre_aspirante_cerss.min' => 'LA LONGITUD DEL NOMBRE NO PUEDE SER MENOR A 2 CARACTERES',
            'genero_cerss.required' => 'EL GENERO ES REQUERIDO',
            'anio_cerss.max' => 'EL AÑO NO DEBE DE TENER MAS DE 4 DIGITOS',
            'file_upload.required' => 'EL ARCHIVO DE CARGA ES REQUERIDO',
            'file_upload.mimes' => 'EL ARCHIVO NO ES UNA EXTENSION PDF',
            'file_upload.file' => '',
            'numero_expediente_cerss.required' => 'EL NÚMERO DE EXPEDIENTE ES REQUERIDO',
        ];

        $validator =  Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            # devolvemos un error
            //dd($validator);
            return redirect()->route('preinscripcion.cerss')
                    ->withErrors($validator)
                    ->withInput();
        } else {
            /**
             * checamos si el número de expediente ya se encuentra registrado en la tabla
             */
            //$numeroExpediente = trim($request->input('numero_expediente_cerss'));
            //$chkNumeroExp = DB::table('alumnos_pre')->where('numero_expediente', $numeroExpediente)->count();
            //if ($chkNumeroExp > 0) {
                # se encontro un aspirante con ese número de expediente
                //return redirect()->back()->withErrors(sprintf('LO SENTIMOS, EL NÚMERO DE EXPEDIENTE: %s YA SE ENCUENTRA REGISTRADO', $numeroExpediente));
            //} else {
                // obtener el usuario que agrega
                $usuario_agrega = Auth::user()->name;
                /**
                 * obtener el nombre del estado
                 */
                //obtener el estado
                $estado_mod_cerss = DB::table('estados')->where('id', $request->input('cerss_estado'))->get();
                /**
                 * empezamos a insertar el registro
                 */
                $dia = trim($request->input('dia_cerss'));
                $mes = trim($request->input('mes_cerss'));
                $anio = trim($request->input('anio_cerss'));

                if (empty($dia) && empty($mes) && empty($anio))
                {
                    # condición para saber si se puede armar una fecha
                    $fecha_nacimiento = NULL;
                } else {
                    $fecha_nacimiento = $anio."-".$mes."-".$dia;
                }
                 //A
                $id_alumnos_pre = DB::table('alumnos_pre')->insertGetId([
                    'id_unidad' => $usuarioUnidad,
                    'nombre' => $request->input('nombre_aspirante_cerss'),
                    'apellido_paterno' => (is_null($request->input('apellidoPaterno_aspirante_cerss')) ? '' : $request->input('apellidoPaterno_aspirante_cerss')),
                    'apellido_materno' => (is_null($request->input('apellidoMaterno_aspirante_cerss')) ? '' : $request->input('apellidoMaterno_aspirante_cerss')),
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'nacionalidad' => $request->input('nacionalidad_cerss'),
                    'sexo' => $request->input('genero_cerss'),
                    'curp' => (is_null($request->input('curp_cerss')) ? '' : $request->input('curp_cerss')),
                    'rfc_cerss' => $request->input('rfc_cerss'),
                    'ultimo_grado_estudios' => $request->input('ultimo_grado_estudios_cerss'),
                    'tiene_documentacion' => false,
                    'realizo' => $usuario_agrega,
                    'nombre_cerss' => $request->input('nombre_cerss'),
                    'numero_expediente' => $request->input('numero_expediente_cerss'),
                    'direccion_cerss' => $request->input('direcciones_cerss'),
                    'titular_cerss' => $request->input('titular_cerss'),
                    'telefono' => '',
                    'domicilio' => '',
                    'colonia' => '',
                    'estado' => (is_null($estado_mod_cerss[0]->nombre) ? '' : $estado_mod_cerss[0]->nombre),
                    'municipio' => (is_null($request->input('cerss_municipio')) ? '' : $request->input('cerss_municipio')),
                    'estado_civil' => '',
                    'discapacidad' => (is_null($request->input('discapacidad_cerss')) ? '' : $request->input('discapacidad_cerss')),
                    'ultimo_grado_estudios' => '',
                    'medio_entero' => '',
                    'puesto_empresa' => '',
                    'sistema_capacitacion_especificar' => '',
                    'empresa_trabaja' => '',
                    'antiguedad' => '',
                    'es_cereso' => true,
                ]);

                # trabajamos cargando el acta de nacimiento al servidor
                if ($request->hasFile('file_upload')) {

                        // obtenemos el valor de acta_nacimiento
                        $ficha_cerss = DB::table('alumnos_pre')->WHERE('id', $id_alumnos_pre)->VALUE('ficha_cerss');
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
                        $url_ficha_cerss = $this->uploaded_file($ficha_cerss, $id_alumnos_pre, 'ficha_cerss'); #invocamos el método
                        $chk_ficha_cerss = true;
                        // creamos un arreglo
                        $arregloDocs = [
                            'ficha_cerss' => $url_ficha_cerss,
                            'chk_ficha_cerss' => $chk_ficha_cerss
                        ];
                } else {
                    $url_ficha_cerss = '';
                    $chk_ficha_cerss = false;
                }

                // vamos a actualizar el registro con el arreglo que trae diferentes variables y carga de archivos
                DB::table('alumnos_pre')->WHERE('id', $id_alumnos_pre)->update($arregloDocs);

                // limpiamos el arreglo
                unset($arregloDocs);

                // redireccionamos con un mensaje de éxito
                return redirect()->route('alumnos.index')->with('success', 'Nuevo Aspirante Agregado Exitosamente!');
            //}
        }
    }

    public function showCerss($id)
    {
        $id_prealumno = base64_decode($id);
        $alumnoPre_show = DB::table('alumnos_pre')->WHERE('id', $id_prealumno)->FIRST([
            'nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento' , 'nacionalidad' ,
            'sexo' , 'curp', 'rfc_cerss', 'ultimo_grado_estudios', 'es_cereso', 'chk_ficha_cerss', 'ficha_cerss',
            'nombre_cerss', 'numero_expediente', 'direccion_cerss', 'titular_cerss', 'municipio', 'estado', 'discapacidad'
        ]);
        return view('layouts.pages.sid_cerss_show', compact('id_prealumno', 'alumnoPre_show'));
    }
    /**
     * modificaciones formulario updateCerss
     */
    public function updateCerss($id)
    {
        $idPrealumnoUpdate = base64_decode($id);
        $grado_estudio_update = [
            'PRIMARIA INCONCLUSA' => 'PRIMARIA INCONCLUSA',
            'PRIMARIA TERMINADA' => 'PRIMARIA TERMINADA',
            'SECUNDARIA INCONCLUSA' => 'SECUNDARIA INCONCLUSA',
            'SECUNDARIA TERMINADA' => 'SECUNDARIA TERMINADA',
            'NIVEL MEDIO SUPERIOR INCONCLUSO' => 'NIVEL MEDIO SUPERIOR INCONCLUSO',
            'NIVEL MEDIO SUPERIOR TERMINADO' => 'NIVEL MEDIO SUPERIOR TERMINADO',
            'NIVEL SUPERIOR INCONCLUSO' => 'NIVEL SUPERIOR INCONCLUSO',
            'NIVEL SUPERIOR TERMINADO' => 'NIVEL SUPERIOR TERMINADO',
            'POSTGRADO' => 'POSTGRADO',
            'NO ESPECIFICADO' => 'NO ESPECIFICADO'
        ];
        $alumnoPre_update = DB::table('alumnos_pre')->WHERE('id', $idPrealumnoUpdate)->FIRST([
            'nombre', 'apellido_paterno', 'apellido_materno', 'fecha_nacimiento' , 'nacionalidad' ,
            'sexo' , 'curp', 'rfc_cerss', 'ultimo_grado_estudios', 'es_cereso', 'chk_ficha_cerss', 'ficha_cerss',
            'nombre_cerss', 'numero_expediente', 'direccion_cerss', 'titular_cerss', 'estado', 'municipio', 'discapacidad'
        ]);

        $estados = DB::table('estados')->get();
        $municipios = DB::table('tbl_municipios')->get();

        if (is_null($alumnoPre_update->fecha_nacimiento)) {
            # es nulo como verdadero
            $anio_nac_cerss = '';
            $mes_nac_cerss = '';
            $dia_nac_cerss= '';
        } else {
            $fecha_nac = explode("-", $alumnoPre_update->fecha_nacimiento);
            $anio_nac_cerss = $fecha_nac[0];
            $mes_nac_cerss = $fecha_nac[1];
            $dia_nac_cerss = $fecha_nac[2];
        }

        return view('layouts.pages.sid_cerss_update', compact('idPrealumnoUpdate', 'alumnoPre_update', 'anio_nac_cerss', 'mes_nac_cerss', 'dia_nac_cerss', 'grado_estudio_update', 'estados', 'municipios'));
    }
    /**
     * formulario número 2
     */
    protected function steptwo($id)
    {
        $id_prealumno = base64_decode($id);
        $alumnoPre = DB::table('alumnos_pre')->WHERE('id', '=', $id_prealumno)->FIRST(['chk_acta_nacimiento', 'acta_nacimiento', 'chk_curp', 'documento_curp',
        'chk_comprobante_domicilio', 'comprobante_domicilio', 'chk_ine', 'ine', 'chk_pasaporte_licencia', 'pasaporte_licencia_manejo', 'chk_comprobante_ultimo_grado', 'comprobante_ultimo_grado',
        'chk_fotografia', 'fotografia', 'comprobante_calidad_migratoria', 'chk_comprobante_calidad_migratoria', 'nombre', 'apellido_paterno', 'apellido_materno',
        'curp']);
        return view('layouts.pages.frmfiles_alumno_inscripcion2', compact('id_prealumno', 'alumnoPre'));
    }

    protected function update_pregistro(Request $request)
    {
        //dd($request->all());
        if (isset($request->tipoDocumento)) {
            $idPre = $request->alumno_id;
            # validamos que haya un tipo de documento iniciado, es decir la variable
            switch ($request->tipoDocumento) {
                case 'acta_nacimiento':
                    # trabajamos cargando el acta de nacimiento al servidor
                    if ($request->hasFile('customFile')) {
                        # llamamos al método
                        $validator = Validator::make($request->all(), [
                            'customFile' => 'mimes:pdf|max:2048'
                        ]);
                        if ($validator->fails()) {
                            # code...
                            return redirect('alumnos/preinscripcion/paso2/'.base64_encode($idPre))
                                    ->withErrors($validator);
                        } else {
                            // obtenemos el valor de acta_nacimiento
                            $alumnoPre = Alumnopre::WHERE('id', $idPre)->FIRST();
                            // checamos que no sea nulo
                            if (!is_null($alumnoPre->acta_nacimiento)) {
                                # si no está nulo
                                if(!empty($alumnoPre->acta_nacimiento)){
                                    $docActanacimiento = explode("/",$alumnoPre->acta_nacimiento, 5);
                                    if (Storage::exists($docActanacimiento[4])) {
                                        # checamos si hay un documento de ser así procedemos a eliminarlo
                                        Storage::delete($docActanacimiento[4]);
                                    }
                                }
                            }

                            $acta_nacimiento = $request->file('customFile'); # obtenemos el archivo
                            $url_documento = $this->uploaded_file($acta_nacimiento, $idPre, 'acta_nacimiento'); #invocamos el método
                            $chk_documento = true;
                            // creamos un arreglo
                            $arregloDocs = [
                                'acta_nacimiento' => $url_documento,
                                'chk_acta_nacimiento' => $chk_documento
                            ];
                        }
                    } else {
                        $url_documento = '';
                        $chk_documento = false;
                    }
                    break;
                case 'copia_curp':
                    # modificacion de documento curp
                    if ($request->hasFile('customFile')) {
                        # llamamos al método
                        $validator = Validator::make($request->all(), [
                            'customFile' => 'mimes:pdf|max:2048'
                        ]);
                        if ($validator->fails()) {
                            # code...
                            return redirect('alumnos/preinscripcion/paso2/'.base64_encode($idPre))
                                    ->withErrors($validator);
                        } else {
                            // obtenemos el valor de documento_curp
                            $alumnoPre = Alumnopre::WHERE('id', '=', $idPre)->FIRST();
                            // checamos que no sea nulo

                            if (!is_null($alumnoPre->documento_curp)) {
                                # si no está nulo
                                if(!empty($alumnoPre->documento_curp)){
                                    $docCurp = explode("/",$alumnoPre->documento_curp, 5);
                                    if (Storage::exists($docCurp[4])) {
                                        # checamos si hay un documento de ser así procedemos a eliminarlo
                                        Storage::delete($docCurp[4]);
                                    }
                                }
                            }

                            $documento_curp = $request->file('customFile'); # obtenemos el archivo 
                            $url_documento = $this->uploaded_file($documento_curp, $idPre, 'documento_curp'); #invocamos el método
                            $chk_documento = true;
                            // creamos un arreglo
                            $arregloDocs = [
                                'documento_curp' => $url_documento,
                                'chk_curp' => $chk_documento
                            ];
                        }
                    } else {
                        $url_documento = '';
                        $chk_documento = false;
                    }
                    break;
                case 'comprobante_domicilio':
                    # comprobante de domicilio

                    if ($request->hasFile('customFile')) {
                        # llamamos al método
                        $validator = Validator::make($request->all(), [
                            'customFile' => 'mimes:pdf|max:2048'
                        ]);
                        if ($validator->fails()) {
                            # code...
                            return redirect('alumnos/preinscripcion/paso2/'.base64_encode($idPre))
                                    ->withErrors($validator);
                        } else {
                            // obtenemos el valor de documento_curp
                            $alumnoPre = Alumnopre::WHERE('id', '=', $idPre)->FIRST();
                            // checamos que no sea nulo

                            if (!is_null($alumnoPre->comprobante_domicilio)) {
                                # si no está nulo
                                if (!empty($alumnoPre->comprobante_domicilio)) {
                                    # code...
                                    $comprobanteDomicilio = explode("/",$alumnoPre->comprobante_domicilio, 5);
                                    if (Storage::exists($comprobanteDomicilio[4])) {
                                        # checamos si hay un documento de ser así procedemos a eliminarlo
                                        Storage::delete($comprobanteDomicilio[4]);
                                    }
                                }
                            }

                            $comprobante_domicilio = $request->file('customFile'); # obtenemos el archivo
                            $url_documento = $this->uploaded_file($comprobante_domicilio, $idPre, 'comprobante_domicilio'); #invocamos el método
                            $chk_documento = true;
                            // creamos un arreglo
                            $arregloDocs = [
                                'comprobante_domicilio' => $url_documento,
                                'chk_comprobante_domicilio' => $chk_documento
                            ];
                        }
                    } else {
                        $url_documento = '';
                        $chk_documento = false;
                    }

                    break;
                case 'fotografia':
                    # fotografia
                    if ($request->hasFile('customFile')) {
                        # llamamos al método
                        $validator = Validator::make($request->all(), [
                            'customFile' => 'mimes:jpeg,jpg,png|max:2048'
                        ]);
                        if ($validator->fails()) {
                            # code...
                            return redirect('alumnos/preinscripcion/paso2/'.base64_encode($idPre))
                                    ->withErrors($validator);
                        } else {
                            // obtenemos el valor de documento_curp
                            $alumnoPre = Alumnopre::WHERE('id', '=', $idPre)->FIRST();
                            // checamos que no sea nulo

                            if (!is_null($alumnoPre->fotografia)) {
                                # si no está nulo y el campo fotografia no presenta ''
                                if(!empty($alumnoPre->fotografia)){
                                    $fotografia = explode("/",$alumnoPre->fotografia, 5);
                                    if (Storage::exists($fotografia[4])) {
                                        # checamos si hay un documento de ser así procedemos a eliminarlo
                                        Storage::delete($fotografia[4]);
                                    }
                                }
                            }

                            $fotografia = $request->file('customFile'); # obtenemos el archivo
                            $url_documento = $this->uploaded_file($fotografia, $idPre, 'fotografia'); #invocamos el método
                            $chk_documento = true;
                            // creamos un arreglo
                            $arregloDocs = [
                                'fotografia' => $url_documento,
                                'chk_fotografia' => $chk_documento
                            ];
                        }
                    } else {
                        $url_documento = '';
                        $chk_documento = false;
                    }

                    break;
                case 'credencial_electoral':
                    # credencial electoral o credencial de elector
                    if ($request->hasFile('customFile')) {
                        # llamamos al método
                        $validator = Validator::make($request->all(), [
                            'customFile' => 'mimes:pdf|max:2048'
                        ]);
                        if ($validator->fails()) {
                            # code...
                            return redirect('alumnos/preinscripcion/paso2/'.base64_encode($idPre))
                                    ->withErrors($validator);
                        } else {
                            // obtenemos el valor de documento_curp
                            $alumnoPre = Alumnopre::WHERE('id', '=', $idPre)->FIRST();
                            // checamos que no sea nulo

                            if (!is_null($alumnoPre->ine)) {
                                # si no está nulo
                                if (!empty($alumnoPre->ine)) {
                                    # code...
                                    $documentoIne = explode("/",$alumnoPre->ine, 5);
                                    if (Storage::exists($documentoIne[4])) {
                                        # checamos si hay un documento de ser así procedemos a eliminarlo
                                        Storage::delete($documentoIne[4]);
                                    }
                                }

                            }

                            $ine = $request->file('customFile'); # obtenemos el archivo
                            $url_documento = $this->uploaded_file($ine, $idPre, 'ine'); #invocamos el método
                            $chk_documento = true;
                            // creamos un arreglo
                            $arregloDocs = [
                                'ine' => $url_documento,
                                'chk_ine' => $chk_documento
                            ];
                        }
                    } else {
                        $url_documento = '';
                        $chk_documento = false;
                    }

                    break;
                case 'pasaporte_licencia_manejo':

                    if ($request->hasFile('customFile')) {
                        # llamamos al método
                        $validator = Validator::make($request->all(), [
                            'customFile' => 'mimes:pdf|max:2048'
                        ]);
                        if ($validator->fails()) {
                            # code...
                            return redirect('alumnos/preinscripcion/paso2/'.base64_encode($idPre))
                                    ->withErrors($validator);
                        } else {
                            // obtenemos el valor de documento_curp
                            $alumnoPre = Alumnopre::WHERE('id', '=', $idPre)->FIRST();
                            // checamos que no sea nulo

                            if (!is_null($alumnoPre->pasaporte_licencia_manejo)) {
                                # si no está nulo
                                if (!empty($alumnoPre->pasaporte_licencia_manejo)) {
                                    # code...
                                    $documentoLicenciaManejo = explode("/",$alumnoPre->pasaporte_licencia_manejo, 5);
                                    if (Storage::exists($documentoLicenciaManejo[4])) {
                                        # checamos si hay un documento de ser así procedemos a eliminarlo
                                        Storage::delete($documentoLicenciaManejo[4]);
                                    }
                                }
                            }

                            $pasaporte_licencia_manejo = $request->file('customFile'); # obtenemos el archivo
                            $url_documento = $this->uploaded_file($pasaporte_licencia_manejo, $idPre, 'pasaporte_licencia_manejo'); #invocamos el método
                            $chk_documento = true;
                            // creamos un arreglo
                            $arregloDocs = [
                                'pasaporte_licencia_manejo' => $url_documento,
                                'chk_pasaporte_licencia' => $chk_documento
                            ];
                        }
                    } else {
                        $url_documento = '';
                        $chk_documento = false;
                    }
                    break;
                case 'ultimo_grado_estudios':

                    if ($request->hasFile('customFile')) {
                        # llamamos al método
                        $validator = Validator::make($request->all(), [
                            'customFile' => 'mimes:pdf|max:2048'
                        ]);
                        if ($validator->fails()) {
                            # code...
                            return redirect('alumnos/preinscripcion/paso2/'.base64_encode($idPre))
                                    ->withErrors($validator);
                        } else {
                            // obtenemos el valor de documento_curp
                            $alumnoPre = Alumnopre::WHERE('id', '=', $idPre)->FIRST();
                            // checamos que no sea nulo

                            if (!is_null($alumnoPre->comprobante_ultimo_grado)) {
                                # si no está nulo
                                if (!empty($alumnoPre->comprobante_ultimo_grado)) {
                                    # code...
                                    $ultimoGradoEstudios = explode("/",$alumnoPre->comprobante_ultimo_grado, 5);
                                    if (Storage::exists($ultimoGradoEstudios[4])) {
                                        # checamos si hay un documento de ser así procedemos a eliminarlo
                                        Storage::delete($ultimoGradoEstudios[4]);
                                    }
                                }
                            }

                            $comprobante_ultimo_grado = $request->file('customFile'); # obtenemos el archivo
                            $url_documento = $this->uploaded_file($comprobante_ultimo_grado, $idPre, 'comprobante_ultimo_grado'); #invocamos el método
                            $chk_documento = true;
                            // creamos un arreglo
                            $arregloDocs = [
                                'comprobante_ultimo_grado' => $url_documento,
                                'chk_comprobante_ultimo_grado' => $chk_documento
                            ];
                        }
                    } else {
                        $url_documento = '';
                        $chk_documento = false;
                    }
                    break;
                case 'comprobante_migratorio':

                    if ($request->hasFile('customFile')) {
                        # llamamos al método
                        $validator = Validator::make($request->all(), [
                            'customFile' => 'mimes:pdf|max:2048'
                        ]);
                        if ($validator->fails()) {
                            # code...
                            return redirect('alumnos/preinscripcion/paso2/'.base64_encode($idPre))
                                    ->withErrors($validator);
                        } else {
                            // obtenemos el valor de documento_curp
                            $alumnoPre = Alumnopre::WHERE('id', '=', $idPre)->FIRST();
                            // checamos que no sea nulo

                            if (!is_null($alumnoPre->comprobante_calidad_migratoria)) {
                                # si no está nulo
                                if (!empty($alumnoPre->comrprobante_calidad_migratoria )) {
                                    # code...
                                    $comprobanteCalidadMigratoria = explode("/",$alumnoPre->comprobante_calidad_migratoria, 5);
                                    if (Storage::exists($comprobanteCalidadMigratoria[4])) {
                                        # checamos si hay un documento de ser así procedemos a eliminarlo
                                        Storage::delete($comprobanteCalidadMigratoria[4]);
                                    }
                                }
                            }

                            $comprobante_calidad_migratoria = $request->file('customFile'); # obtenemos el archivo
                            $url_documento = $this->uploaded_file($comprobante_calidad_migratoria, $idPre, 'comprobante_calidad_migratoria'); #invocamos el método
                            $chk_documento = true;
                            // creamos un arreglo
                            $arregloDocs = [
                                'comprobante_calidad_migratoria' => $url_documento,
                                'chk_comprobante_calidad_migratoria' => $chk_documento
                            ];
                        }
                    } else {
                        $url_documento = '';
                        $chk_documento = false;
                    }
                    break;
                default:
                    # code...
                    break;
            }
            // vamos a actualizar el registro con el arreglo que trae diferentes variables y carga de archivos
            Alumnopre::findOrfail($idPre)->update($arregloDocs);
            $aspirante = Alumnopre::WHERE('id', '=', $idPre)->FIRST();

            $nombre_aspirante = $aspirante->apellido_paterno.' '.$aspirante->apellido_materno.' '.$aspirante->nombre;
            // limpiamos el arreglo
            unset($arregloDocs);

            return redirect('alumnos/preinscripcion/paso2/'.base64_encode($idPre))->with('success', sprintf('DOCUMENTO DEL ALUMNO  %s  CARGADO EXTIOSAMENTE!', $nombre_aspirante));

        }
    }

    public function pdf_registro()
    {
        $pdf = PDF::loadView('layouts.pdfpages.registroalumno');

        return view('layouts\pdfpages\registroalumno');
        return $pdf->stream('medium.pdf');
    }

    protected function show($id)
    {
        $idpre = base64_decode($id);
        $AlumnoMatricula = new  Alumnopre;
        $Especialidad = new especialidad;
        $unidadestbl = new tbl_unidades();
        $tblUnidades = $unidadestbl->SELECT('ubicacion')->GROUPBY('ubicacion')->GET(['ubicacion']);
        $especialidades = $Especialidad->SELECT('id', 'nombre')->orderBy('nombre', 'asc')->GET();
        $Alumno = $AlumnoMatricula->findOrfail($idpre, ['id', 'nombre', 'apellido_paterno', 'apellido_materno', 'sexo', 'curp', 'fecha_nacimiento',
        'telefono', 'cp', 'estado', 'municipio', 'estado_civil', 'discapacidad', 'domicilio', 'colonia']);

        return view('layouts.pages.sid_general', compact('Alumno', 'especialidades', 'tblUnidades'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->get('alumno_id');
        /**
         * Para recuperar una sola fila por su valor de columna de id, use el método de búsqueda
         */
        $AlumnosPre = Alumnopre::findOrfail($id);

        // checamos si el usuario ya existe
        if(!is_null($AlumnosPre)) {

            #ES VERDADERO SI NO ES NULL
            // si existe, se tiene que utilizar el mismo número de control
            // obtenemos su número de control
            // primeramente habrá que buscarlo en la tabla AlumnoSice
            $alumnos_sice = DB::table('registro_alumnos_sice')->where('curp', $AlumnosPre->curp)->select('no_control')->first();
            // comprobamos si existe algo en la busqueda de la tabla
            if($alumnos_sice !== null){
                // REGISTRO ENCONTRADO
                $no_control_sice = $alumnos_sice->no_control;

                // hacemos el guardado del alumno con el curso que desea tomar
                $usuario = Auth::user()->name;
                /**
                 * checamos si el curso ya está asignado a este usuario
                 */
                // $check_alumno_registro_cursos = DB::table('alumnos_registro')->where([
                //     ['id_curso', '=', $request->input('cursos_sid')],
                //     ['no_control', '=', $no_control_sice],
                // ])->first();
                /**
                 * CHECAMOS SI HAY ALGÚN REGISTRO DATO EN check_alumno_registro_cursos
                 * SI NO PROCEDEMOS A INSERTAR EL REGISTRO, DE NO SER ASÍ MANDAMOS UN MENSAJE AL
                 * USUARIO QUE NO SE PUEDE CARGAR EL POR QUE EL CURSO YA SE ENCUENTRA REGISTRADO
                 */
                // if(is_null($check_alumno_registro_cursos)){
                //     // VERDADERO PROCEDEMOS A CARGAR EL REGISTRO

                //      /**
                //      * funcion alumnos
                //      */
                //     $alumno = new Alumno([
                //         'no_control' => $no_control_sice,
                //         'id_especialidad' => $request->input('especialidad_sid'),
                //         'id_curso' => $request->input('cursos_sid'),
                //         'horario' => $request->input('horario'),
                //         'grupo' => $request->input('grupo'),
                //         'unidad' => $request->input('tblunidades'),
                //         'tipo_curso' => $request->input('tipo_curso'),
                //         'realizo' => $usuario,
                //         'cerrs' => $request->input('cerrs')
                //     ]);

                //     $AlumnosPre->alumnos()->save($alumno);

                //     return redirect()->route('alumnos.inscritos')
                //         ->with('success', sprintf('¡EL CURSO ASOCIADO CON EL N° DE CONTROL %s REGISTRADO EXITOSAMENTE!', $no_control_sice));
                // } else {
                //     // FALSO PROCEDEMOS A ENVIAR UN MENSAJE DE RESTRICCIÓN AL USUARIO
                //     return redirect()->route('alumnos.presincripcion-paso2', ['id' => base64_encode($id)])
                //     ->withErrors(sprintf('LO SENTIMOS, EL CURSO ASOCIADO CON EL N° DE CONTROL %s YA FUE REGISTRADO', $no_control_sice));
                // }

                    /**
                     * funcion alumnos
                     */
                    $alumno = new Alumno([
                        'no_control' => $no_control_sice,
                        'id_especialidad' => $request->input('especialidad_sid'),
                        'id_curso' => $request->input('cursos_sid'),
                        'horario' => $request->input('horario'),
                        'grupo' => $request->input('grupo'),
                        'unidad' => $request->input('tblunidades'),
                        'tipo_curso' => $request->input('tipo_curso'),
                        'realizo' => $usuario
                    ]);

                    $AlumnosPre->alumnos()->save($alumno);

                    return redirect()->route('alumnos.inscritos')
                        ->with('success', sprintf('¡EL CURSO ASOCIADO CON EL N° DE CONTROL %s REGISTRADO EXITOSAMENTE!', $no_control_sice));


            } else {

                // $chk_curso_duplicado = DB::table('alumnos_registro')->where([ ['id_curso', '=', $request->input('cursos_sid')], ['id_pre', '=', $id] ])->first();
                // // CHECAMOS ANTES DE GENERAR EL NÚMERO DE CONTROL QUE EL CURSO AL QUE DESEA ESTÁR VINCULADO NO SE ENCUENTRE OCUPADO
                // if (!is_null($chk_curso_duplicado)) {
                //     $cursos_duplicados = DB::table('cursos')->where('id', $request->input('cursos_sid'))->select('nombre_curso')->first();
                //     $curso_nombre = $cursos_duplicados->nombre_curso;
                //     //dd($curso_nombre);
                //     /**
                //      * OBTENER EL CURSO ASOCIADO AL REGISTRO
                //      */
                //     # SI HAY UN REGISTRO VAMOS A ENVIAR UN MENSAJE AL USUARIO PARA QUE SE AVISE DE LA DUPLICIDAD DEL REGISTRO EN LA BASE DE DATOS
                //     return redirect()->back()->withErrors(sprintf('LO SENTIMOS, EL CURSO: %s YA SE ENCUENTRA REGISTRADO', $curso_nombre));
                // }

                /**
                 * NO ENCONTRADO NO HAY REGISTROS ASOCIADOS A ESE NÚMERO DE CONTROL EN LA BASE DE DATOS registro_alumnos_sice
                 * SE PROCEDE A BUSCAR UNA COINCIDENCÍA EN LA TABLA alumnos_registro
                 */

                $unidadesTbl_ = $request->input('tblunidades');

                //$Alumno_ = new Alumno();
                $Alumnos_ = DB::table('alumnos_registro')->WHERE([
                    ['id_pre', '=', $id]
                ])->skip(0)->take(1)->get();
                // aquí veré que obtenemos
                if(count($Alumnos_) > 0) {
                    // si hay datos obtenemos el número de control
                    $no_control = $Alumnos_[0]->no_control;
                } else {
                    // no cuenta con registros, por lo tanto se tendrá que generar un nuevo numero de control
                    /**
                     * obtener el año correcto
                     */
                    $date = Carbon::now();
                    $anio = $date->format('Y');

                    /**
                     * obtenemos los dos ultimos digitos de la fecha
                     */
                    $anio_division = substr($anio,2,2);

                    /**
                     * obtenemos el valor de un campo de trabajo
                     */
                    //$unidades = new Unidad();
                    $cct_unidades = DB::table('tbl_unidades')->SELECT('cct')
                                    ->WHERE('unidad', '=', $unidadesTbl_)
                                    ->GET();

                    /***
                     * obtener los numeros de las unidades
                     */
                    $cla = substr($cct_unidades[0]->cct,0,2); // dos primeros

                    $cli = $cla . substr($cct_unidades[0]->cct,5,5); //ultimos 5 caracteres

                    $cv = substr($cct_unidades[0]->cct,8,2); // ultimos dos caracteres

                    $no_control = $this->setNumeroControl($cli, $anio_division, $cv);
                }


                $usuario = Auth::user()->name;

                /**
                 * funcion alumnos
                 */
                $alumno = new Alumno([
                    'no_control' => $no_control,
                    'id_especialidad' => $request->input('especialidad_sid'),
                    'id_curso' => $request->input('cursos_sid'),
                    'horario' => $request->input('horario'),
                    'grupo' => $request->input('grupo'),
                    'unidad' => $request->input('tblunidades'),
                    'tipo_curso' => $request->input('tipo_curso'),
                    'realizo' => $usuario,
                    'cerrs' => $request->input('cerrs')
                ]);

                $AlumnosPre->alumnos()->save($alumno);

                return redirect()->route('alumnos.inscritos')->with('success', sprintf('ASPIRANTE VINCULADO EXITOSAMENTE A CURSO CON N° CONTROL %s', $no_control));

            }

        }

    }

    protected function getcursos(Request $request)
    {
        if (isset($request->idEsp)){
            /*Aquí si hace falta habrá que incluir la clase municipios con include*/
            $idEspecialidad = $request->idEsp;
            $tipo_curso = $request->tipo;
            $unidad_seleccionada = '["'.$request->unidad.'"]';
            //$Curso = new curso();
            $Cursos = DB::table('cursos')->select('id','nombre_curso')->where([['tipo_curso', '=', $tipo_curso], ['id_especialidad', '=', $idEspecialidad], ['unidades_disponible', '@>', $unidad_seleccionada], ['estado', '=', true]])->orderBy('nombre_curso', 'asc')->get();

            /*Usamos un nuevo método que habremos creado en la clase municipio: getByDepartamento*/
            $json=json_encode($Cursos);
        }else{
            $json=json_encode(array('error'=>'No se recibió un valor de id de Especialidad para filtar'));
        }

        return $json;
    }

    protected function getcursosModified(Request $request){
        if (isset($request->idEsp_mod)){
            /*Aquí si hace falta habrá que incluir la clase municipios con include*/
            $idEspecialidad = $request->idEsp_mod;
            $tipo_curso = $request->tipo_mod;
            $unidad_seleccionada = '["'.$request->unidad.'"]';
            //$Curso = new curso();
            $Cursos = DB::table('cursos')->select('id','nombre_curso')->where([['tipo_curso', '=', $tipo_curso], ['id_especialidad', '=', $idEspecialidad], ['unidades_disponible', '@>', $unidad_seleccionada], ['estado', '=', true]])->orderBy('nombre_curso', 'asc')->get();

            /*Usamos un nuevo método que habremos creado en la clase municipio: getByDepartamento*/
            $json=json_encode($Cursos);
        }else{
            $json=json_encode(array('error'=>'No se recibió un valor de id de Especialidad para filtar'));
        }

        return $json;
    }

    protected function checkcursos(Request $request)
    {
        if (isset($request->unidad)){
            $idcurso = $request->idcur;
            $unidad_seleccionada = '["'.$request->unidad.'"]';
            $check = DB::table('cursos')->select('id','nombre_curso')->where('unidades_disponible', '@>', $unidad_seleccionada)->get();
            //$unidad = 'CHK_' . str_replace(' ', '_', $request->unidad) . ' AS chk';
            //$check = cursoAvailable::SELECT($unidad, 'curso_id')->WHERE('curso_id', '=', $idcurso)->FIRST();
            $json=json_encode($check);
        }else{
            $json=json_encode(array('error'=>'No se recibió un valor de id de Especialidad para filtar'));
        }

        return $json;
    }


    protected function getcursos_update(Request $request)
    {
        /**
         * QUITAR O MODIFICAR
         */
        if (isset($request->idEsp_mod)){
            /*Aquí si hace falta habrá que incluir la clase municipios con include*/
            $idEspecialidad_mod = $request->idEsp_mod;
            $tipo_curso_mod = $request->tipo_mod;
            $Curso = new curso();
            $Cursos_mod = $Curso->WHERE('id_especialidad', '=', $idEspecialidad_mod)
                            ->WHERE('tipo_curso', '=', $tipo_curso_mod)->GET();

            /*Usamos un nuevo método que habremos creado en la clase municipio: getByDepartamento*/
            $json=json_encode($Cursos_mod);
        }else{
            $json=json_encode(array('error'=>'No se recibió un valor de id de Especialidad para filtar'));
        }

        return $json;
    }

    protected function getmunicipios(Request $request)
    {
        if (isset($request->idEst)){
            /*Aquí si hace falta habrá que incluir la clase municipios con include*/
            $idEstado=$request->idEst;
            $municipio = new municipio();
            $municipios = $municipio->WHERE('id_estado', '=', $idEstado)->GET();

            /*Usamos un nuevo método que habremos creado en la clase municipio: getByDepartamento*/
            $json=json_encode($municipios);
        }else{
            $json=json_encode(array('error'=>'No se recibió un valor de id de Especialidad para filtar'));
        }

        return $json;
    }

    protected function uploaded_file($file, $id, $name)
    {
        $tamanio = $file->getSize(); #obtener el tamaño del archivo del cliente
        $extensionFile = $file->getClientOriginalExtension(); // extension de la imagen
        # nuevo nombre del archivo
        $documentFile = trim($name."_".date('YmdHis')."_".$id.".".$extensionFile);
        $file->storeAs('/uploadFiles/alumnos/'.$id, $documentFile); // guardamos el archivo en la carpeta storage
        $documentUrl = Storage::url('/uploadFiles/alumnos/'.$id."/".$documentFile); // obtenemos la url donde se encuentra el archivo almacenado en el servidor.
        return $documentUrl;
    }
    

    protected function modifyUpdateChief($id){
        $grado_estudio = [
            'PRIMARIA INCONCLUSA' => 'PRIMARIA INCONCLUSA',
            'PRIMARIA TERMINADA' => 'PRIMARIA TERMINADA',
            'SECUNDARIA INCONCLUSA' => 'SECUNDARIA INCONCLUSA',
            'SECUNDARIA TERMINADA' => 'SECUNDARIA TERMINADA',
            'NIVEL MEDIO SUPERIOR INCONCLUSO' => 'NIVEL MEDIO SUPERIOR INCONCLUSO',
            'NIVEL MEDIO SUPERIOR TERMINADO' => 'NIVEL MEDIO SUPERIOR TERMINADO',
            'NIVEL SUPERIOR INCONCLUSO' => 'NIVEL SUPERIOR INCONCLUSO',
            'NIVEL SUPERIOR TERMINADO' => 'NIVEL SUPERIOR TERMINADO',
            'POSTGRADO' => 'POSTGRADO',
            'NO ESPECIFICADO' => 'NO ESPECIFICADO'
        ];
        $idpre = base64_decode($id);
        $alumnos = new Alumnopre();
        $municipio = new Municipio();
        $estado = new Estado();
        $municipios = $municipio->all();
        $estados = $estado->all();
        $alumno = $alumnos->findOrfail($idpre);
        $fecha_nac = explode("-", $alumno->fecha_nacimiento);
        $anio_nac = $fecha_nac[0];
        $mes_nac = $fecha_nac[1];
        $dia_nac = $fecha_nac[2];
        return view('layouts.pages.sid-modificacion-jefe', compact('alumno', 'municipios', 'estados', 'anio_nac', 'mes_nac', 'dia_nac', 'grado_estudio'));
    }
    /**
     * Actualización preinscripcion cerss.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function updatedCerssNew(Request $request, $idPreinscripcion) {
        if (isset($idPreinscripcion)) {
            //dd($request);

            // obtener el usuario que agrega
            $usuario_agrega = Auth::user()->name;

            /**
             * empezamos a insertar el registro
             */
            $dia = trim($request->input('dia_cerss'));
            $mes = trim($request->input('mes_cerss'));
            $anio = trim($request->input('anio_cerss'));

            if (empty($dia) && empty($mes) && empty($anio))
            {
                # condición para saber si se puede armar una fecha
                $fecha_nacimiento = NULL;
            } else {
                $fecha_nacimiento = $anio."-".$mes."-".$dia;
            }

            //obtener el estado
            $nombre_estado_cerss_mod = DB::table('estados')->where('id', $request->input('cerss_estado_update'))->first();

        # arreglo de datos
            $array_update_cerss = [

                'nombre' => $request->input('nombre_aspirante_cerss_update'),
                'apellido_paterno' => (is_null($request->input('apellidoPaterno_aspirante_cerss_update')) ? '' : $request->input('apellidoPaterno_aspirante_cerss_update')),
                'apellido_materno' => (is_null($request->input('apellidoMaterno_aspirante_cerss_update')) ? '' : $request->input('apellidoMaterno_aspirante_cerss_update')),
                'fecha_nacimiento' => $fecha_nacimiento,
                'nacionalidad' => $request->input('nacionalidad_cerss_update'),
                'sexo' => $request->input('genero_cerss_update'),
                'curp' => (is_null($request->input('curp_cerss_update')) ? '' : $request->input('curp_cerss_update')),
                'rfc_cerss' => $request->input('rfc_cerss_update'),
                'ultimo_grado_estudios' => $request->input('ultimo_grado_estudios_cerss_update'),
                'tiene_documentacion' => false,
                'nombre_cerss' => $request->input('nombre_cerss_update'),
                'numero_expediente' => $request->input('numero_expediente_cerss_update'),
                'direccion_cerss' => $request->input('direcciones_cerss_update_'),
                'titular_cerss' => $request->input('titular_cerss_update_'),
                'telefono' => '',
                'domicilio' => '',
                'colonia' => '',
                'estado' => trim($nombre_estado_cerss_mod->nombre),
                'municipio' => trim($request->input('cerss_municipio_update')),
                'estado_civil' => '',
                'discapacidad' => trim($request->input('discapacidad_cerss_update')),
                'medio_entero' => '',
                'puesto_empresa' => '',
                'sistema_capacitacion_especificar' => '',
                'empresa_trabaja' => '',
                'antiguedad' => '',
                'es_cereso' => $request->input('is_cerrs_update'),
            ];

            $idPreInscripcion = base64_decode($idPreinscripcion);

            DB::table('alumnos_pre')->WHERE('id', $idPreInscripcion)->UPDATE($array_update_cerss);

            # trabajamos cargando el acta de nacimiento al servidor
            if ($request->hasFile('file_upload')) {

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
            }

            $numeroExpediente = $request->input('numero_expediente_cerss');
            return redirect()->route('alumnos.index')
                ->with('success', sprintf('ASPIRANTE CON EXPEDIENTE %s  MODIFICADO EXTIOSAMENTE!', $numeroExpediente));
        }
    }
    /***
     * METODO
     */
    protected function setNumeroControl($cli_value, $anioDivision, $cv){
        // si arroja algo la consulta se procede
        $alsumnados = new Alumno();
        // pasamos la variable a entero
        $ultima_fecha_division = $alsumnados->SELECT(
            DB::raw('(SUBSTRING(no_control FROM 1 FOR 2)) control')
        )->WHERE(DB::raw('SUBSTRING(no_control FROM 8 FOR 2)'),'=',$cv)
        ->orderBy('control', 'DESC')->limit(1)->FIRST();

        if(is_null($ultima_fecha_division)) {
            // verdadero
            $fechaControl = null;
        } else {
            // falso
            $fechaControl = $ultima_fecha_division->control;
        }

        // comparamos fechas
        if ($fechaControl <> $anioDivision) {
            # nuevo código
            $control = 0;
            $contador = $control + 1;
            $str_length = 4;
            $value_control = substr("0000{$contador}", -$str_length);

            return $control_number = $anioDivision . $cli_value . $value_control;

        } else {
            $als = $alsumnados->SELECT(
                DB::raw('(SUBSTRING(no_control FROM 10 FOR 13)) control ')
            )
            ->WHERE([[DB::raw('SUBSTRING(no_control FROM 8 FOR 2)'),'=',$cv],[DB::raw('SUBSTRING(no_control FROM 1 FOR 2)'),'=', $anioDivision]])
            ->orderBy('control', 'DESC')
            ->limit(1)
            ->GET();

            $control_ = $als[0]->control;

            $count = (int)$control_ + 1;
            $str_length = 4;

            $value_control = substr("0000{$count}", -$str_length);

            return $control_number = $anioDivision . $cli_value . $value_control;
        }
    }

    /**
     * funciones
     */
    protected function getNumeroExpediente(Request $request){
        $num_exp = DB::table('alumnos_pre')->select(
            DB::raw('MAX(numero_expediente) as num_expediente')
        )->limit(1)->get();

        $json=json_encode($num_exp);
        return $json;
    }
}
