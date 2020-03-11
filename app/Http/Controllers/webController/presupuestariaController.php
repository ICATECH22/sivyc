<?php

namespace App\Http\Controllers\webController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\folio;
use App\Models\supre;
// reference the Dompdf namespace
use PDF;
class presupuestariaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('layouts.pdfpages.procesodepago');
        //return view('layouts.pdfpages.presupuestaria');
        //return view('layouts.pdfpages.contratohonorarios');
        //return view('layouts.pdfpages.solicitudsuficiencia');
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
    public function export_pdf($id) {
        $supre = new supre();
        $folio = new folio();
        $data_supre = $supre::WHERE('id', '=', $id)->FIRST();
        $data_folio = $folio::WHERE('id_supre', '=', $id)->GET();
        $date = strtotime($data_supre->fecha);
        $D = date('d', $date);
        $M = date('m',$date);
        $Y = date("Y",$date);
        $pdf = PDF::loadView('layouts.pdfpages.presupuestaria',compact('data_supre','data_folio','D','M','Y'));
        //$pdf = PDF::loadView('layouts.pdfpages.solicitudsuficiencia',compact());
        //$pdf = PDF::loadView('layouts.pdfpages.contratohonorarios');
        //$doomPdf->loadHtml('hello world');

        // (Optional) configuramos el tamaño y orientación de la hoja
        return $pdf->stream('medium.pdf');
    }
}
