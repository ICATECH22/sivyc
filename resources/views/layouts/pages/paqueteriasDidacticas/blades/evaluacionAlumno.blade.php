<hr style="border-color:dimgray">
<label>
    <h2>EVALUACIÓN DE APRENDIZAJE AL ALUMNO</h2>
</label>

<hr style="border-color:dimgray">
<div class="form-row">
    <div class="form-group col-md-12 col-sm-12">
        <label for="instrucciones" class="control-label">INSTRUCCIONES</label>
        <textarea placeholder="Agrege aqui las instrucciones para la evaluacion del alumno" class="form-control" id="instrucciones" name="instrucciones" cols="15" rows="5"></textarea>
    </div>
</div>

<div class="row col-md-12" id="preguntas-area-parent">
    <input type="number" hidden id="numPreguntas" name="numPreguntas">
    <div class="row col-md-12" id="pregunta1">
        <div class="form-row col-md-7 col-sm-12">
            <div class="form-group col-md-12 col-sm-10">
                <label class="control-label">PREGUNTA</label>
                <textarea placeholder="pregunta" class="form-control"  name="pregunta1" cols="15" rows="2"></textarea>
            </div>
        </div>

        <div class="form-row col-md-4 ">
            <div class="form-group col-md-12 col-sm-6">
                <label for="tipopregunta" class="control-label">TIPO DE PREGUNTA</label>
                <select onchange="cambiarTipoPregunta(this)" class="form-control" name="pregunta1-tipo">
                    <option value="multiple" selected>Multiple</option>
                    <option value="abierta">Abierta</option>
                </select>
            </div>
        </div>

        <div class="form-row col-md-1">
            <div class="form-group col-md-1 col-sm-6">
                <label for="">Eliminar pregunta</label>
                <button type="button" class="btn btn-danger" onclick="removerPregunta(this)">
                    <i class="fa fa-trash"></i>
            </div>
        </div>


        <div class="form-row col-md-7 opcion-area-p1" id="pregunta1-opc">
            <input type="text" hidden id="pregunta1-opc-answer" name="pregunta1-opc-answer">
            <div class="input-group mb-3 " >
                <div class="input-group-prepend">
                    <div class="input-group-text">
                        <input type="checkbox" onclick="setAceptedAnswer(this)"  name="pregunta1-opc-correc[]">
                    </div>
                </div>
                &nbsp;&nbsp;&nbsp;
                <input placeholder="Opcion" type="text" class="form-control resp-abierta multiple" name="pregunta1-opc[]" >
                <a class="btn btn-warning btn-circle m-1 btn-circle-sm" onclick="removerOpcion(this)" >
                    <i class="fa fa-minus"></i>
                </a>
            </div>
        </div>

        <div class="form-row col-md-6 opcion-area-pregunta1" >
            <div class="input-group mb-3">
                <a style="cursor: default;" onclick="agregarOpcion(this)">Agregar opcion</a>
            </div>
        </div>
        
        <div class="form-row col-md-7 respuesta-abierta-area ra-p1" style="display: none">
            <div class="input-group mb-3">
                <input disabled placeholder="Texto de la respuesta abierta" type="text" class="form-control resp-abierta">
            </div>
        </div>
    </div>

</div>


<div class="form-group col-md-2 col-sm-6">
    <a style="cursor: default;" onclick="agregarPregunta()" class="btn btn-primary">Agregar Pregunta</a>
</div>


<div class="row">
    <div class="col-lg-12 margin-tb">>
        <div class="pull-right">
            <!-- @can('cursos.store') -->
            <button type="submit" class="btn btn-primary">Guardar</button>
            <!-- <a type="button" class="btn btn-primary" onclick="confirmacion()">Guardar</a> -->
            <!-- @endcan -->
        </div>
    </div>
</div>