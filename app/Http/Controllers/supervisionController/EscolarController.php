<?php

namespace App\Http\Controllers\supervisionController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use App\Models\Tbl_curso;

class EscolarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    function __construct() {

    }
    public function index(Request $request)
    {
        $user = Auth::user();
        $tipo = $request->get('tipo_busqueda');
        $valor = $request->get('valor_busqueda');
        $unidades = $user->unidades;
        $id_user = $user->id;
        $anio = date("Y");

        if($request->get('fecha')) $fecha = $request->get('fecha');
        else $fecha = date("d/m/Y");

        $query = DB::table('tbl_cursos')->select('tbl_cursos.id','tbl_cursos.id_curso','tbl_cursos.id_instructor',
        'tbl_cursos.nombre','tbl_cursos.clave','tbl_cursos.curso','tbl_cursos.inicio','tbl_cursos.termino','tbl_cursos.hini',
        'tbl_cursos.hfin','tbl_cursos.unidad',DB::raw('COUNT(DISTINCT(i.id)) as total'),DB::raw('COUNT(DISTINCT(a.id)) as total_alumnos'),
        'token_i.id as token_instructor','token_i.ttl as ttl_instructor','token_a.id_curso as token_alumno',
        'tbl_cursos.json_supervision');

        if($fecha)$query = $query->where('tbl_cursos.inicio','<=',$fecha)->where('tbl_cursos.termino','>=',$fecha);
        if($unidades) {
            $unidades = explode(',',$unidades);
            $query = $query->whereIn('tbl_cursos.unidad',$unidades);
        }
        if (!empty($tipo) AND !empty(trim($valor))) {
            switch ($tipo) {
                case 'nombre_instructor':
                    $query = $query->where('tbl_cursos.nombre', 'like', '%'.$valor.'%');
                    break;
                case 'clave_curso':
                    $query = $query->where('tbl_cursos.clave',$valor);
                    break;
                case 'nombre_curso':
                    $query = $query->where('tbl_cursos.curso', 'LIKE', '%'.$valor.'%');
                    break;
            }
        }
        $query = $query->where('tbl_cursos.clave', '>', '0');

        $query = $query->leftJoin('supervision_instructores as i', function($join)use($id_user){
                $join->on('i.id_tbl_cursos', '=', 'tbl_cursos.id');
                $join->where('i.id_user',$id_user);
                $join->groupBy('i.id_tbl_cursos');

            });
        $query = $query->leftJoin('supervision_alumnos as a', function($join)use($id_user){
                $join->on('a.id_tbl_cursos', '=', 'tbl_cursos.id');
                $join->where('a.id_user',$id_user);
                $join->groupBy('a.id_tbl_cursos');

            });

        $query = $query->leftJoin('supervision_tokens as token_i' ,function($join)use($id_user){
                $join->on('tbl_cursos.id', '=', 'token_i.id_curso');
                $join->on('token_i.id_instructor','=','tbl_cursos.id_instructor');
                $join->where('token_i.id_supervisor',$id_user);
                $join->where('token_i.id_instructor','>','0');
        });

        $query = $query->leftJoin('supervision_tokens as token_a' ,function($join)use($id_user){
                $join->on('tbl_cursos.id', '=', 'token_a.id_curso');
                $join->where('token_a.id_supervisor',$id_user);
                $join->where('token_a.id_alumno','>','0');
        });

        $query = $query->groupby('tbl_cursos.id','tbl_cursos.id_curso','tbl_cursos.id_instructor',
        'tbl_cursos.nombre','tbl_cursos.clave','tbl_cursos.curso','tbl_cursos.inicio','tbl_cursos.termino','tbl_cursos.hini',
        'tbl_cursos.hfin','tbl_cursos.unidad','i.id_tbl_cursos','a.id_tbl_cursos','token_i.id','token_i.ttl','token_a.id_curso');

        $data =  $query->orderBy('tbl_cursos.inicio', 'DESC')->paginate(15);
        //var_dump($data);exit;


        return view('supervision.escolar.index', compact('data','fecha'));
    }

    public function updateCurso(Request $request)
    {
        $id_supervisor = Auth::user()->id;
        $id_curso = $request->input('id_curso');
        $fecha = date("dmy");
        $anio = date("Y");
        $archivo = "#";
        if($id_curso AND $request->input('status_supervision') AND $request->input('obs_supervision') AND $request->file('file_soporte')){
            $status = $request->input('status_supervision');
            if ($request->file('file_soporte')) {
                $ext = $request->file('file_soporte')->extension();
                $file_name =  $status."-".$id_curso."-".$fecha.".".$ext;
                $path_file = '/supervisiones/'.$anio.'/cursos';
                $archivo =  'storage'.$path_file.'/'.$file_name;
            }
            $json_supervision = response()->json([
                'status' => $request->input('status_supervision'),
                'id_supervisor' => $id_supervisor,
                'fecha' => date('Y-m-d'),
                'obs' => $request->input('obs_supervision'),
                'archivo' =>  $archivo
            ]);

            $c = Tbl_curso::find($id_curso);
            $c->json_supervision = $json_supervision;
            if($c->save()) {
                if ($request->file('file_soporte'))
                        $request->file('file_soporte')->storeAs($path_file, $file_name);
                return 1;
            }
            return 0;
        }
        return 0;
    }

}
