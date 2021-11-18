<?php
// Creado Por Orlando Chavez
namespace App\Http\Controllers\webController;

use App\Models\instructor;
use App\ProductoStock;
use App\Models\tbl_curso;
use App\Models\curso;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Redirect,Response;
use App\Models\InstructorPerfil;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use App\Models\tbl_unidades;
use App\Models\Alumno;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FormatoTReport;

class CursoValidadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function cv_inicio(Request $request) {
        // parametros de busqueda
        $buscarcursoValidado = trim($request->get('busqueda_curso_validado'));

        $tipoCursoValidad = $request->get('tipobusquedacursovalidado');
        //  dd($request);

        // obtener el usuario y su unidad
        $unidadUser = Auth::user()->unidad;

        $userId = Auth::user()->id;

        $roles = DB::table('role_user')
            ->LEFTJOIN('roles', 'roles.id', '=', 'role_user.role_id')
            ->SELECT('roles.slug AS role_name')
            ->WHERE('role_user.user_id', '=', $userId)
            ->GET();

        // obtener unidades
        $unidades = new tbl_unidades;
        $unidadPorUsuario = $unidades->WHERE('id', $unidadUser)->FIRST();

        switch ($roles[0]->role_name) {
            case 'dta_certificacion_control':
                # code...
                $data = tbl_curso::busquedacursovalidado($tipoCursoValidad, $buscarcursoValidado)
                    ->WHERE('tbl_cursos.clave', '!=', '0')
                    ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                    ->LEFTJOIN('instructores','instructores.id','=','tbl_cursos.id_instructor')
                    ->LEFTJOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
                    ->orderBy('tbl_cursos.id', 'desc')
                    ->PAGINATE(25, ['tbl_cursos.id','tbl_cursos.clave','cursos.nombre_curso AS nombrecur',
                    'instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','instructores.archivo_alta',
                    'tbl_cursos.inicio','tbl_cursos.termino', 'tbl_cursos.unidad','tbl_cursos.pdf_curso',
                    'tbl_cursos.tcapacitacion','tbl_cursos.munidad']);
            break;
            case 'depto_academico_cursos':
                # code...
                $data = tbl_curso::busquedacursovalidado($tipoCursoValidad, $buscarcursoValidado)
                    ->WHERE('tbl_cursos.clave', '!=', '0')
                    ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                    ->LEFTJOIN('instructores','instructores.id','=','tbl_cursos.id_instructor')
                    ->LEFTJOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
                    ->orderBy('tbl_cursos.id', 'desc')
                    ->PAGINATE(25, ['tbl_cursos.id','tbl_cursos.clave','cursos.nombre_curso AS nombrecur',
                    'instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','instructores.archivo_alta',
                    'tbl_cursos.inicio','tbl_cursos.termino', 'tbl_cursos.unidad','tbl_cursos.pdf_curso',
                    'tbl_cursos.tcapacitacion','tbl_cursos.munidad']);
            break;
            case 'depto_academico':
                # code...
                $data = tbl_curso::busquedacursovalidado($tipoCursoValidad, $buscarcursoValidado)
                    ->WHERE('tbl_cursos.clave', '!=', '0')
                    ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                    ->LEFTJOIN('instructores','instructores.id','=','tbl_cursos.id_instructor')
                    ->LEFTJOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
                    ->orderBy('tbl_cursos.id', 'desc')
                    ->PAGINATE(25, ['tbl_cursos.id','tbl_cursos.clave','cursos.nombre_curso AS nombrecur',
                    'instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','instructores.archivo_alta',
                    'tbl_cursos.inicio','tbl_cursos.termino', 'tbl_cursos.unidad','tbl_cursos.pdf_curso',
                    'tbl_cursos.tcapacitacion','tbl_cursos.munidad']);
            break;
            case 'unidad.ejecutiva':
                # code... DTA - Información e Innovación Académica - Jefatura
                $data = tbl_curso::busquedacursovalidado($tipoCursoValidad, $buscarcursoValidado)
                    ->WHERE('tbl_cursos.clave', '!=', '0')
                    ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                    ->LEFTJOIN('instructores','instructores.id','=','tbl_cursos.id_instructor')
                    ->LEFTJOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
                    ->orderBy('tbl_cursos.id', 'desc')
                    ->PAGINATE(25, ['tbl_cursos.id','tbl_cursos.clave','cursos.nombre_curso AS nombrecur',
                    'instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','instructores.archivo_alta',
                    'tbl_cursos.inicio','tbl_cursos.termino', 'tbl_cursos.unidad','tbl_cursos.pdf_curso',
                    'tbl_cursos.tcapacitacion','tbl_cursos.munidad']);
            break;
            case 'direccion.general':
                # code...
                $data = tbl_curso::busquedacursovalidado($tipoCursoValidad, $buscarcursoValidado)
                    ->WHERE('tbl_cursos.clave', '!=', '0')
                    ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                    ->LEFTJOIN('instructores','instructores.id','=','tbl_cursos.id_instructor')
                    ->LEFTJOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
                    ->orderBy('tbl_cursos.id', 'desc')
                    ->PAGINATE(25, ['tbl_cursos.id','tbl_cursos.clave','cursos.nombre_curso AS nombrecur',
                    'instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','instructores.archivo_alta',
                    'tbl_cursos.inicio','tbl_cursos.termino', 'tbl_cursos.unidad','tbl_cursos.pdf_curso',
                    'tbl_cursos.tcapacitacion','tbl_cursos.munidad']);
                break;
            case 'planeacion':
                # code...
                $data = tbl_curso::busquedacursovalidado($tipoCursoValidad, $buscarcursoValidado)
                    ->WHERE('tbl_cursos.clave', '!=', '0')
                    ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                    ->LEFTJOIN('instructores','instructores.id','=','tbl_cursos.id_instructor')
                    ->LEFTJOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
                    ->orderBy('tbl_cursos.id', 'desc')
                    ->PAGINATE(25, ['tbl_cursos.id','tbl_cursos.clave','cursos.nombre_curso AS nombrecur',
                    'instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','instructores.archivo_alta',
                    'tbl_cursos.inicio','tbl_cursos.termino', 'tbl_cursos.unidad','tbl_cursos.pdf_curso',
                    'tbl_cursos.tcapacitacion','tbl_cursos.munidad']);
                break;
            case 'financiero_verificador':
                # code...
                $data = tbl_curso::busquedacursovalidado($tipoCursoValidad, $buscarcursoValidado)
                    ->WHERE('tbl_cursos.clave', '!=', '0')
                    ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                    ->LEFTJOIN('instructores','instructores.id','=','tbl_cursos.id_instructor')
                    ->LEFTJOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
                    ->orderBy('tbl_cursos.id', 'desc')
                    ->PAGINATE(25, ['tbl_cursos.id','tbl_cursos.clave','cursos.nombre_curso AS nombrecur',
                    'instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','instructores.archivo_alta',
                    'tbl_cursos.inicio','tbl_cursos.termino', 'tbl_cursos.unidad','tbl_cursos.pdf_curso',
                    'tbl_cursos.tcapacitacion','tbl_cursos.munidad']);
                break;
            case 'financiero_pago':
                # code...
                $data = tbl_curso::busquedacursovalidado($tipoCursoValidad, $buscarcursoValidado)
                    ->WHERE('tbl_cursos.clave', '!=', '0')
                    ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                    ->LEFTJOIN('instructores','instructores.id','=','tbl_cursos.id_instructor')
                    ->LEFTJOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
                    ->orderBy('tbl_cursos.id', 'desc')
                    ->PAGINATE(25, ['tbl_cursos.id','tbl_cursos.clave','cursos.nombre_curso AS nombrecur',
                    'instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','instructores.archivo_alta',
                    'tbl_cursos.inicio','tbl_cursos.termino', 'tbl_cursos.unidad','tbl_cursos.pdf_curso',
                    'tbl_cursos.tcapacitacion','tbl_cursos.munidad']);
                break;
                case 'admin':
                    # code...
                    $data = tbl_curso::busquedacursovalidado($tipoCursoValidad, $buscarcursoValidado)
                        ->WHERE('tbl_cursos.clave', '!=', '0')
                        ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                        ->LEFTJOIN('instructores','instructores.id','=','tbl_cursos.id_instructor')
                        ->LEFTJOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
                        ->orderBy('tbl_cursos.id', 'desc')
                        ->PAGINATE(25, ['tbl_cursos.id','tbl_cursos.clave','cursos.nombre_curso AS nombrecur',
                        'instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','instructores.archivo_alta',
                        'tbl_cursos.inicio','tbl_cursos.termino', 'tbl_cursos.unidad','tbl_cursos.pdf_curso',
                        'tbl_cursos.tcapacitacion','tbl_cursos.munidad']);
                    break;
            default:
                # code...
                // obtener unidades
                $unidadUsuario = DB::table('tbl_unidades')->WHERE('id', $unidadUser)->FIRST();
                /**
                 * contratos - contratos
                 */

                $data = tbl_curso::busquedacursovalidado($tipoCursoValidad, $buscarcursoValidado)
                    ->WHERE('tbl_cursos.clave', '!=', '0')
                    ->WHERE('tbl_unidades.ubicacion', '=', $unidadPorUsuario->ubicacion)
                    ->LEFTJOIN('cursos','cursos.id','=','tbl_cursos.id_curso')
                    ->LEFTJOIN('instructores','instructores.id','=','tbl_cursos.id_instructor')
                    ->LEFTJOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
                    ->orderBy('tbl_cursos.id', 'desc')
                    ->PAGINATE(25, ['tbl_cursos.id','tbl_cursos.clave','cursos.nombre_curso AS nombrecur',
                    'instructores.nombre','instructores.apellidoPaterno','instructores.apellidoMaterno','instructores.archivo_alta',
                    'tbl_cursos.inicio','tbl_cursos.termino', 'tbl_cursos.unidad','tbl_cursos.pdf_curso',
                    'tbl_cursos.tcapacitacion','tbl_cursos.munidad']);
                break;
        }


