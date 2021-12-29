  <table class="table table-striped col-md-12" id='tblAlumnos'>
    <thead>
      <tr>
        <th class="h6" scope="col">#</th>            
        <th class="h6" scope="col">Curp</th>                       
        <th class="h6" scope="col">Nombre</th>
        <th class="h6" scope="col">Matricula</th>
        <th class="h6" scope="col">Sexo</th>
        <th class="h6" scope="col" width="8%">Fec. Nac.</th>
        <th class="h6" scope="col">Escolaridad</th>
        <th scope="col" class="h6">TIPO DE INSCRIPCI&Oacute;N</th>
        <th scope="col" class="h6 text-center">COUTA</th>
        <th class="h6 text-center" scope="col"> @if($activar){{'Eliminar'}}@endif</th>                               
        <th class="h6 text-center" scope="col">SID</th>
        <th class="h6 text-center" scope="col">CURP</th>
        <!--<th class="h6 text-center" scope="col">Subir SID</th>--->
                                                  
      </tr>
    </thead>                                    
    <tbody>
      @if(count($alumnos)>0)
          @foreach($alumnos as $a)                               
              <tr id="{{$a->id_reg}}">
                <th scope="row"> {{ $consec++ }} </th>
                <th>{{ $a->curp }}</th>
                <th>{{ $a->apellido_paterno }} {{ $a->apellido_materno }} {{ $a->nombre }}</th> 
                <th>{{ $a->no_control}}</th>
                <th>{{ $a->sex }}</th>
                <th>{{ $a->fnacimiento }}</th>
                <th>{{ $a->ultimo_grado_estudios }}</th>
                <th>{{$a->tinscripcion}}</th>
                <th class="text-center">{{ Form::text('costo['.$a->id_reg.']', $a->costo , ['id'=>'costo['.$a->id_reg.']', 'class' => 'form-control numero', 'size' => 1]) }}</th>
                <th class="text-center">
                  @if($activar)
                    <a class="nav-link" ><i class="fa fa-remove  fa-2x fa-lg text-danger" onclick="eliminar({{$a->id_reg}},'{{ route('preinscripcion.grupo.eliminar') }}');" title="Eliminar"></i></a>
                  @endif
                </th>                                                                                                                                        
                <th class="text-center">
                  @if($a->id_cerss)
                    <a target="_blank" href="{{route('documento.sid_cerrs', ['nocontrol' => base64_encode($a->id_reg)])}}" class="nav-link" ><i class="fa fa-print  fa-2x fa-lg text-info" title="Imprimir SID"></i></a>              
                  @else  
                    <a target="_blank" href="{{route('documento.sid', ['nocontrol' => base64_encode($a->id_reg)])}}" class="nav-link" ><i class="fa fa-print  fa-2x fa-lg text-info" title="Imprimir SID"></i></a>
                  @endif
                </th>
                <!--
                  <th class="text-center">
                  <a class="nav-link" ><i class="fa fa-upload  fa-2x fa-lg text-danger" title="Cargar SID"></i></a>
                  </th>
                --> 
                <th class="text-center">
                  @if (isset($a->requisitos))
                      <?php
                         $documento = json_decode($a->requisitos);
                         $documento = $documento->documento;
                      ?>
                      <a target="_blank" href="{{$documento}}" class="nav-link"><i class="fa fa-print  fa-2x fa-lg text-info" title="Imprimir CURP"></i></a>
                  @else
                      <a target="_blank" href="{{$a->documento_curp}}" class="nav-link"><i class="fa fa-print  fa-2x fa-lg text-info" title="Imprimir CURP"></i></a>
                  @endif
                </th>
              </tr>
              <?php
                    if(!$a->tinscripcion) $turnar=false;
              ?>
          @endforeach     
      @endif                                                                                         
    </tbody>
</table>
<table class="table table-striped col-md-7">
  <thead>
    <tr>
      <th class="h6" scope="col">
        <div>COMPROBANTE DE PAGO:</div>
      </th>
    </tr>
  </thead>
  <tbody>
    <tr>
      @if ($activar AND $folio_grupo)
      <th>
        <div class="custom-file">
          <input type="file" class="custom-file-input" id="customFile" name="customFile" onchange="fileValidationpdf()">
          <label class="custom-file-label" for="customFile">SELECCIONAR DOCUMENTO</label>
        </div>
      </th>
      <th>
        <button type="button" id="comprobante" class="btn btn-success">SUBIR COMPROBANTE</button>
      </th>
      <th>
        <a class="btn btn-dark-green" href="https://www.ilovepdf.com/es/unir_pdf" target="blank">UNIR PDF´s</a>
      </th>
      @endif
      @if ($comprobante)
          <th>
            <a target="_blank" href="{{$comprobante}}" class="nav-link" ><i class="fa fa-print  fa-2x fa-lg text-info" title="Imprimir Comprobante de pago"></i></a>
          </th>
      @endif
    </tr>
  </tbody>
</table>
    <div class="col-md-12 text-right">
        <button type="button" class="btn" id="nuevo" >NUEVO GRUPO</button> &nbsp;&nbsp;
        @if($activar AND $folio_grupo)
            <button type="submit" class="btn" id="update" >GUARDAR CAMBIOS </button> &nbsp;&nbsp;                        
            <button type="button" class="btn bg-danger " id="turnar" >ENVIAR A LA UNIDAD >> </button>
        @endif 
    </div>
