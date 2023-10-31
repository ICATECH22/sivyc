<!--ELABORO ROMELIA PEREZ - rpnanguelu@gmail.com-->
@section('content_script_css')
    <link rel="stylesheet" href="{{asset('css/global.css') }}" />   
    <style>
        .custom-file-label::after {
            content: "Examinar";
        }
    </style>
@endsection
@extends('theme.sivyc.layout')
@section('title', 'Grupos- Recibos de Pago | SIVyC Icatech')
@section('content')       
    <div class="card-header">
        Grupos / Recibos de Pago        
    </div>
    <div class="card card-body">
        @if(count($message)>0)
            <div class="row ">
                <div @if(isset($message["ERROR"])) class="col-md-12 alert alert-danger" @else class="col-md-12 alert alert-success"  @endif>
                    <p>@if(isset($message["ERROR"])) {{ $message["ERROR"] }} @else {{ $message["ALERT"] }} @endif </p>
                </div>
            </div>
        @endif
        {{ Form::open(['method' => 'post', 'id'=>'frm',  'enctype' => 'multipart/form-data']) }}
            @csrf
            <div class="row form-inline">                                 
                {{ Form::text('folio_grupo', $request->folio_grupo, ['id'=>'folio_grupo', 'class' => 'form-control mr-2', 'placeholder' => 'FOLIO DE GRUPO', 'aria-label' => 'FOLIO DE GRUPO', 'required' => 'required', 'size' => 30]) }}
                {{ Form::button('BUSCAR', ['id' => 'buscar','name' => 'BUSCAR', 'class' => 'btn', 'value'=>route('grupos.recibos')]) }}                
            </div>        
            @if($data)  
                <div class="row form-inline"> 
                    <div class="form-group col-md-6"> <h4>DEL CURSO</h4> </div>    
                    <div class="form-group col-md-6 justify-content-end ">                        
                        <h4 class="bg-light p-2">&nbsp; RECIBO No. &nbsp;<span class="bg-white p-1">&nbsp;<b>{{$data->uc}}</b> <b class="text-danger">{{ str_pad($data->num_recibo, 4, "0", STR_PAD_LEFT) }}</b>&nbsp;</span> &nbsp;</h4>
                        @if($data->status_folio == 'DISPONIBLE') 
                            <h4 class="text-center text-white p-2" style="background-color: #33A731;">&nbsp;DISPONIBLE &nbsp;</h4>
                        @elseif($data->status_folio == 'ENVIADO') 
                            <h4 class="text-center text-white bg-danger p-2" >&nbsp;ENVIADO &nbsp;</h4>
                        @elseif($data->status_folio == 'IMPRENTA') 
                            <h4 class="text-center text-white bg-danger p-2" >&nbsp;DE IMPRENTA &nbsp;</h4>
                        @else
                            <h4 class="bg-warning text-center p-2">&nbsp;{{$data->status_folio}} &nbsp;</h4>
                        @endif
                        @if($data->file_pdf)
                            <a class="nav-link pt-0" href="{{$path_files}}{{ $data->file_pdf}}" target="_blank">
                                <i  class="far fa-file-pdf  fa-3x text-danger"  title='DESCARGAR RECIBO DE PAGO OFICIALIZADO.'></i>
                            </a>
                        @endif
                    </div>                    
                </div>                
                <div class="row bg-light" style="padding:35px; line-height: 1.5em;">
                    <div class="form-group col-md-12"><b> </b></div>
                    <div class="form-group col-md-6">    FOLIO GRUPO: <b>{{$data->folio_grupo}}</b></div>
                    <div class="form-group col-md-6">
                        CLAVE: 
                        @if($data->clave==0) <b class="text-danger">{{$data->status_clave}} &nbsp;</b> @else <b>{{$data->clave}} &nbsp;</b> @endif
                        @if($data->status_curso) ESTATUS: <b class="text-danger"> {{$data->status_curso}} </b> @endif 

                    </div>
                    <div class="form-group col-md-6">    
                        UNIDAD/ACCIÓN MÓVIL:            
                        <b>@if($data->unidad == $data->ubicacion){{ $data->unidad }} @else {{ $data->ubicacion }} / {{ $data->unidad }} @endif</b>        
                    </div>                
                    <div class="form-group col-md-6">CURSO: <b>{{ $data->curso }}</b></div>
                    <div class="form-group col-md-6">INSTRUCTOR: <b>{{ $data->nombre }}</b></div>
                    <div class="form-group col-md-6">TIPO DE PAGO: <b>{{ $data->tpago }}</b></div>
                    <div class="form-group col-md-6">FECHAS: <b>{{ $data->inicio }} AL {{ $data->termino }}</b></div> 
                    <div class="form-group col-md-6">HORARIO: <b>DE {{ $data->hini }} A {{ $data->hfin }} </b></div>                                
                    <div class="form-group col-md-6">TOTAL BENEFICIADOS: <b>{{ $data->hombre+$data->mujer }}</b></div>
                    <div class="form-group col-md-6">TOTAL CUOTA DE RECUPERACIÓN: <b>$ {{ number_format($data->costo, 2, '.', ',') }}</b></div>                    
                    @if($data->status_curso )<div class="form-group col-md-6">ESTATUS CLAVE: <b>{{ $data->status_curso }}</b></div> @endif           
                    <div class="form-group col-md-6">ESTATUS RECIBO: <b>{{ $data->status_recibo }}</b></div>
                </div>                
                @if(!in_array($data->status_folio, ['IMPRENTA','DISPONIBLE']) and !$data->status_curso)
                    <h4 class="pt-2 pb-2">DEL RECIBO DE PAGO</h4>                     
                    <div class="form-row bg-light p-5">
                        <div class="form-group col-md-3 m-1 ">
                            <label>RECIBÍ DE:</label>
                            {{ Form::text('recibide', $data->recibide, ['id'=>'recibide', 'class' => 'form-control', 'placeholder' => 'RECIBÍ DE', 'title' => 'RECICÍ DE']) }}
                        </div>
                        <div class="form-group col-md-3 m-1 ">
                            <label>EXPEDICIÓN:</label>
                            {{ Form::date('fecha', $data->fecha_expedicion, ['id'=>'fecha', 'class' => 'form-control', 'placeholder' => 'DIA/MES/AÑO',  'title'=>'FECHA DE EXPEDICIÓN']) }}
                        </div>
                        <div class="form-group col-md-3 m-1 ">
                            <label>RECIBIÓ:</label>
                            {{ Form::text('recibio', $data->recibio, ['id'=>'recibio', 'class' => 'form-control', 'placeholder' => 'RECIBIÓ', 'title' => 'RECIBIÓ', 'disabled'=>'true']) }}
                        </div>
                        <div class="form-group col-md-2 m-1 "> <br/>
                            {{ Form::button('GUARDAR CAMBIOS', ['id'=>'modificar','class' => 'btn', 'value'=> route('grupos.recibos.modificar')]) }}                                      
                        </div>
                    </div> 
                    <hr/> 
                @endif                 
                <div class="row w-100 form-inline justify-content-end">                    
                    <h5 class="bg-light p-2">RECIBO No. <span class="bg-white p-1">&nbsp;<b>{{$data->uc}}</b> <b class="text-danger">{{ str_pad($data->num_recibo, 4, "0", STR_PAD_LEFT) }}</b>&nbsp;</span></h5>
                    @if($data->file_pdf)
                            <a class="nav-link pt-0" href="{{$path_files}}{{ $data->file_pdf}}" target="_blank">
                                <i  class="far fa-file-pdf  fa-3x text-danger"  title='DESCARGAR RECIBO DE PAGO OFICIALIZADO.'></i>
                            </a>
                    @endif
                    
                    @if($movimientos)
                        {{ Form::select('movimiento', $movimientos, '', ['id'=>'movimiento','class' => 'form-control  col-md-3 m-1', 'placeholder'=>'- MOVIMIENTOS -'] ) }}
                    @endif
                    <div class="custom-file col-md-2" id="inputFile" style="display:none">
                        <input id="file_recibo" type="file" name="file_recibo" class="custom-file-input" accept=".pdf" >
                        <label class="custom-file-label" for="file_recibo">&nbsp;&nbsp;</label>
                    </div>                    
                    {{ Form::select('status_recibo', ['PAGADO'=>'PAGADO','POR COBRAR'=>'POR COBRAR'], '', ['id'=>'status_recibo','class' => 'form-control', 'title'=>'ESTATUS','style'=>'display:none'] ) }}                    
                    {{ Form::text('motivo', '', ['id'=>'motivo', 'class' => 'form-control col-md-4 m-1 ', 'placeholder' => 'MOTIVO', 'title' => 'MOTIVO', 'style'=>'display:none']) }}
                    {{ Form::button('ACEPTAR', ['id'=>'aceptar','class' => 'btn btn-danger', 'style'=>'display:none', 'value'=>route('grupos.recibos.aceptar')]) }}
                    @if($data->status_folio == 'DISPONIBLE')                        
                        {{ Form::text('recibide', $data->recibide, ['id'=>'recibide', 'class' => 'form-control col-md-3 m-1 ', 'placeholder' => 'RECIBÍ DE', 'title' => 'RECICÍ DE']) }}
                        {{ Form::date('fecha', $data->fecha_expedicion, ['id'=>'fecha', 'class' => 'form-control col-md-1 m-1 ', 'placeholder' => 'DIA/MES/AÑO',  'title'=>'FECHA DE EXPEDICIÓN']) }}                        
                        {{ Form::text('recibio', $data->recibio, ['id'=>'recibio', 'class' => 'form-control col-md-3 m-1 ', 'placeholder' => 'RECIBIÓ', 'title' => 'RECIBIÓ', 'disabled'=>'true' ]) }}
                        {{ Form::select('status_recibo', ['PAGADO'=>'PAGADO','POR COBRAR'=>'POR COBRAR'], '', ['id'=>'status_recibo','class' => 'form-control', 'title'=>'ESTATUS'] ) }}
                        {{ Form::button('ASIGNAR', ['id'=>'asignar','class' => 'btn btn-danger', 'value'=> route('grupos.recibos.asignar')]) }}
                    @else                        
                        @if( ($data->status_folio != "IMPRENTA" and !$data->status_curso) OR $data->status_folio=='ACEPTADO') 
                            {{ Form::button('GENERAR RECIBO', ['id'=>'pdfRecibo','class' => 'btn', 'value' => route('grupos.recibos.pdf')]) }}
                        @endif
                        @if($data->status_folio == "CARGADO") 
                            {{ Form::button('ENVIAR', ['id'=>'enviar','class' => 'btn btn-danger', 'value'=> route('grupos.recibos.enviar')]) }}
                        @endif
                    @endif
                    
                    
                </div>            
            @endif
        {!! Form::close() !!}
    </div>
    @section('script_content_js') 
        <script src="{{ asset('js/grupos/recibos.js') }}"></script>               
    @endsection
@endsection