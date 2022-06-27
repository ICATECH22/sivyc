<?php

namespace App\Http\Controllers\webController;

use App\Http\Controllers\Controller;
use App\Models\curso;
use App\Models\PaqueteriasDidacticas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PaqueteriaDidacticaController extends Controller
{
    //
    public function index($idCurso)
    {
        $curso = curso::toBase()->where('id', $idCurso)->first();
        $paqueterias = PaqueteriasDidacticas::toBase()->where([['id_curso', $idCurso], ['estatus', 1]])->first();
        // dump($curso );
        return view('layouts.pages.paqueteriasDidacticas.paqueterias_didacticas', compact('idCurso', 'curso', 'paqueterias'));
    }

    public function store(Request $request, $idCurso)
    {
        //
        // dd($request->toArray());
        
        $paqueteriasDidacticas = new PaqueteriasDidacticas();
        $preguntas = ['instrucciones'=>$request->instrucciones];

        $cartaDescriptiva = [
            'nombrecurso' => $request->nombrecurso,
            'entidadfederativa' => $request->entidadfederativa,
            'cicloescolar' => $request->cicloescolar,
            'programaestrategico' => $request->programaestrategico,
            'modalidad' => $request->modalidad,
            'tipo' => $request->tipo,
            'perfilidoneo' => $request->perfilidoneo,
            'duracion' => $request->duracion,
            'formacionlaboral' => $request->formacionlaboral,
            'especialidad' => $request->especialidad,
            'publico' => $request->publico,
            'aprendizajeesperado' => $request->aprendizajeesperado,
            'criterio' => $request->criterio,
            'ponderacion' => $request->ponderacion,
            'objetivoespecifico' => $request->objetivoespecifico,
            'transversabilidad' => $request->transversabilidad,
            'contenidoTematico' => $request->contenidoT,
            'observaciones' => $request->observaciones,
            'recursosDidacticos' => $request->recursosD,
        ];
        
        // dd($cartaDescriptiva);
        // dump (array_count_values($request->toArray()));
        $i = 0;
        $contPreguntas = 0;
        
        $auxContPreguntas = $request->numPreguntas;
        
        while(true) {//ciclo para encontrar las preguntas del formulario
            $i++;
            if($contPreguntas == $auxContPreguntas )
                break;
            
            $numPregunta = 'pregunta'.$i;
            $tipoPregunta = 'pregunta'.$i.'-tipo';
            $opcPregunta = 'pregunta'.$i.'-opc';
            $respuesta = 'pregunta'.$i.'-opc-answer';
            
            $contenidoT = 'pregunta'.$i.'-contenidoT';

            if($request->$numPregunta != null){
                if($request->$tipoPregunta =='multiple'){
                    $tempPregunta = [
                        'descripcion' => $request->$numPregunta,
                        'tipo' => $request->$tipoPregunta,
                        'opciones' => $request->$opcPregunta,
                        'respuesta' => $request->$respuesta,
                        'contenidoTematico' => $request->$contenidoT,
                    ];
                }else{
                    $respuesta = 'pregunta'.$i.'-resp-abierta';
                    $tempPregunta = [
                        'descripcion' => $request->$numPregunta,
                        'tipo' => $request->$tipoPregunta,
                        'opciones' => $request->$opcPregunta,
                        'respuesta' => $request->$respuesta,
                        'contenidoTematico' => $request->$contenidoT,
                    ];
                    
                }
                array_push($preguntas, $tempPregunta);
                $contPreguntas++;
            }
        }
        

        DB::beginTransaction();
        try {
            PaqueteriasDidacticas::create([
                'id_curso' => $idCurso,
                'carta_descriptiva' => json_encode($cartaDescriptiva),
                'eval_alumno' => json_encode($preguntas),
                'estatus' => 1,
                'created_at' => Carbon::now(),
                'id_user_created' => Auth::id(),
            ]);
            DB::commit();
            return redirect()->route('curso-inicio')->with('success', 'SE HA GUARDADO LA PAQUETERIA DIDACTICA!');
        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->route('curso-inicio')->with('error', 'HUBO UN ERROR AL GUARDAR LA PAQUETERIA DIDACTICA!');
        }
        
    }

    function uploadImg(Request $request){
        if($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName.'_'.time().'.'.$extension;
        
            $request->file('upload')->move(public_path('images'), $fileName);
   
            $CKEditorFuncNum = $request->input('CKEditorFuncNum');
            $url = asset('images/'.$fileName); 
            $msg = 'Image uploaded successfully'; 
            $response = "<script>window.parent.CKEDITOR.tools.callFunction($CKEditorFuncNum, '$url', '$msg')</script>";
               
            @header('Content-type: text/html; charset=utf-8'); 
            echo $response;
        }
    }

    public function buscadorEspecialidades(Request $request)
    {
        $especialidades = DB::table('especialidades')
            ->where('nombre', 'like', '%' . $request->especialidad .'%')
            ->get();
        return response()->json($especialidades);
    }


    public function DescargarPaqueteria($idCurso){
        $paqueteriasDidacticas = PaqueteriasDidacticas::toBase()->where([['id_curso', $idCurso], ['estatus', 1] ])->first();
        
        // 
        $cartaDescriptiva = json_decode($paqueteriasDidacticas->carta_descriptiva);
        $cartaDescriptiva->ponderacion = json_decode($cartaDescriptiva->ponderacion);
        $cartaDescriptiva->contenidoTematico = json_decode($cartaDescriptiva->contenidoTematico);
        $cartaDescriptiva->recursosDidacticos = json_decode($cartaDescriptiva->recursosDidacticos);
        
      
        $curso = curso::toBase()->where('id', $idCurso)->first();
      
        $pdf = \PDF::loadView('layouts.pages.paqueteriasDidacticas.pdf.cartaDescriptiva', compact('cartaDescriptiva'));
        $pdf->setPaper('A4','landscape');
        return $pdf->stream('paqueteriaDidactica.pdf');    
    }
    public function DescargarPaqueteriaEvalAlumno($idCurso){
        $paqueteriasDidacticas = PaqueteriasDidacticas::toBase()->where([['id_curso', $idCurso], ['estatus', 1] ])->first();
        $cartaDescriptiva = json_decode($paqueteriasDidacticas->carta_descriptiva);
        $evalAlumno = json_decode($paqueteriasDidacticas->eval_alumno);
        $curso = curso::toBase()->where('id', $idCurso)->first();
        $abecedario = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'Ñ', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
        $pdf = \PDF::loadView('layouts.pages.paqueteriasDidacticas.pdf.eval_alumno_pdf', compact('evalAlumno', 'abecedario', 'curso','cartaDescriptiva'));
        
        return $pdf->stream('EvaluacionAlumno.pdf');    
    }

    public function DescargarPaqueteriaEvalInstructor(){
        $pdf = \PDF::loadView('layouts.pages.paqueteriasDidacticas.pdf.evaInstructorCurso_pdf');
        return $pdf->stream('evaluacionInstructor');
    }
}
