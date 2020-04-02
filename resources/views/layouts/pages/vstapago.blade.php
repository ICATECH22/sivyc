@extends('theme.sivyc.layout')
<!--llamar a la plantilla -->
@section('title', 'Pagos | SIVyC Icatech')
<!--seccion-->
@section('content')
    <style>
        * {
        box-sizing: border-box;
        }

        #myInput {
        background-image: url('img/search.png');
        background-position: 5px 10px;
        background-repeat: no-repeat;
        background-size: 32px;
        width: 100%;
        font-size: 16px;
        padding: 12px 20px 12px 40px;
        border: 1px solid #ddd;
        margin-bottom: 12px;
        }
    </style>
    <div class="container g-pt-50">
        @if ($message =  Session::get('info'))
            <div class="alert alert-info alert-block">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>Registro de Pagos</h2>
                </div>
            </div>
        </div>
        <div class="pull-left">
        </div>
        <hr style="border-color:dimgray">
        <br>
        <h2>Solicitudes de Pago</h2>
        <table  id="table-instructor" class="table table-bordered Datatables">
            <caption>Lista de Contratos en Espera</caption>
            <thead>
                <tr>
                    <th scope="col">N°. Contrato</th>
                    <th scope="col">N°. de Circular</th>
                    <th scope="col">Unidad de Capacitación</th>
                    <th scope="col">Estado</th>
                    <th width="160px">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contratos_folios as $itemData)
                    <tr>
                        <td>{{$itemData->numero_contrato}}</td>
                        <td>{{$itemData->numero_circular}}</td>
                        <td>{{$itemData->unidad_capacitacion}}</td>
                        <td>{{$itemData->status}}</td>
                        <td>
                            @switch($itemData->status)
                                @case('Verificando_Pago')
                                <a class="btn btn-danger" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id_contrato}}","{{$itemData->docs}}","{{$itemData->id_supre}}","{{$itemData->status}}"]'>PDF</a>
                                    @can('verificar_pago.create')
                                        <a class="btn btn-primary" href="{{route('pago.verificarpago', ['id' => $itemData->id_contrato])}}">Verificar</a>
                                    @endcan
                                    @break
                                @case('Pago_Verificado')
                                <a class="btn btn-danger" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id_contrato}}","{{$itemData->docs}}","{{$itemData->id_supre}}","{{$itemData->status}}"]'>PDF</a>
                                    @can('pagos.create')
                                        <a class="btn btn-success" href="{{route('pago-crear', ['id' => $itemData->id_contrato])}}">Confirmar Pago</a>
                                    @endcan
                                    @break
                                @case('Finalizado')
                                <a class="btn btn-danger" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id_contrato}}","{{$itemData->docs}}","{{$itemData->id_supre}}","{{$itemData->status}}"]'>PDF</a>
                                    <a class="btn btn-success" href="{{route('mostrar-pago', ['id' => $itemData->id_contrato])}}" target="_blank">Solicitud</a>
                                    @break
                            @endswitch
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                </tr>
            </tfoot>
        </table>
        <br>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">
      <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Archivos PDF Generables</h4>
                </div>
            <div class="modal-body" style="text-align:center">
                <form action="" id="pdfForm" method="get">
                    @csrf
                <div style="text-align:center" class="form-group">
                    <a class="btn btn-danger" id="sol_pdf" name="sol_pdf" href="#" target="_blank">Solicitud de Pago</a><br>
                </div>
                <div style="text-align:center" class="form-group">
                    <a class="btn btn-danger" id="contrato_pdf" name="contrato_pdf" href="#" target="_blank">Contrato de Instructor</a>
                </div>
                <div style="text-align:center" class="form-group">
                    <a class="btn btn-danger" id="docs_pdf" name="docs_pdf" href="#" target="_blank">Documentos para solicitud de pago</a>
                </div>
                <div style="text-align:center" class="form-group">
                    <a class="btn btn-danger" id="valsupre_pdf" name="valsupre_pdf" href="#" target="_blank">Validación de Suficiencia Presupuestal</a><br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
  </div>
    <br>
@endsection
