<!--Creado por Orlando Chavez-->
@extends('theme.sivyc.layout')
<!--llamar a la plantilla -->
@section('title', 'Contratos | SIVyC Icatech')
<!--seccion-->
@section('content')
    <style>
        * {
        box-sizing: border-box;
        }

        br {
            line-height:70px;
        }
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

        br {
            line-height:50px;
        }
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
                    <h2>Contratos</h2>
                    {!! Form::open(['route' => 'contrato-inicio', 'method' => 'GET', 'class' => 'form-inline' ]) !!}
                        <select name="tipo_contrato" class="form-control mr-sm-2" id="tipo_contrato">
                            <option value="">BUSQUEDA POR TIPO</option>
                            <option value="no_memorandum">N° MEMORANDUM</option>
                            <option value="unidad_capacitacion">UNIDAD CAPACITACIÓN</option>
                            <option value="fecha">FECHA</option>
                        </select>

                        {!! Form::text('busquedaPorContrato', null, ['class' => 'form-control mr-sm-2', 'placeholder' => 'BUSCAR', 'aria-label' => 'BUSCAR', 'value' => 1]) !!}
                        <button class="btn btn-outline-info my-2 my-sm-0" type="submit">BUSCAR</button>
                    {!! Form::close() !!}
                </div>
                <br>
                <div class="pull-right">
                    <a class="btn btn-info  m-1" title="Está a tiempo" data-toggle="modal" data-target="#mymodalSemaforo">
                        Información sobre Semaforo
                    </a>
                </div>
            </div>
        </div>
        <hr style="border-color:dimgray">
        <table  id="table-instructor" class="table table-bordered Datatables table-responsive-md">
            <caption>Catalogo de Suficiencias Presupuestales Validadas</caption>
            <thead>
                <tr>
                    <th scope="col">No. de Memoramdum</th>
                    <th scope="col">Unidad de Capacitación</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Status</th>
                    <th scope="col">Ultima Modificación de Status</th>
                    <th scope="col">Folio de Validación</th>
                    <th scope="col">Acción</th>
                    <th scope="col">semaforo</th>
                </tr>
            </thead>
            @if (count($querySupre) > 0)
                <tbody>
                    @foreach ($querySupre as $itemData)
                        <tr>
                            <th scope="row">{{$itemData->no_memo}}</th>
                            <td>{{$itemData->unidad_capacitacion}}</td>
                            <td>{{$itemData->fecha}}</td>
                            <td>{{$itemData->status}}</td>
                            <td>{{$itemData->fecha_status}}</td>
                            <td>{{$itemData->folio_validacion}}</td>
                            <td>
                                @if ($itemData->status == 'Validado')
                                    <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="Documento pdf" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id_contrato}}","{{$itemData->docs}}","{{$itemData->id}}","{{$itemData->status}}","{{$itemData->doc_validado}}"]'>
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a>
                                    @can('contratos.create')
                                        <a class="btn btn-success btn-circle m-1 btn-circle-sm" title="Crear Contrato" href="{{route('contratos.create', ['id' => $itemData->id_folios])}}">
                                            <i class="fa fa-file-text" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                @endif
                                @if ($itemData->status == 'Validando_Contrato')
                                    <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="Documento pdf" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id_contrato}}","{{$itemData->docs}}","{{$itemData->id}}","{{$itemData->status}}","{{$itemData->doc_validado}}"]'>
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a>
                                    @can('contrato.validate')
                                        <a class="btn btn-success btn-circle m-1 btn-circle-sm" title="Validar Contrato" href="{{route('contrato-validar', ['id' => $itemData->id_contrato])}}">
                                            <i class="fa fa-eye" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                @endif
                                @if ($itemData->status == 'Contrato_Rechazado')
                                    <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="Documento pdf" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id_contrato}}","{{$itemData->docs}}","{{$itemData->id}}","{{$itemData->status}}","{{$itemData->doc_validado}}"]'>
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a>
                                    @can('contratos.edit')
                                        <a class="btn btn-success btn-circle m-1 btn-circle-sm" title="Modificar Contrato" href="{{route('contrato-mod', ['id' => $itemData->id_contrato])}}">
                                            <i class="fa fa-file-text" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                @endif
                                @if ($itemData->status == 'Contratado')
                                    <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="Documento pdf" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id_contrato}}","{{$itemData->docs}}","{{$itemData->id}}","{{$itemData->status}}","{{$itemData->doc_validado}}"]'>
                                        <i class="fa fa-file-pdf-o" aria-hidden="true"></i>
                                    </a>
                                    @can('solicitud_pago.create')
                                        <a class="btn btn-success btn-circle m-1 btn-circle-sm" title="Solicitar Pago" href="{{route('solicitud-pago', ['id' => $itemData->id_folios])}}">
                                            <i class="fa fa-money" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    <a class="btn btn-info btn-circle m-1 btn-circle-sm" title="Consulta de Validación" href="{{route('contrato-validado-historial', ['id' => $itemData->id_contrato])}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                @endif
                                @if ($itemData->status == 'Pago_Rechazado')
                                    <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="Validación" href="{{route('valsupre-pdf', ['id' => $itemData->id])}}" target="_blank">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </a>
                                    <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="PDF" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id}}","{{$itemData->status}}","{{$itemData->doc_validado}}"]'>
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                    </a>
                                    @can('contratos.edit')
                                        <a class="btn btn-success btn-circle m-1 btn-circle-sm" title="Modificar Solicitud de Pago" href="{{route('pago-mod', ['id' => $itemData->id_folios])}}" >
                                            <i class="fa fa-wrench" aria-hidden="true"></i>
                                        </a>
                                    @endcan
                                    <a class="btn btn-info btn-circle m-1 btn-circle-sm" title="Consulta de Validación" href="{{route('contrato-validado-historial', ['id' => $itemData->id_contrato])}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                @endif
                                @if ($itemData->status == 'Verificando_Pago')
                                    <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="PDF" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id_contrato}}","{{$itemData->docs}}","{{$itemData->id}}","{{$itemData->status}}","{{$itemData->doc_validado}}"]'>
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                    </a>
                                    <a class="btn btn-info btn-circle m-1 btn-circle-sm" title="Consulta de Validación" href="{{route('contrato-validado-historial', ['id' => $itemData->id_contrato])}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                @endif
                                @if ($itemData->status == 'Pago_Verificado')
                                    <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="PDF" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id_contrato}}","{{$itemData->docs}}","{{$itemData->id}}","{{$itemData->status}}","{{$itemData->doc_validado}}"]'>
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                    </a>
                                    <a class="btn btn-info btn-circle m-1 btn-circle-sm" title="Consulta de Validación" href="{{route('contrato-validado-historial', ['id' => $itemData->id_contrato])}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                @endif
                                @if ($itemData->status == 'Finalizado')
                                    <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="PDF" id="show_pdf" name="show_pdf" data-toggle="modal" data-target="#myModal" data-id='["{{$itemData->id_folios}}","{{$itemData->id_contrato}}","{{$itemData->docs}}","{{$itemData->id}}","{{$itemData->status}}","{{$itemData->doc_validado}}"]'>
                                        <i class="fa fa-file" aria-hidden="true"></i>
                                    </a>
                                    <a class="btn btn-info btn-circle m-1 btn-circle-sm" title="Consulta de Validación" href="{{route('contrato-validado-historial', ['id' => $itemData->id_contrato])}}">
                                        <i class="fa fa-eye" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if ($itemData->fecha_dif > 0)
                                    <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="La fecha de vencimiento ha pasado por {{$itemData->fecha_dif }} días">
                                        <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                                    </a>
                                @elseif ($itemData->fecha_dif >= -3)
                                    <a class="btn btn-warning btn-circle m-1 btn-circle-sm" title="La fecha de vencimiento está cerca faltán {{$itemData->fecha_dif }} días">
                                        <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                                    </a>
                                @else
                                    <a class="btn btn-success btn-circle m-1 btn-circle-sm" title="Está a tiempo">
                                        <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="8">
                            {{ $querySupre->appends(request()->query())->links() }}
                        </td>
                    </tr>
                </tfoot>
            @else
                <tbody>
                    <tr>
                        <td colspan="8">
                            <h4>
                                <center>
                                    <b>NO HAY REGISTROS DISPONIBLES</b>
                                </center>
                            </h4>
                        </td>
                    </tr>
                </tbody>
            @endif

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
                            <a class="btn btn-danger" id="docs_pdf" name="docs_pdf" href="#" target="_blank" download>Documentos para solicitud de pago</a>
                        </div>
                        <div style="text-align:center" class="form-group">
                            <a class="btn btn-danger" id="valsupre_pdf" name="valsupre_pdf" href="#" target="_blank">Validación de Suficiencia Presupuestal</a><br>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Modal Ends-->
    <!--Modal Semaforo-->
    <div class="modal fade" id="mymodalSemaforo" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">INFORMACIÓN SOBRE EL SEMAFORO</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

                <a class="btn btn-danger btn-circle m-1 btn-circle-sm" title="La fecha de vencimiento ha pasado por {{$itemData->fecha_dif }} días">
                    <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                </a>
                    <b>LA FECHA DEL CURSO YA HA FINALIZADO O TERMINADO</b>
                <br>
                <a class="btn btn-warning btn-circle m-1 btn-circle-sm" title="La fecha de vencimiento está cerca faltán {{$itemData->fecha_dif }} días">
                    <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                </a>
                    <b>EL CURSO TIENE TRES DÍAS PARA FINALIZAR</b>
                <br>
                <a class="btn btn-success btn-circle m-1 btn-circle-sm" title="Está a tiempo">
                    <i class="fa fa-lightbulb-o" aria-hidden="true"></i>
                </a>
                    <b>LAS FECHAS DE FINALIZACIÓN DEL CURSO ESTÁN EN TIEMPO Y FORMA</b>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
    </div>
    <!--Modal Semaforo Ends-->
<br>
@endsection
