<?php

namespace App\Http\Controllers\ApiController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Folio;

class FolioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $folios = new Folio();
        $retrieveFolios = $folios->all();
        return response()->json($retrieveFolios, 200);
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
        try {
            //implementación del código
            $folio = new Folio;
            $folio->unidad = $request->unidad;
            $folio->id_curso = $request->id_curso;
            $folio->fecha_acta = $request->fecha_acta;
            $folio->matricula = $request->matricula;
            $folio->nombre = $request->nombre;
            $folio->folio = $request->folio;
            $folio->fecha_expedicion = $request->fecha_expedicion;
            $folio->movimiento = $request->movimiento;
            $folio->motivo = $request->motivo;
            $folio->mod = $request->mod;
            $folio->fini = $request->fini;
            $folio->ffin = $request->ffin;
            $folio->focan = $request->focan;
            $folio->realizo = $request->realizo;

            $folio->save();
            // redireccionamos con un mensaje de éxito
            return response()->json(['success' => 'Nuevo folio Agregado Exitosamente'], 200);
        } catch (Exception $th) {
            //throw $th;
            return response()->json(['error' => $th->getMessage()], 501);
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
    public function update(Request $request, $curso, $matricula)
    {
        //
        try {
            //code...
            $folio = new Folio();
            $folio->WHERE([
                ['matricula', '=', $matricula],
                ['id_curso', '=', $curso]
            ])->update($request->all());
            return response()->json(['success' => 'Folio actualizado exitosamente'], 200);
        } catch (Exception $e) {
            //throw $th;
            return response()->json(['error' => $e->getMessage()], 501);
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
}
