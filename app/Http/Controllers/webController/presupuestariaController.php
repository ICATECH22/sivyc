<?php

namespace App\Http\Controllers\webController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        return view('layouts.pdfpages.presupuestaria');
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
    public function export_pdf() {
        $pdf = PDF::loadView('layouts.pdfpages.presupuestaria');
        //$pdf = PDF::loadView('layouts.pdfpages.solicitudsuficiencia');
        //$pdf = PDF::loadView('layouts.pdfpages.contratohonorarios');
        //$doomPdf->loadHtml('hello world');

        // (Optional) configuramos el tamaño y orientación de la hoja
        return $pdf->download('medium.pdf');
    }
}
