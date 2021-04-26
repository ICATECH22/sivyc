$(function(){
    /**
     * documento de modal
    */
   $('#validarModel').on('show.bs.modal', function(event){
       var button = $(event.relatedTarget);
       var id = button.data('id');
       $('#validarForm').attr("action", "/pago/validacion" + "/" + id);
   });

   //Modal Subir Valsupre Firmado
   $('#DocModal').on('show.bs.modal', function(event){
    var button = $(event.relatedTarget);
    var id = button.data('id');
    $('#idinsmod').val(id);
    });

    //Modal Reemplazar Valsupre Firmado
   $('#DocModal2').on('show.bs.modal', function(event){
    var button = $(event.relatedTarget);
    var id = button.data('id');
    document.getElementById('idinsmod2').value = id;
    //$('idinsmod').val(id);
    });

   //Modal en Contratos
   $('#myModal').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var id = button.data('id');
        console.log(id);
        if(id['4'] == 'Finalizado' || id['4'] == 'Verificando_Pago' || id['4'] == "Pago_Verificado")
        {
            $('#sol_pdf').attr("class", "btn btn-danger");
            $('#sol_pdf').attr("href", "/contrato/solicitud-pago/pdf/" + id['0']);
            $('#contrato_pdf').attr("class", "btn btn-danger");
            $('#contrato_pdf').attr("href", "/contrato/" + id['1']);
            $('#docs_pdf').attr("class", "btn btn-danger");
            $('#docs_pdf').attr("href", id['2']);
            if(id['5'] != "")
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger");
                $('#valsupre_pdf').attr("href", id['5']);
            }
            else
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger disabled");
            }
        }
        else if(id['4'] == 'Pago_Rechazado')
        {
            $('#sol_pdf').attr("class", "btn btn-danger disabled");
            $('#contrato_pdf').attr("class", "btn btn-danger");
            $('#contrato_pdf').attr("href", "/contrato/" + id['1']);
            $('#docs_pdf').attr("class", "btn btn-danger");
            $('#docs_pdf').attr("href", id['2']);
            if(id['5'] != "")
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger");
                $('#valsupre_pdf').attr("href", id['5']);
            }
            else
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger disabled");
            }
        }
        else if(id['4'] == 'Validado')
        {
            $('#sol_pdf').attr("class", "btn btn-danger disabled");
            $('#contrato_pdf').attr("class", "btn btn-danger disabled");
            $('#docs_pdf').attr("class", "btn btn-danger disabled");
            if(id['5'] != "")
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger");
                $('#valsupre_pdf').attr("href", id['5']);
            }
            else
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger disabled");
            }
        }
        else if(id['4'] == 'Contrato_Rechazado')
        {
            $('#sol_pdf').attr("class", "btn btn-danger disabled");
            $('#contrato_pdf').attr("class", "btn btn-danger disabled");
            $('#docs_pdf').attr("class", "btn btn-danger disabled");
            $('#valsupre_pdf').attr("class", "btn btn-danger");
            if(id['5'] != "")
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger");
                $('#valsupre_pdf').attr("href", id['5']);
            }
            else
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger disabled");
            }
        }
        else if(id['4'] == 'Contratado')
        {
            $('#sol_pdf').attr("class", "btn btn-danger disabled");
            $('#contrato_pdf').attr("class", "btn btn-danger");
            $('#contrato_pdf').attr("href", "/contrato/" + id['1']);
            $('#docs_pdf').attr("class", "btn btn-danger disabled");
            $('#valsupre_pdf').attr("class", "btn btn-danger");
            if(id['5'] != "")
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger");
                $('#valsupre_pdf').attr("href", id['5']);
            }
            else
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger disabled");
            }
        }
        else if(id['4'] == 'Validando_Contrato')
        {
            $('#sol_pdf').attr("class", "btn btn-danger disabled");
            $('#contrato_pdf').attr("class", "btn btn-danger disabled");
            $('#docs_pdf').attr("class", "btn btn-danger disabled");
            $('#valsupre_pdf').attr("class", "btn btn-danger");
            if(id['5'] != "")
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger");
                $('#valsupre_pdf').attr("href", id['5']);
            }
            else
            {
                $('#valsupre_pdf').attr("class", "btn btn-danger disabled");
            }
        }
    });

    //Modal en Supre
    $('#supreModal').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var id = button.data('id');
        console.log(id);
        if(id['1'] == 'En_Proceso' || id['1'] == 'Rechazado')
        {
            $('#supre_pdf').attr("class", "btn btn-danger");
            $('#supre_pdf').attr("href", "/supre/pdf/" + id['0']);
            $('#anexo_pdf').attr("class", "btn btn-danger");
            $('#anexo_pdf').attr("href", "/supre/tabla-pdf/" + id['0']);
            $('#valsupre_pdf').attr("class", "btn btn-danger disabled");
            $('#valsupre2_pdf').attr("class", "btn btn-danger disabled");
        }
        else if(id['1'] == 'Validado')
        {
            $('#supre_pdf').attr("class", "btn btn-danger");
            $('#supre_pdf').attr("href", "/supre/pdf/" + id['0']);
            $('#anexo_pdf').attr("class", "btn btn-danger");
            $('#anexo_pdf').attr("href", "/supre/tabla-pdf/" + id['0']);
            $('#valsupre_pdf').attr("class", "btn btn-danger");
            $('#valsupre_pdf').attr("href","/supre/validacion/pdf/" + id['0']);
            if(id['2'] != "")
            {
                $('#valsupre2_pdf').attr("class", "btn btn-danger");
                $('#valsupre2_pdf').attr("href", id['2']);
            }
            if(id['2'] == "")
            {
                $('#valsupre2_pdf').attr("class", "btn btn-danger disabled");
            }
        }
    });

    //Modal de reincio de supre en supre
    $('#restartModal').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var id = button.data('id');
        $('#confirm_restart').attr("href","/supre/reiniciar/" + id);
    });

    //Modal de reincio de Contrato
    $('#restartModalContrato').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var id = button.data('id');
        $('#confirm_restart').attr("href","/contrato/reiniciar/" + id);
    });

    //Modal de reincio de Pago
    $('#restartModalPago').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var id = button.data('id');
        $('#confirm_restart2').attr("href","/pago/reiniciar/" + id);
    });

    //Modal de cancelacion de folio
    $('#cancelModalFolio').on('show.bs.modal', function(event){
        var button = $(event.relatedTarget);
        var id = button.data('id');
        document.getElementById('idf').value = id;
    });
});
