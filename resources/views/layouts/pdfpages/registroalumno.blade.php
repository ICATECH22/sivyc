<html>
    <!--pdf registro para alumnos-->
<head>
  <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  <style>
    body{
      font-family: sans-serif;
    }
    @page {
        margin: 50px 50px;
    }
    small {
        font-size: .7em
    }
    sa {
        text-decoration-line: overline;
    }
    se {
        text-decoration-line: underline;
    }
    table {
        margin-top: .6em;
        margin-bottom: 0.5em;
    }
    table, td {
        border-style: none;
        border: 1px solid black; //Cualquier otro tipo de borde como bottom que es el inferior o ninguno
        padding: 0;
    }

    td.tres { width: calc(100%/2); }
    td.cuatro { width: calc(100%/4); }
    small.sml {
        font-size: .5em
    }
    td{
        padding: 2px 3px;
    }
    div.centrado {
        text-align: center;
    }
    small.texto-centrado {
        font-size: .8em
    }
    .linea {
        border-top: 1px solid black;
        height: 2px;
        max-width: 200px;
        padding: 0;
        margin: 5px auto 0 auto;
      }

      .centrados{
          text-align: center;
      }
  </style>
</head>
 <body>
    <div class="container g-pt-70">
        <table class="table td">
            <colgroup>
				<col style="width: 30%"/>
				<col style="width: 70%"/>
			</colgroup>
            <thead>
              <tr>
                <td scope="col" colspan="2">
                    <div align="center">
                        <b>DATOS DE LA UNIDAD DE CAPACITACIÓN</b>
                    </div>
                </td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td colspan="2" style='border-bottom:none'>
                    <small>
                        <b>INSTITUTO:</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <se>
                            <b>INSTITUTO DE CAPACITACIÓN Y VINCULACION TECNÓLOGICA DEL ESTADO DE CHIAPAS "ICATECH"</b>
                        </se>
                    </small>
                </td>
              </tr>
              <tr>
                <td scope="row" class="tres" style='border-right:none;border-top:none'>
                    <small>
                        <b> UNIDAD DE CAPACITACIÓN: </b>
                    </small>

                </td>
                <td scope="row" class="tres" style='border-left:none;border-top:none'>
                   <small>
                       <b> CLAVE CCT: </b>
                   </small>
                </td>
              </tr>
            </tbody>
        </table>
        <table class="table td">
            <colgroup>
				<col style="width: 25%"/>
                <col style="width: 25%"/>
                <col style="width: 25%"/>
                <col style="width: 25%"/>
			</colgroup>
            <thead>
                <tr>
                  <td scope="col" colspan="4">
                      <div align="center">
                          <b>DATOS PERSONALES</b>
                      </div>
                  </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td scope="row" class="cuatro" style='border-right:none; border-bottom:none;'>
                        <small>
                           <b> NOMBRE DEL ASPIRANTE: </b>
                        </small>
                    </td>
                    <td scope="row" class="cuatro" style='border-left:none; border-right:none; border-bottom:none;'>
                        <small>
                            <b> PRIMER APELLIDO: &nbsp;&nbsp;</b>
                            <se>OZUNA</se>
                        </small>
                    </td>
                    <td scope="row" class="cuatro" style='border-right:none;border-left:none; border-bottom:none;'>
                        <small>
                            <b> SEGUNDO APELLIDO: &nbsp;&nbsp;</b>
                            <se>CANTORAL</se>
                        </small>
                    </td>
                    <td scope="row" class="cuatro" style='border-left:none; border-bottom:none;'>
                        <small>
                            <b> NOMBRE(S): &nbsp;&nbsp;</b>
                            <se>VICENTA DEL CARMEN</se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td style='border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>SEXO: &nbsp;&nbsp;</b>
                            <se>MUJER</se>
                        </small>
                    </td>
                    <td style='border-left:none; border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>CURP: &nbsp;&nbsp;</b>
                            <se>OUCV640323MCSZNC03</se>
                        </small>
                    </td>
                    <td style='border-left:none; border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>EDAD: &nbsp;&nbsp;</b>
                            <se>31 AÑOS</se>
                        </small>
                    </td>
                    <td style='border-left:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>TELEFONO: &nbsp;&nbsp;</b>
                            <se class="linea"> 9612766372 </se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="tres" style='border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>DOMICILIO: &nbsp;&nbsp;</b>
                            <se>
                                ANDADOR 25 MANZANA 15 CASA N° 2 INFONAVITT GRIJALVA 2 SECCIÓN
                            </se>
                        </small>
                    </td>
                    <td colspan="2" class="tres" style='border-left:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>COLONIA O LOCALIDAD: &nbsp;&nbsp;</b>
                            <se>INFONAVIT GRIJALVA</se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td style='border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>C.P.: &nbsp;&nbsp;</b>
                            <se>29000</se>
                        </small>
                    </td>
                    <td style='border-left:none; border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>MUNICIPIO: &nbsp;&nbsp;</b>
                            <se>CHIAPA DE CORZO</se>
                        </small>
                    </td>
                    <td colspan="2" style='border-left:none; border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>ESTADO: &nbsp;&nbsp;</b>
                            <se>CHIAPAS</se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style='border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>ESTADO CIVIL: &nbsp;&nbsp;</b>
                            <se>SOLTERO</se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td colspan="4" style='border-right:none; border-top:none;'>
                        <small>
                            <b>DISCAPACIDAD QUE PRESENTA: &nbsp;&nbsp;</b>
                            <se>NINGUNA</se>
                        </small>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table td" cellspacing="0" cellpadding="0">
            <colgroup>
				<col style="width: 50%"/>
                <col style="width: 50%"/>
			</colgroup>
            <thead>
                <tr>
                  <td scope="col" colspan="2">
                      <div align="center">
                          <b>DATOS GENERALES</b>
                      </div>
                  </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" scope="row" style='border-left:none; border-right:none; border-bottom:none;'>
                        <small>
                            <b> ESPECIALIDAD A LA QUE DESEAN INSCRIBIRSE: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
                            <se>ALIMENTOS Y BEBIDAS</se>
                         </small>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" scope="row" class="tres" style='border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>CURSO: &nbsp;&nbsp;</b>
                            <se>MANPULACIÓN HIGIENICA</se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td scope="row" class="tres" style='border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>HORARIO: &nbsp;&nbsp;</b>
                            <se>8 PM A 9 PM</se>
                        </small>
                    </td>
                    <td scope="row" class="tres" style='border-left:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>GRUPO: &nbsp;&nbsp;</b>
                            <se></se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>DOCUMENTACIÓN ENTREGADA: &nbsp;&nbsp;</b>
                            <se></se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>EXTRANJEROS ANEXAR: &nbsp;&nbsp;</b>
                            <se></se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td style='border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>EMPRESA DONDE TRABAJA: &nbsp;&nbsp;</b>
                            <se>GURDERÍA</se>
                        </small>
                    </td>
                    <td style='border-right:none; border-left:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>PUESTO: &nbsp;&nbsp;</b>
                            <se>MAESTRA</se>
                            <div class="linea"></div>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td style='border-right:none; border-top:none;'>
                        <small>
                            <b>ANTIGUEDAD: &nbsp;&nbsp;</b>
                            <se></se>
                        </small>
                    </td>
                    <td style='border-right:none; border-left:none; border-top:none;'>
                        <small>
                            <b>DIRECCIÓN: &nbsp;&nbsp;</b>
                            <se></se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='border-right:none; border-top:none;'>
                        <small class="sml">
                            <b>NOTA: LA DOCUMENTACIÓN DEBERA ENTREGARSE EN ORIGINAL Y COPIA PARA SU COTEJO.</b>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td scope="col" colspan="2">
                        <div align="center">
                            <b>DATOS PARA LA UNIDAD DE CAPACITACIÓN</b>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='border-right:none; border-top:none;  border-bottom:none;'>
                        <small>
                            <b>MEDIO POR EL QUE SE ENTERÓ DEL SISTEMA: &nbsp;&nbsp;</b>
                            <se></se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='border-right:none; border-top:none; border-bottom:none;'>
                        <small>
                            <b>MOTIVOS DE ELECCIÓN DEL SISTEMA DE CAPACITACIÓN: &nbsp;&nbsp;</b>
                            <se></se>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style='border-right:none; border-top:none; border-bottom:none;'>
                        <div class="centrado">
                            <small>
                                EL ASPIRANTE SE COMPROMETE A CUMPLIR CON LAS NORMAS Y DISPOSICIONES DICTADAS POR LAS AUTORIDADES DE LA UNIDAD.
                            </small>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td scope="row" class="tres" style='border-right:none;border-top:none; border-bottom:none;'>
                        <small>
                            <div class="centrados">
                                <b> OZUNA CANTORAL VICENTA DEL CARMEN </b>
                                <div class="linea"></div>
                            </div>
                        </small>
                    </td>
                    <td scope="row" class="tres" style='border-left:none;border-top:none; border-bottom:none;'>
                       <small>
                            <div class="centrados">
                                <b> AMAYRANI ORTEGA ESPINOZA </b>
                                <div class="linea"></div>
                            </div>
                       </small>
                    </td>
                </tr>
                <tr>
                    <td scope="row" class="tres" style='border-right:none;border-top:none'>
                        <small>
                            <div class="centrados">
                                <b> NOMBRE Y FIRMA DEL ASPIRANTE</b>
                            </div>
                        </small>
                    </td>
                    <td scope="row" class="tres" style='border-left:none;border-top:none'>
                       <small>
                            <div class="centrados">
                                <b> NOMBRE Y FIRMA DE LA PERSONA QUE INSCRIBE </b>
                            </div>
                       </small>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

 </body>
</html>

