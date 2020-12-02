<?php

namespace App\Http\Controllers\reportesController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Input;
use App\Excel\xlsReportes;
use Maatwebsite\Excel\Facades\Excel;

use PDF;
class cursosController extends Controller
{   
    function __construct() {
        session_start();
        $this->discapacidad = ["AUDITIVA"=>"1","DEL HABLA"=>"2","INTELECTUAL"=>"3", "MOTRIZ"=>"4", "VISUAL"=>"5","NINGUNA"=>"6"];
        $this->escolaridad = ["PRIMARIA INCONCLUSA"=>"1","PRIMARIA TERMINADA"=>"2","SECUNDARIA INCONCLUSA"=>"3","SECUNDARIA TERMINADA"=>"4",
        "NIVEL MEDIO SUPERIOR INCONCLUSO"=>"5","NIVEL MEDIO SUPERIOR TERMINADO"=>"6","NIVEL SUPERIOR INCONCLUSO"=>"7","NIVEL SUPERIOR TERMINADO"=>"8","POSTGRADO"=>"9"];
        $this->periodo = ["7"=>"1","8"=>"1","9"=>"1","10"=>"2","11"=>"2","12"=>"2","1"=>"3","2"=>"3","3"=>"3","4"=>"4","5"=>"4","6"=>"4"];
        $this->mes = ["1"=>"ENERO","2"=>"FEBRERO","3"=>"MARZO","4"=>"ABRIL","5"=>"MAYO","6"=>"JUNIO","7"=>"JULIO","8"=>"AGOSTO","9"=>"SEPTIEMBRE","10"=>"OCTUBRE","11"=>"NOVIEMBRE","12"=>"DICIEMBRE"];         
    }
    
    public function index(Request $request){
        $id_user = Auth::user()->id;
        $rol = DB::table('role_user')->LEFTJOIN('roles', 'roles.id', '=', 'role_user.role_id')            
            ->WHERE('role_user.user_id', '=', $id_user)->WHERE('roles.slug', '=', 'unidad')
            ->value('roles.slug');        
        $_SESSION['unidades']=NULL;
        //var_dump($rol);exit;
        if($rol=='unidad'){ 
            $unidad = Auth::user()->unidad;
            $unidad = DB::table('tbl_unidades')->where('id',$unidad)->value('unidad');
            $unidades = DB::table('tbl_unidades')->where('ubicacion',$unidad)->pluck('unidad');        
            if(count($unidades)==0) $unidades =[$unidad];       
            $_SESSION['unidades'] = $unidades;              
        }
        //var_dump($_SESSION['unidades']);exit;
        return view('reportes.cursos.index');     
    }
    
    public function asistencia(Request $request){ //PRUEBA 2B-20-OPAU-CAE-0022
        $clave = $request->get('clave');
        
        $file = "LISTA_ASISTENCIA_$clave.PDF";
        if($clave){
            $curso = DB::table('tbl_cursos')->select('tbl_cursos.*',DB::raw('right(clave,4) as grupo'),
            DB::raw("to_char(inicio, 'DD/MM/YYYY') as fechaini"),DB::raw("to_char(termino, 'DD/MM/YYYY') as fechafin"),
            'u.plantel',DB::raw('EXTRACT(MONTH FROM inicio)  as mes_inicio'),DB::raw('EXTRACT(YEAR FROM inicio)  as anio_inicio') )
            ->where('clave',$clave);
            if($_SESSION['unidades'])$curso = $curso->whereIn('u.ubicacion',$_SESSION['unidades']);
            $curso = $curso->leftjoin('tbl_unidades as u','u.unidad','tbl_cursos.unidad')            
            ->first(); 
            //var_dump($curso);exit;
            if($curso){
                $consec_curso = $curso->id_curso; 
                $fecha_termino = $curso->inicio;
                $alumnos = DB::table('tbl_inscripcion as i')
                    ->select('i.matricula','i.alumno')
                    ->where('i.id_curso',$curso->id)->where('i.status','INSCRITO')                                      
                    ->groupby('i.matricula','i.alumno')->orderby('i.alumno')->get();
               //var_dump($alumnos); exit;       
                if(!$alumnos) return "NO HAY ALUMNOS INSCRITOS";
                $mes = $this->mes;
                $consec = 1;               
                
                $pdf = PDF::loadView('reportes.cursos.pdf-asistencia',compact('curso','alumnos','mes','consec'));        
                $pdf->setPaper('Letter', 'landscape');
                return $pdf->stream($file);
            } else return "Curso no v&aacute;lido para esta Unidad";
        }
        return "Clave no v&aacute;lida";
    }
    
