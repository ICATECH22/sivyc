@extends('theme.sivyc.layout')
<!--llamar a la plantilla -->
@section('title', 'Alumnos | SIVyC Icatech')
<!--seccion-->
@section('content')
    <div class="container g-pt-50">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                    <h2>ASPIRANTES</h2>

                    {!! Form::open(['route' => 'alumnos.index', 'method' => 'GET', 'class' => 'form-inline' ]) !!}
                        <select name="busqueda_aspirante" class="form-control mr-sm-2" id="busqueda_aspirante">
                            <option value="">BUSCAR POR TIPO</option>
                            <option value="curp_aspirante">CURP</option>
                            <option value="nombre_aspirante">NOMBRE</option>
                        </select>

                        {!! Form::text('busqueda_aspirantepor', null, ['class' => 'form-control mr-sm-2', 'placeholder' => 'BUSCAR', 'aria-label' => 'BUSCAR']) !!}
                        <button class="btn btn-outline-info my-2 my-sm-0" type="submit">BUSCAR</button>
                    {!! Form::close() !!}
                </div>

                <div class="pull-right">
                    @can('alumnos.inscripcion-paso1')
                        <a class="btn btn-success btn-lg" href="{{route('alumnos.preinscripcion')}}">Nuevo</a>
                    @endcan
                </div>
            </div>
        </div>
        <hr style="border-color:dimgray">
        @if ($contador > 0)
            <table  id="table-instructor" class="table table-bordered table-responsive-md Datatables">
                <caption>Catalogo de Alumnos</caption>
                <thead>
                    <tr>
                        <th scope="col">NOMBRE</th>
                        <th scope="col">CURP</th>
                        @can('alumno.inscripcion-documento')
                        <th scope="col">DOCUMENTOS</th>
                        @endcan
                        @can('alumnos.inscripcion-paso3')
                        <th scope="col">ACCIONES</th>
                        @endcan
                        @can('alumnos.inscripcion-paso2')
                        <th scope="col">MODIFICAR</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach ($retrieveAlumnos as $itemData)
                        <tr>
                            <td scope="row">{{$itemData->apellido_paterno}} {{$itemData->apellido_materno}} {{$itemData->nombre}}</td>
                            <td>{{$itemData->curp}}</td>
                            @can('alumno.inscripcion-documento')
                                <td>
                                    <a href="{{route('alumnos.preinscripcion.paso2',['id' => base64_encode($itemData->id)])}}" class="btn btn-info btn-circle m-1 btn-circle-sm" data-toggle="tooltip" data-placement="top" title="ANEXAR DOCUMENTOS">
                                        <i class="fa fa-upload" aria-hidden="true"></i>
                                    </a>
                                </td>
                            @endcan


                            @can('alumnos.inscripcion-paso3')
                                <td>
                                    <a href="{{route('alumnos.presincripcion-paso2', ['id' => base64_encode($itemData->id)])}}" class="btn btn-danger btn-circle m-1 btn-circle-sm" data-toggle="tooltip" data-placement="top" title="INSCRIBIR">
                                        <i class="fa fa-gears" aria-hidden="true"></i>
                                    </a>
                                </td>
                            @endcan


                            @can('alumnos.inscripcion-paso2')
                                <td>
                                    <a href="{{route('alumnos.presincripcion-modificar', ['id' => base64_encode($itemData->id)])}}" class="btn btn-warning btn-circle m-1 btn-circle-sm" data-toggle="tooltip" data-placement="top" title="MODIFICAR">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                </td>
                            @endcan

                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            {{ $retrieveAlumnos->links() }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="alert alert-warning" role="alert">
                <h2>NO HAY ALUMNOS REGISTRADOS!</h2>
            </div>
        @endif
        <br>
    </div>
    <br>
@endsection
