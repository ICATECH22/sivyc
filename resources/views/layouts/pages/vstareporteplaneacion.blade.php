<!-- Creado por Orlando Chávez 04012021-->
@extends('theme.sivyc.layout')
@section('title', 'Reporte de Suficiencias Presupuestales| Sivyc Icatech')
<head>
    <style>
        .radio-xl .custom-control-label::before,
        .radio-xl .custom-control-label::after {
        top: 1.2rem;
        width: 1.85rem;
        height: 1.85rem;
        }

        .radio-xl .custom-control-label {
        padding-top: 23px;
        padding-left: 10px;
        }

        td {
            text-align: center; /* center checkbox horizontally */
            vertical-align: middle; /* center checkbox vertically */
        }
        #choice-td{
            background-color: white;
        }
        table {
            border: 1px solid;
            width: 200px;
        }
        tr {
            height: 65px;
        }
    </style>
</head>
@section('content')
    <section class="container g-pt-50">
        <form action="{{ route('planeacion.reportepdf') }}" method="post" id="registercontrato">
            @csrf
            <div class="text-center">
                <h1>Reporte de Suficiencias Presupuestales</h1>
            </div>
            <br>
            <h2>Filtrar Suficiencias Presupuestales Por:</h2>
            <br>
            <table  id="table-instructor" class="table table-responsive-md">
                <tbody>
                    <tr>
                        <td class="custom-radio radio-xl" id='choice-td'>
                            <input type="radio" class="custom-control-input"  id="general" name="filtro" value="general">
                            <label for="general" class="custom-control-label"><h4>General</h4></label>
                        </td>
                        <td class="custom-radio radio-xl" id='choice-td'>
                            <input type="radio" class="custom-control-input"  id="curso" name="filtro" value="curso">
                            <label for="curso" class="custom-control-label"><h4>Curso</h4></label>
                        </td>
                        <td class="custom-radio radio-xl" id='choice-td'>
                            <input type="radio" class="custom-control-input"  id="unidad" name="filtro" value="unidad">
                            <label for="unidad" class="custom-control-label"><h4>Unidad</h4></label>
                        </td>
                        <td class="custom-radio radio-xl" id='choice-td'>
                            <input type="radio" class="custom-control-input"  id="instructor" name="filtro" value="instructor">
                            <label for="instructor" class="custom-control-label"><h4>Instructor</h4></label>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr style="border-color:dimgray">
            <div class="form-row">
                <div class="form-group col-md-2"></div>
                <div class="form-group col-md-3">
                    <label for="inputid_curso"><h3>De:</h3></label>
                    <input type="date" name="fecha1" id="fecha1" class="form-control" required>
                </div>
                <div class="form-group col-md-1"></div>
                <div class="form-group col-md-3">
                    <label for="inputid_curso"><h3>Hasta:</h3></label>
                    <input type="date" name="fecha2" id="fecha2" class="form-control" required>
                </div>
            </div>
            <div id="div_curso" class="form-row d-none d-print-none">
                <div class="form-group col-md-2"></div>
                <div class="form-group col-md-6">
                    <label for="inputid_curso"><h3>Nombre del Curso</h3></label>
                    <input type="text" name="cursoaut" id="cursoaut" class="form-control" required>
                    <input type="text" name="id_curso" id="id_curso" class="form-control" hidden required>
                </div>
            </div>
            <div id="div_instructor" class="form-row d-none d-print-none">
                <div class="form-group col-md-2"></div>
                <div class="form-group col-md-6">
                    <label for="inputid_instructor"><h3>Nombre del Instructor</h3></label>
                    <input type="text" name="instructoraut" id="instructoraut" class="form-control" required>
                    <input type="text" name="id_instructor" id="id_instructor" class="form-control" hidden  required>
                </div>
            </div>
            <div id="div_unidad" class="form-row d-none d-print-none">
                <div class="form-group col-md-2"></div>
                <div class="form-group col-md-6">
                    <label for="unidad" class="control-label">Unidad de Capacitación </label>
                    <select name="unidad" id="unidad" class="form-control">
                    <option value="SIN ESPECIFICAR">SIN ESPECIFICAR</option>
                    @foreach ($unidades as $data )
                        <option value="{{$data->unidad}}">{{$data->unidad}}</option>
                    @endforeach
                </select>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-lg-12 margin-tb">
                    <div class="pull-left">
                        <a class="btn btn-danger" href="{{URL::previous()}}">Regresar</a>
                    </div>
                    <div class="pull-right">
                        <button type="submit" class="btn btn-primary" >Generar</button>
                    </div>
                </div>
            </div>
        </form>
    </section>
@stop