    public function calificaciones(Request $request){
        $clave = $request->get('clave');
        
        $file = "CALIFICACIONES_$clave.PDF";
        if($clave){
            $curso = DB::table('tbl_cursos')->select('tbl_cursos.*',DB::raw('right(clave,4) as grupo'),
            DB::raw("to_char(inicio, 'DD/MM/YYYY') as fechaini"),DB::raw("to_char(termino, 'DD/MM/YYYY') as fechafin"),'u.plantel' )
            ->where('clave',$clave);
            if($_SESSION['unidades']) $curso = $curso->whereIn('u.ubicacion',$_SESSION['unidades']);
            $curso = $curso->leftjoin('tbl_unidades as u','u.unidad','tbl_cursos.unidad')            
            ->first(); 
           // var_dump($curso);exit;
            if($curso){
                $consec_curso = $curso->id_curso; 
                $fecha_termino = $curso->inicio;
                $alumnos = DB::table('tbl_inscripcion as i')
                    ->select('i.matricula','i.alumno','cal.calificacion' )
                    ->where('i.id_curso',$curso->id)->where('i.status','INSCRITO')                                      
                    ->Join('tbl_calificaciones as cal', function($join){
                        $join->on('cal.idcurso', '=', 'i.id_curso');                
                        $join->on('cal.matricula', '=', 'i.matricula');                
                    })->groupby('i.matricula','i.alumno','cal.calificacion')->orderby('i.alumno')->get();
               //var_dump($alumnos); exit;       
                if(count($alumnos)==0){ return "NO HAY ALUMNOS INSCRITOS";exit;}               
                $consec = 1;               
                
                $pdf = PDF::loadView('reportes.cursos.pdf-calificaciones',compact('curso','alumnos','consec'));        
                $pdf->setPaper('Letter', 'landscape');
                return $pdf->stream($file);
            } else return "Curso no v&aacute;lido para esta Unidad";
        }
        return "Clave no v&aacute;lida";
    } 
    
    public function riacIns(Request $request){
        $clave = $request->get('clave');
        
        $file = "RIAC_INSCRIPCION_$clave.PDF";
        if($clave){
            $curso = DB::table('tbl_cursos')->select('tbl_cursos.*',DB::raw('right(clave,4) as grupo'),
            DB::raw("to_char(inicio, 'DD/MM/YYYY') as fechaini"),DB::raw("to_char(termino, 'DD/MM/YYYY') as fechafin"),
            DB::raw("trim(substring(u.dunidad , position('.' in u.dunidad)+1,char_length(u.dunidad))) as dunidad"),'u.pdunidad',
            DB::raw("trim(substring(u.dgeneral , position('.' in u.dgeneral)+1,char_length(u.dgeneral))) as dgeneral"),'u.pdgeneral',
            DB::raw('EXTRACT(MONTH FROM termino)  as mes_termino'),'u.plantel' )
            ->where('clave',$clave);
            if($_SESSION['unidades']) $curso = $curso->whereIn('u.ubicacion',$_SESSION['unidades']);
            $curso = $curso->leftjoin('tbl_unidades as u','u.unidad','tbl_cursos.unidad')            
            ->first(); 
           // var_dump($curso);exit;
            if($curso){
                $consec_curso = $curso->id_curso; 
                $fecha_termino = $curso->inicio;
                $alumnos = DB::table('tbl_inscripcion as i')
                    ->select('i.matricula','i.alumno',DB::raw('left(a_pre.sexo,1) as sexo'),'a_pre.ultimo_grado_estudios','a_pre.discapacidad',
                        'i.abrinscri',DB::raw("to_char(i.created_at, 'YYYY-MM-DD') as fecha_creacion"),
                        DB::raw("EXTRACT(year from (age('".$fecha_termino."',a_pre.fecha_nacimiento))) as edad"),'a_pre.fecha_nacimiento' )
                    ->where('i.id_curso',$curso->id)->where('i.status','INSCRITO')                                      
                    ->Join('alumnos_registro as a_reg', function($join)use($consec_curso){
                        //$join->on('a_r.id_curso', '=', $consec_curso);                
                        $join->on('a_reg.no_control', '=', 'i.matricula');                    
                    }) 
                    ->Join('alumnos_pre as a_pre', function($join)use($consec_curso){
                        $join->on('a_pre.id', '=', 'a_reg.id_pre');
                    });                
                $alumnos = $alumnos->groupby('i.matricula','i.alumno','i.created_at',
                    'a_reg.id_pre','a_pre.fecha_nacimiento','a_pre.sexo','a_pre.ultimo_grado_estudios',
                    'a_pre.discapacidad','i.abrinscri')->orderby('i.alumno')->get();
               //var_dump($alumnos); exit;       
                if(count($alumnos)==0){ return "NO HAY ALUMNOS INSCRITOS";exit;} 
                $discapacidad = $this->discapacidad;  
                $escolaridad = $this->escolaridad;
                $periodo = $this->periodo;
                $consec = 1;               
                
                $pdf = PDF::loadView('reportes.cursos.pdf-riac-ins',compact('curso','alumnos','discapacidad','escolaridad','periodo','consec'));        
                $pdf->setPaper('Letter', 'landscape');
                return $pdf->stream($file);
            } else return "Curso no v&aacute;lido para esta Unidad";
        }
        return "Clave no v&aacute;lida";
    } 
    
