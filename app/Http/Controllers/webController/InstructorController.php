<?php

namespace App\Http\Controllers\webController;

use App\Models\instructor;
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

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $instructor = new instructor();
        $data = $instructor::where('id', '!=', '0')->latest()->get();
       // $data = $this->paginate($new_all);
        return view('layouts.pages.initinstructor', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function crear_instructor()
    {
        return view('layouts.pages.frminstructor');
    }

    public function guardar_instructor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cv' => 'required|mimes:pdf|max:2048'
        ]);
        if ($validator->fails()) {
            # code...
            $error =  $validator->errors()->first();
            dd($error);
        } else {
            $saveInstructor = new instructor();
            $file = $request->file('cv'); # obtenemos el archivo
            $urlcv = $this->pdf_upload($file);
            $nco = '300E    '; #No. Control prueba
            $nombre_completo = $request->nombre. ' ' . $request->apellido_paterno. ' ' . $request->apellido_materno;

            # Proceso de Guardado
            #----- Personal -----
            $saveInstructor->nombre = trim($nombre_completo);
            $saveInstructor->curp = trim($request->curp);
            $saveInstructor->rfc = trim($request->rfc);
            $saveInstructor->folio_ine = trim($request->folio_ine);
            $saveInstructor->sexo = trim($request->sexo);
            $saveInstructor->estado_civil = trim($request->estado_civil);
            $saveInstructor->fecha_nacimiento = trim($request->fecha_nacimiento);
            $saveInstructor->entidad = trim($request->entidad);
            $saveInstructor->municipio = trim($request->municipio);
            $saveInstructor->asentamiento = trim($request->asentamiento);
            $saveInstructor->domicilio = trim($request->domicilio);
            $saveInstructor->telefono = trim($request->telefono);
            $saveInstructor->correo = trim($request->correo);
            $saveInstructor->banco = trim($request->banco);
            $saveInstructor->interbancaria = trim($request->clabe);
            $saveInstructor->no_cuenta = trim($request->numero_cuenta);

            #----- Academico -----
            $saveInstructor->experiencia_laboral = trim($request->exp_laboral);
            $saveInstructor->experiencia_docente = trim($request->exp_docente);
            $saveInstructor->cursos_recibidos = trim($request->cursos_recibidos);
            $saveInstructor->cursos_conocer = trim($request->cursos_conocer);
            $saveInstructor->cursos_impartidos = trim($request->cursos_impartidos);
            $saveInstructor->capacitados_icatech = trim($request->cap_icatech);
            $saveInstructor->curso_recibido_icatech =trim($request->cursos_recicatech);
            $saveInstructor->archivo_cv = trim($urlcv);

            #----- Institucional -----
            $saveInstructor->numero_control = $nco;
            $saveInstructor->tipo_honorario = trim($request->tipo_honorario);
            $saveInstructor->registro_agente_capacitador_externo = trim($request->registro_agente);
            $saveInstructor->unidad_capacitacion_solicita_validacion_instructor = trim($request->uncap_validacion);
            $saveInstructor->memoramdum_validacion = trim($request->memo_validacion);
            $saveInstructor->modificacion_memo = trim($request->memo_mod);
            $saveInstructor->fecha_validacion = trim($request->fecha_validacion);
            $saveInstructor->observaciones = trim($request->observacion);
            $saveInstructor->save();

            $paso = 'paso!';
            $path = $request;
            dd($paso);
    }
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function ver_instructor($id)
    {
        $instructor = new instructor();
        $instructor_perfil = new InstructorPerfil();
        // consulta para mostrar los datos de determinado
        $getinstructor = $instructor->findOrFail($id);
        $perfil = $instructor_perfil->WHERE('numero_control', '=', $id)->GET();

        return view('layouts.pages.verinstructor', compact('perfil','getinstructor'));
    }
    public function add_perfil($id)
    {
        $idInstructor = $id;
        return view('layouts.pages.frmperfilprof', compact('idInstructor'));
    }

    public function perfilinstructor_save(Request $request)
    {
        $perfilInstructor = new InstructorPerfil();
        #proceso de guardado
        $perfilInstructor->area_carrera = trim($request->area_carrera); //
        $perfilInstructor->especialidad = trim($request->especialidad); //
        $perfilInstructor->clave_especialidad = trim($request->clave_especialidad); //
        $perfilInstructor->nivel_estudios_cubre_especialidad = trim($request->grado_estudio); //
        $perfilInstructor->perfil_profesional = trim($request->perfil_profesional); //
        $perfilInstructor->carrera = trim($request->nombre_carrera); //
        $perfilInstructor->estatus = trim($request->estatus); //
        $perfilInstructor->pais_institucion = trim($request->institucion_pais); //
        $perfilInstructor->entidad_institucion = trim($request->institucion_entidad); //
        $perfilInstructor->fecha_expedicion_documento = trim($request->fecha_documento); //
        $perfilInstructor->folio_documento = trim($request->folio_documento); //
        $perfilInstructor->numero_control = trim($request->idInstructor); //
        $perfilInstructor->save(); // guardar registro

        return redirect()->route('instructor-ver', ['id' => $request->idInstructor])
                        ->with('success','Perfil profesional agregado');

    }

    public function add_cursoimpartir()
    {
        return view('layouts.pages.frmcursoimpartir');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\instructor  $instructor
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //
    }

    protected function pdf_upload($pdf)
    {
                    $tamanio = $pdf->getClientSize(); #obtener el tamaño del archivo del cliente
                    $extensionPdf = $pdf->getClientOriginalExtension(); // extension de la imagen
                    $pdfFile = trim( Str::slug($pdf->getClientOriginalName(), '-')) . "." . $extensionPdf; // nombre de la imagen al momento de subirla
                    $pdf->storeAs('/uploadFiles/', $pdfFile); // guardamos el archivo en la carpeta storage
                    $pdfUrl = Storage::url('/uploadFiles/'.$pdfFile); // obtenemos la url donde se encuentra el archivo almacenado en el servidor.
                    return $pdfUrl;
    }

    public function paginate($items, $perPage = 5, $page = null)
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
        'path' => Paginator::resolveCurrentPath()
    ]);
}
}

