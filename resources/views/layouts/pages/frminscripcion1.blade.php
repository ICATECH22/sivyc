@extends('theme.sivyc.layout')
<!--llamar a la plantilla -->
@section('title', 'Solicitud de Inscripción | Sivyc Icatech')
<!--seccion-->
@section('content')
    <div class="container g-pt-50">
        <form method="POST">
            @csrf
            <div style="text-align: center;">
                <label for="tituloformulariocurso"><h1>Solicitud de Inscripción (SID)</h1></label>
            </div>
            <hr style="border-color:dimgray">
            <div class="form-row">
            <!-- Unidad -->
            <div class="form-group col-md-4">
                <label for="nocontrol" class="control-label">Número de Control</label>
                <input type="text" class="form-control" id="nocontrol" name="nocontrol" placeholder="N° de Control">
            </div>
            <!--Unidad Fin-->
            <!-- nombre curso -->
            <div class="form-group col-md-4">
                <label for="fecha" class="control-label">Fecha</label>
                <input type="date" class="form-control" id="fecha" name="fecha">
            </div>
            <!-- nombre curso FIN-->
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="nosolicitud  " class="control-label">Número de Solicitud</label>
                    <input type="text" class="form-control" id="nosolicitud" name="nosolicitud">
                </div>
            </div>
            <div class="form-row">
            <!--nombre aspirante-->
            <div class="form-group col-md-4">
                <label for="nombreaspirante " class="control-label">Nombre del Aspirante</label>
                <input type="text" class="form-control" id="nombreaspirante" name="nombreaspirante">
            </div>
            <!--nombre aspirante END-->
            <!-- apellido paterno -->
            <div class="form-group col-md-4">
                <label for="apaternoaspirante" class="control-label">Apellido Paterno</label>
                <input type="text" class="form-control" id="apaternoaspirante" name="apaternoaspirante">
            </div>
            <!-- apellido paterno END -->
            <!-- apellido materno-->
            <div class="form-group col-md-4">
                <label for="amaternoaspirante" class="control-label">Apellido Materno</label>
                <input type="text" class="form-control" id="amaternoaspirante" name="amaternoaspirante">
            </div>
            <!-- apellido materno END-->

            </div>
            <div class="form-row">
                <!-- curp -->
                <div class="form-group col-md-3">
                <label for="curpaspirante" class="control-label">Curp Aspirante</label>
                <input type="text" class="form-control" id="curpaspirante" name="curpaspirante" placeholder="Curp">
                </div>
                <!-- curp END -->
                <!-- genero-->
                <div class="form-group col-md-3">
                <label for="generoaspirante" class="control-label">Genero</label>
                <select class="form-control" id="generoaspirante">
                    <option>Mujer</option>
                    <option>Hombre</option>
                </select>
                </div>
                <!-- genero END-->
                <!-- edad -->
                <div class="form-group col-md-3">
                <label for="edadaspirante" class="control-label">Edad</label>
                <input type="text" class="form-control" id="edadaspirante" name="edadaspirante">
                </div>
                <!-- edad END -->
                <!-- telefono-->
                <div class="form-group col-md-3">
                <label for="telefonoaspirante" class="control-label">Teléfono</label>
                <input type="text" class="form-control" id="telefonoaspirante" name="telefonoaspirante">
                </div>
                <!-- telefono END-->
            </div>
            <div class="form-row">
                <!-- domicilio -->
                <div class="form-group col-md-12">
                <label for="domicilioaspirante" class="control-label">Domicilio</label>
                <input type="text" class="form-control" id="domicilioaspirante" name="domicilioaspirante">
                </div>
                <!-- domicilio END -->
            </div>
            <div class="form-row">
                <!-- colonia -->
                <div class="form-group col-md-3">
                <label for="coloniaaspirante" class="control-label">Colonia</label>
                <input type="text" class="form-control" id="coloniaaspirante" name="coloniaaspirante">
                </div>
                <!-- colinia END -->
                <div class="form-group col-md-3">
                    <label for="codigopostalaspirante" class="control-label">Código Postal</label>
                    <input type="text" class="form-control" id="codigopostalaspirante" name="codigopostalaspirante">
                </div>
                <!--estado-->
                <div class="form-group col-md-3">
                    <label for="estadoaspirante" class="control-label">Estado</label>
                    <select class="form-control" id="estadoaspirante">
                        <option>estado1</option>
                        <option>estado2</option>
                    </select>
                </div>
                <!--estado END-->
                <!--municipio-->
                <div class="form-group col-md-3">
                    <label for="municipioaspirante" class="control-label">Municipio</label>
                    <select class="form-control" id="municipioaspirante">
                        <option>estado1</option>
                        <option>estado2</option>
                    </select>
                </div>
                <!--municipio END-->
            </div>
            <div class="form-row">
                <!-- estado civil -->
                <div class="form-group col-md-6">
                <label for="estadocivil" class="control-label">Estado Civil</label>
                <select class="form-control" id="estadocivil">
                    <option>opcion1</option>
                    <option>opcion2</option>
                </select>
                </div>
                <!-- estado civil END -->
                <div class="form-group col-md-6">
                    <label for="discapacidadaspirante" class="control-label">Discapacidad que Presenta</label>
                    <input type="text" class="form-control" id="discapacidadaspirante" name="discapacidadaspirante">
                </div>
            </div>
            <hr>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <br>
    </div>
@endsection