    public function riacAcred(Request $request){
        
        $clave = $request->get('clave');
        $file = "RIAC_ACREDITACION_$clave.PDF";
        if($clave){
            $curso = DB::table('tbl_cursos')->select('tbl_cursos.*',DB::raw('right(clave,4) as grupo'),
            DB::raw("to_char(inicio, 'DD/MM/YYYY') as fechaini"),DB::raw("to_char(termino, 'DD/MM/YYYY') as fechafin"),
            DB::raw("trim(substring(u.dunidad , position('.' in u.dunidad)+1,char_length(u.dunidad))) as dunidad"),'u.pdunidad',
            DB::raw("trim(substring(u.dgeneral , position('.' in u.dgeneral)+1,char_length(u.dgeneral))) as dgeneral"),'u.pdgeneral',
            DB::raw('EXTRACT(MONTH FROM termino)  as mes_termino'),'u.plantel')
            ->where('clave',$clave);
            if($_SESSION['unidades']) $curso = $curso->whereIn('u.ubicacion',$_SESSION['unidades']);
            $curso = $curso->leftjoin('tbl_unidades as u','u.unidad','tbl_cursos.unidad')            
            ->first(); 
            
            if($curso){
                $consec_curso = $curso->id_curso; 
                $fecha_termino = $curso->termino;
                $alumnos = DB::table('tbl_inscripcion as i')
                    ->select('i.matricula','i.alumno','cal.acreditado',DB::raw('left(a_pre.sexo,1) as sexo'),'a_pre.ultimo_grado_estudios','a_pre.discapacidad',
                        'i.abrinscri',DB::raw("to_char(i.created_at, 'YYYY-MM-DD') as fecha_creacion"),
                        DB::raw("EXTRACT(year from (age('".$fecha_termino."',a_pre.fecha_nacimiento))) as edad"),'a_pre.fecha_nacimiento' )
                    ->where('i.id_curso',$curso->id)->where('i.status','INSCRITO')
                    ->Join('tbl_calificaciones as cal', function($join){
                        $join->on('cal.idcurso', '=', 'i.id_curso');                
                        $join->on('cal.matricula', '=', 'i.matricula');                
                    })                    
                    ->Join('alumnos_registro as a_reg', function($join)use($consec_curso){
                        //$join->on('a_r.id_curso', '=', $consec_curso);                
                        $join->on('a_reg.no_control', '=', 'i.matricula');                    
                    }) 
                    ->Join('alumnos_pre as a_pre', function($join)use($consec_curso){
                        $join->on('a_pre.id', '=', 'a_reg.id_pre');
                    });                
                $alumnos = $alumnos->groupby('i.matricula','i.alumno','i.created_at','cal.acreditado',
                    'a_reg.id_pre','a_pre.fecha_nacimiento','a_pre.sexo','a_pre.ultimo_grado_estudios',
                    'a_pre.discapacidad','i.abrinscri')->orderby('i.alumno')->get();                
               //var_dump($alumnos); exit;
                if(count($alumnos)==0){ return "NO TIENEN CALIFICACIONES ASIGNADAS";exit;}       
                $discapacidad = $this->discapacidad;  
                $escolaridad = $this->escolaridad;
                $periodo = $this->periodo;
                $consec = 1;               
                
                $pdf = PDF::loadView('reportes.cursos.pdf-riac-acred',compact('curso','alumnos','discapacidad','escolaridad','periodo','consec'));        
                $pdf->setPaper('Letter', 'landscape');
                return $pdf->stream($file);
            }else return "Curso no v&aacute;lido para esta Unidad";
        }
        return "Clave no v&aacute;lida";
    } 
    
