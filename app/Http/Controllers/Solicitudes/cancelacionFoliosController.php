<?php

namespace App\Http\Controllers\Solicitudes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;

class cancelacionfoliosController extends Controller
{   
    function __construct() {
        session_start();
        $this->motivo = ['NO ACREDITADO'=>'DESERTADO','ERROR MECANOGRAFICO'=>'ERROR MECANOGRAFICO','ROBO O EXTRAVIO'=>'ROBO O EXTRAVIO','DETERIORO'=>'DETERIORO'];
    }
    
    public function index(Request $request){
        $id_user = Auth::user()->id;
        $rol = DB::table('role_user')->LEFTJOIN('roles', 'roles.id', '=', 'role_user.role_id')            
            ->WHERE('role_user.user_id', '=', $id_user)->WHERE('roles.slug', 'like', '%unidad%')
            ->value('roles.slug');        
        $_SESSION['unidades'] = $unidades = $message = $data = NULL;
        if(session('message')) $message = session('message');
        if($rol){ 
            $unidad = Auth::user()->unidad;
            $unidad = DB::table('tbl_unidades')->where('id',$unidad)->value('unidad');
            $unidades = DB::table('tbl_unidades')->where('ubicacion',$unidad)->pluck('unidad'.'id');
            if(count($unidades)==0) $unidades =[$unidad];       
            $_SESSION['unidades'] = $unidades;              
        }
        
        if(!$unidades ){
            $unidades = DB::table('tbl_unidades')->orderby('unidad','ASC')->pluck('unidad','id');
            $_SESSION['unidades'] = $unidades;  
        }

        if($request->clave){
            $data = DB::table('tbl_folios as f')
                ->select('f.id as id_folio','f.matricula','f.nombre as alumnos','f.folio','f.movimiento','f.motivo','c.*')
                ->LEFTJOIN('tbl_cursos as c', 'c.id', '=', 'f.id_curso')
                ->where('c.clave',$request->clave);
                if($request->matricula) $data = $data->where('f.matricula',$request->matricula);
                if($request->num_acta) $data = $data->whereOR('f.num_acta',$request->num_acta);
                $data =$data->orderby('f.id','DESC')->get();
        } //else $message = "Clave del curso requerido para la cancelación";

        $motivo = $this->motivo;
        return view('solicitudes.cancelacionfolios.index', compact('message','data', 'unidades', 'motivo'));
    }  
    
   
    public function store(Request $request){
        
        /*$unidades = json_decode(json_encode($_SESSION['unidades']), true);
        $unidades = array_flip($unidades);
        $unidad = array_search($request->id_unidad, $unidades);
        $num_inicio = substr($request->finicial,1, strlen($request->finicial))*1;
        $num_fin = substr($request->ffinal,1, strlen($request->ffinal))*1;
        */
        
        $folios = $request->folios; 
        $result = DB::table('tbl_folios')->wherein('id',$folios)->update(
            ['movimiento' => 'CANCELADO', 'motivo' => $request->$motivo,'iduser_updated' => Auth::user()->id ]
        );
        if($result) $message = "Operación exitosa!! el registro ha sido guardado correctamente.";
        else $message = "Operación inválida, no existe folio que cancelar.";
        return redirect('/solicitudes/cancelacionfolios')->with(['message'=>$message]);
    }
}