        return view('layouts.pages.vstacvinicio', compact('data'));
    }

    public function cv_crear() {
        $curso = new curso();
        $data = $curso::where('id', '!=', '0')->latest()->get();
        return view('layouts.pages.frmcursovalidado',compact('data'));
    }

    public function solicitud_guardar(Request $request) {
        return redirect()->route('/supre/solicitud/inicio')
                         ->with('success','Solicitud de Suficiencia Presupuestal agregado');
    }

    public function fill1(Request $request) {
        $instructor = new instructor();
        $input = $request->numero_control;
        $newsAll = $instructor::where('numero_control', $input)->first();
        return response()->json($newsAll, 200);
    }

    public function cursosVinculador_reporte()
    {
        $unidades = tbl_unidades::SELECT('unidad')->WHERE('id', '!=', '0')->GET();
        return view('layouts.pages.vstareportecursovinculador', compact('unidades'));
    }

    public function consulta(Request $request)
    {
        $unidad = $request->get('unidad');
        $inicio = $request->get('inicio');
        $termino = $request->get('termino');
        $data = null;

        if($inicio != NULL && $termino != NULL && $unidad != 'DEFAULT')
        {
            $data = tbl_curso::SELECT('tbl_cursos.unidad','tbl_cursos.espe','tbl_cursos.clave','tbl_cursos.curso',
                'tbl_cursos.mod','tbl_cursos.dura','tbl_cursos.inicio','tbl_cursos.termino','tbl_cursos.hini',
                'tbl_cursos.hfin','tbl_cursos.dia','tbl_cursos.horas','tbl_cursos.hombre','tbl_cursos.mujer',
                'tbl_cursos.nombre','tbl_cursos.cp','tbl_cursos.costo','tbl_cursos.tipo_curso','tbl_cursos.tipo',
                'tbl_cursos.nota','tbl_cursos.muni','tbl_cursos.munidad','tbl_cursos.mvalida','tbl_cursos.nmunidad',
                'tbl_cursos.nmacademico','tbl_cursos.modinstructor','tbl_cursos.status','tbl_cursos.efisico',
                'tbl_cursos.depen','tbl_cursos.tcapacitacion','tbl_unidades.ubicacion')
            ->JOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
            ->WHERE('inicio', '>=', $inicio)
            ->WHERE('inicio', '<=', $termino)
            ->WHERE('tbl_unidades.ubicacion', '=', $unidad)
            ->ORDERBY('tbl_unidades.ubicacion', 'ASC')
            ->ORDERBY('tbl_cursos.unidad', 'ASC')
            ->ORDERBY('tbl_cursos.inicio', 'ASC')
            ->GET();
        }

        $unidades = DB::TABLE('tbl_unidades')->SELECT('id','ubicacion')->WHERE('cct', 'LIKE', '%07EIC%')->GET();
        // dd($unidad);

        return view('consultas.cursosiniciados', compact('data','unidad','inicio','termino','unidades'));
    }

    public function xls_cursosiniciados(Request $request)
    {
        $unidad = $request->get('unidad');
        $inicio = $request->get('inicio');
        $termino = $request->get('termino');

        $data = $data = DB::TABLE('tbl_cursos')->SELECT('tbl_unidades.ubicacion','tbl_cursos.unidad','tbl_cursos.espe',
                'tbl_cursos.clave','tbl_cursos.curso','tbl_cursos.mod','tbl_cursos.dura','tbl_cursos.inicio',
                'tbl_cursos.termino',
                DB::raw("CONCAT(tbl_cursos.hini, ' A ', tbl_cursos.hfin) AS horario"),
                'tbl_cursos.dia','tbl_cursos.horas',
                DB::raw("tbl_cursos.hombre + tbl_cursos.mujer AS cupo"),
                'tbl_cursos.nombre','tbl_cursos.cp','tbl_cursos.hombre','tbl_cursos.mujer','tbl_cursos.costo',
                'tbl_cursos.tipo_curso','tbl_cursos.tipo','tbl_cursos.nota','tbl_cursos.muni','tbl_cursos.depen',
                'tbl_cursos.munidad','tbl_cursos.mvalida','tbl_cursos.nmunidad',
                'tbl_cursos.nmacademico','tbl_cursos.efisico','tbl_cursos.modinstructor','tbl_cursos.status',
                'tbl_cursos.tcapacitacion')
            ->JOIN('tbl_unidades', 'tbl_unidades.unidad', '=', 'tbl_cursos.unidad')
            ->WHERE('inicio', '>=', $inicio)
            ->WHERE('inicio', '<=', $termino)
            ->WHERE('tbl_unidades.ubicacion', '=', $unidad)
            ->ORDERBY('tbl_unidades.ubicacion', 'ASC')
            ->ORDERBY('tbl_cursos.unidad', 'ASC')
            ->ORDERBY('tbl_cursos.inicio', 'ASC')
            ->GET();
            // DD($data);

        $cabecera = ['UNIDAD','ACCION MOVIL','ESPECIALIDAD','CLAVE','CURSO','MOD','DURA','INICIO','TERMINO','HORARIO',
            'DIAS','HORAS','CUPO','INSTRUCTOR','CP','HOMBRES','MUJERES','CUOTA','ESQUEMA','TIPO DE PAGO',
            'OBSERVACIONES','MUNICIPIO','DEPENDENCIA BENEFICIADA','MEMO DE SOLICITUD','MEMO DE AUTORIZACION',
            'MEMO DE SOLICITUD DE PROG.','MEMO DE AUTORIZACION DE PROG','ESPACIO','PAGO INSTRUCTOR','ESTATUS',
            'CAPACITACION'];

        $nombreLayout = "cursos iniciados.xlsx";
        $titulo = "cursos iniciados";
        if(count($data)>0){
            return Excel::download(new FormatoTReport($data,$cabecera, $titulo), $nombreLayout);
        }
    }

    public function vinculacion_reportepdf(Request $request)
    {
        $usuarioUnidad = Auth::user()->unidad;
        $unidadUsuario = DB::table('tbl_unidades')->WHERE('id', $usuarioUnidad)->FIRST();

        $i = 0;
        set_time_limit(0);

        if ($request->filtro == "general")
        {
            $data = tbl_curso::SELECT('tbl_cursos.curso','tbl_cursos.tcapacitacion','tbl_cursos.mod','tbl_cursos.espe',
                            'tbl_cursos.id_curso','tbl_cursos.unidad','tbl_cursos.clave','tbl_inscripcion.alumno',
                            'tbl_inscripcion.matricula','tbl_inscripcion.realizo')
                           ->whereDate('tbl_cursos.inicio', '>=', $request->fecha1)
                           ->whereDate('tbl_cursos.inicio', '<=', $request->fecha2)
                           ->WHERE('tbl_cursos.unidad', '=', $unidadUsuario->ubicacion)
                           ->LEFTJOIN('tbl_inscripcion', 'tbl_inscripcion.id_curso', '=', 'tbl_cursos.id')
                           ->GET();
        }
        else if ($request->filtro == 'curso')
        {
            $data = tbl_curso::SELECT('tbl_cursos.curso','tbl_cursos.tcapacitacion','tbl_cursos.mod','tbl_cursos.espe',
                            'tbl_cursos.id_curso','tbl_cursos.unidad','tbl_cursos.clave','tbl_inscripcion.alumno',
                            'tbl_inscripcion.matricula','tbl_inscripcion.realizo')
                           ->whereDate('tbl_cursos.inicio', '>=', $request->fecha1)
                           ->whereDate('tbl_cursos.inicio', '<=', $request->fecha2)
                           ->WHERE('tbl_cursos.unidad', '=', $unidadUsuario->ubicacion)
                           ->WHERE('tbl_cursos.id', '=', $request->id_curso)
                           ->LEFTJOIN('tbl_inscripcion', 'tbl_inscripcion.id_curso', '=', 'tbl_cursos.id')
                           ->GET();
        }
        else if ($request->filtro == 'vinculador')
        {
            $data = tbl_curso::SELECT('tbl_cursos.curso','tbl_cursos.tcapacitacion','tbl_cursos.mod','tbl_cursos.espe',
                            'tbl_cursos.id_curso','tbl_cursos.unidad','tbl_cursos.clave','tbl_inscripcion.alumno',
                            'tbl_inscripcion.matricula','tbl_inscripcion.realizo')
                           ->whereDate('tbl_cursos.inicio', '>=', $request->fecha1)
                           ->whereDate('tbl_cursos.inicio', '<=', $request->fecha2)
                           ->WHERE('tbl_cursos.unidad', '=', $unidadUsuario->ubicacion)
                           ->WHERE('tbl_inscripcion.realizo', '=', $request->vinculadoraut)
                           ->LEFTJOIN('tbl_inscripcion', 'tbl_inscripcion.id_curso', '=', 'tbl_cursos.id')
                           ->GET();
        }

        foreach($data as $cadwell)
        {
            $ins_sivyc = Alumno::SELECT('alumnos_registro.realizo','alumnos_pre.curp', 'alumnos_pre.sexo')
            ->WHERE('alumnos_registro.no_control', '=', $cadwell->matricula)
            ->WHERE('alumnos_registro.id_curso', '=', $cadwell->id_curso)
            ->LEFTJOIN('alumnos_pre', 'alumnos_pre.id', '=', 'alumnos_registro.id_pre')
            ->FIRST();

            if($ins_sivyc !=  NULL)
            {
                $realizo[$i] = $ins_sivyc->realizo;
                $curp[$i] = $ins_sivyc->curp;
                $sexo[$i] = $ins_sivyc->sexo;
            }
            else
            {
                $curp[$i] = NULL;
                $sexo[$i] = NULL;
            }

            $i++;
        }

        $pdf = PDF::loadView('layouts.pdfpages.reportevincalum', compact('data','curp','sexo','realizo'));
        $pdf->setPaper('legal', 'Landscape');
        return $pdf->Download('formato de control '. $request->fecha1 . ' - '. $request->fecha2 .'.pdf');

    }

    public function get_vin(Request $request){

        $search = $request->search;

        if (isset($search)) {
            # si la variable está inicializada
            if($search == ''){
                $vinculador = User::orderby('users.name','asc')->select('role_user.user_id','users.name')
                    ->LEFTJOIN('role_user','role_user.role_id', '=', '11')
                    ->limit(10)->get();
            }else{
                $vinculador = User::orderby('users.name','asc')->select('role_user.user_id','users.name')
                    ->where('nombre', 'like', '%' .$search . '%')
                    ->LEFTJOIN('role_user','role_user.role_id', '=', '11')
                    ->limit(10)->get();
            }

            $response = array();
            foreach($vinculador as $dir){
                $response[] = array("value"=>$dir->id,"label"=>$dir->name);
            }

            echo json_encode($response);
            exit;
        }
    }
}