    public function riacCert(Request $request){
        
        $clave = $request->get('clave');
        $file = "RIAC_CERTIFICACION_$clave.PDF";
        if($clave){
            $curso = DB::table('tbl_cursos')->select('tbl_cursos.*',DB::raw('right(clave,4) as grupo'),
            DB::raw("to_char(inicio, 'DD/MM/YYYY') as fechaini"),DB::raw("to_char(termino, 'DD/MM/YYYY') as fechafin"),
            DB::raw("trim(substring(u.dunidad , position('.' in u.dunidad)+1,char_length(u.dunidad))) as dunidad"),'u.pdunidad',
            DB::raw("trim(substring(u.dgeneral , position('.' in u.dgeneral)+1,char_length(u.dgeneral))) as dgeneral"),'u.pdgeneral',
            DB::raw('EXTRACT(MONTH FROM termino)  as mes_termino'),'u.plantel' )
            ->where('clave',$clave);
            if($_SESSION['unidades']) $curso = $curso->whereIn('u.ubicacion',$_SESSION['unidades']);
            $curso = $curso->leftjoin('tbl_unidades as u','u.unidad','tbl_cursos.unidad')            
            ->first(); 
            //var_dump($curso);exit;
            //echo $curso->id; exit;
            if($curso){
                $consec_curso = $curso->id_curso; 
                $fecha_termino = $curso->termino;
                $alumnos = DB::table('tbl_inscripcion as i')
                    ->select('i.matricula','i.alumno','cal.acreditado','f.folio',DB::raw("to_char(f.fecha_expedicion, 'DD/MM/YYYY') as fecha_expedicion"),
                        DB::raw('left(a_pre.sexo,1) as sexo'),'a_pre.ultimo_grado_estudios','a_pre.discapacidad','i.abrinscri',DB::raw("to_char(i.created_at, 'YYYY-MM-DD') as fecha_creacion"),
                        DB::raw("EXTRACT(year from (age('".$fecha_termino."',a_pre.fecha_nacimiento))) as edad"),'a_pre.fecha_nacimiento' )
                    ->where('i.id_curso',$curso->id)->where('i.status','INSCRITO')
                    ->Join('tbl_calificaciones as cal', function($join){
                        $join->on('cal.idcurso', '=', 'i.id_curso');                
                        $join->on('cal.matricula', '=', 'i.matricula');                
                    })
                    ->Join('tbl_folios as f', function($join){
                        $join->on('f.id_curso', '=', 'i.id_curso');                
                        $join->on('f.matricula', '=', 'i.matricula');                
                    })
                    ->Join('alumnos_registro as a_reg', function($join)use($consec_curso){
                        //$join->on('a_r.id_curso', '=', $consec_curso);                
                        $join->on('a_reg.no_control', '=', 'i.matricula');                    
                    }) 
                    ->Join('alumnos_pre as a_pre', function($join)use($consec_curso){
                        $join->on('a_pre.id', '=', 'a_reg.id_pre');
                    });                
                $alumnos = $alumnos->groupby('i.matricula','i.alumno','i.created_at','cal.acreditado','f.folio',
                    'f.fecha_expedicion','a_reg.id_pre','a_pre.fecha_nacimiento','a_pre.sexo','a_pre.ultimo_grado_estudios',
                    'a_pre.discapacidad','i.abrinscri')->orderby('i.alumno')->get();
               //var_dump($alumnos); exit;
                if(count($alumnos)==0){ return "NO TIENEN FOLIOS ASIGNADOS";exit;}
                
                $discapacidad = $this->discapacidad;  
                $escolaridad = $this->escolaridad;
                $periodo = $this->periodo;
                $consec = 1;               
                
                $pdf = PDF::loadView('reportes.cursos.pdf-riac-cert',compact('curso','alumnos','discapacidad','escolaridad','periodo','consec'));        
                $pdf->setPaper('Letter', 'landscape');
                return $pdf->stream($file);
            }else return "Curso no v&aacute;lido para esta Unidad";
        }
        return "Clave no v&aacute;lida";
    }
     
    public function xlsConst(Request $request){
          
        $clave = $request->get('clave');
        
        if($clave){
            $curso = DB::table('tbl_cursos as c')->select('c.id','c.curso','c.dura','c.cct','c.unidad',
            DB::raw("trim(substring(u.dunidad , position('.' in u.dunidad)+1,char_length(u.dunidad))) as dunidad"),'c.id_curso',
            DB::raw('EXTRACT(MONTH FROM termino)  as mes_termino'),DB::raw('EXTRACT(YEAR FROM termino)  as anio_termino') )
            ->where('c.clave',$clave);
            if($_SESSION['unidades']) $curso = $curso->whereIn('u.ubicacion',$_SESSION['unidades']);
            $curso = $curso->leftjoin('tbl_unidades as u','u.unidad','c.unidad')            
            ->first(); 
            //var_dump($curso);exit;
            //echo $curso->id; exit;
            if($curso){
                $consec_curso = $curso->id_curso; 
                
                $data = DB::table('tbl_inscripcion as i')
                    ->select('a_pre.apellido_paterno','a_pre.apellido_materno','a_pre.nombre','a_pre.curp',
                        DB::raw("'".$curso->curso."' as nombre_curso"),
                        DB::raw("to_char(f.fecha_expedicion, 'DD/MM/YYYY') as fecha"),
                        DB::raw($curso->dura.' as horas'),
                        DB::raw("'".$curso->cct."' as cct"),
                        DB::raw("'".$curso->unidad."' as unidad"),
                        DB::raw("'CHIAPAS' as estado"),
                        DB::raw("'C. ".$curso->dunidad."' as dunidad"),
                        DB::raw("'".$this->mes[$curso->mes_termino]."' as mes"),
                        DB::raw("'".$curso->anio_termino."' as anio")
                        )
                    ->where('i.id_curso',$curso->id)->where('i.status','INSCRITO')
                    ->Join('tbl_calificaciones as cal', function($join){
                        $join->on('cal.idcurso', '=', 'i.id_curso');                
                        $join->on('cal.matricula', '=', 'i.matricula');
                        $join->where('cal.acreditado', 'X');                
                    })       
                    ->Join('tbl_folios as f', function($join){
                        $join->on('f.id_curso', '=', 'i.id_curso');                
                        $join->on('f.matricula', '=', 'i.matricula');                
                    })             
                    ->Join('alumnos_registro as a_reg', function($join)use($consec_curso){
                        //$join->on('a_r.id_curso', '=', $consec_curso);                
                        $join->on('a_reg.no_control', '=', 'i.matricula');                    
                    }) 
                    ->Join('alumnos_pre as a_pre', function($join)use($consec_curso){
                        $join->on('a_pre.id', '=', 'a_reg.id_pre');
                    })
                    ->groupby('a_pre.apellido_paterno','a_pre.apellido_materno','a_pre.nombre','a_pre.curp',
                        'i.curso','f.fecha_expedicion','i.alumno')->orderby('i.alumno')->get();
                //var_dump($data); exit;
                if(count($data)==0){ return "NO TIENEN FOLIOS ASIGNADOS";exit;}
                                
                $head = ['APELLIDO PATERNO','APELLIDO MATERNO','NOMBRE(S)','CURP','CURSO','FECHA','HORAS','CLAVE UNIDAD','CIUDAD','ESTADO','DIRECTOR','MES',utf8_encode('A�O')];
                $nombreLayout = $clave.".xlsx";

                if(count($data)>0){  
                    return Excel::download(new xlsReportes($data,$head), $nombreLayout);
                }
                    
            }else return "Curso no v&aacute;lido para esta Unidad";
        }
    } 
}