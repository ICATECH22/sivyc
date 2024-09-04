@extends('theme.sivyc.layout')
@section('content_script_css')
    <link rel="stylesheet" href="{{ asset('css/global.css') }}" />
    {{-- links de prueba y de produccion --}}
    <link rel="stylesheet" type="text/css"
        href="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/plugins/bootstrap-4.3.1/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/plugins/jasny-bootstrap4/css/jasny-bootstrap.min.css" />
    <style>
        /* Estilo personalizado para el interruptor */
        .custom-switch .custom-control-label::before {
            width: 28px;
            /* Ajusta el ancho del interruptor */
            height: 17;
            /* Ajusta la altura del interruptor */
        }

        .container {
            width: 80%;
            margin: 0 auto;
        }

        .filter-form {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .filter-form input,
        .filter-form select {
            padding: 5px;
            margin-right: 10px;
        }

        .no-records {
            text-align: center;
            padding: 20px;
            background-color: #f0f0f0;
            margin-bottom: 20px;
        }

        .new-form {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .new-form input,
        .new-form select {
            margin-right: 10px;
            padding: 10px;
            box-sizing: border-box;
        }

        .new-form button {
            padding: 10px 20px;
        }

        @media (max-width: 768px) {

            .filter-form,
            .new-form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-form input,
            .filter-form select,
            .filter-form button,
            .new-form input,
            .new-form select,
            .new-form button {
                margin-right: 0;
            }
        }

        .info-box {
            box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
            border-radius: .25rem;
            background-color: #fff;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 1rem;
            min-height: 80px;
            padding: .5rem;
            position: relative;
            width: 100%;
        }

        .info-box-content {
            display: -ms-flexbox;
            display: flex;
            -ms-flex-direction: column;
            flex-direction: column;
            -ms-flex-pack: center;
            justify-content: center;
            line-height: 1.8;
            -ms-flex: 1;
            flex: 1;
            padding: 0 10px;
            overflow: hidden;
        }

        .info-box .info-box-text,
        .info-box .progress-description {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .text-muted {
            color: #6c757d !important;
        }

        .text-center {
            text-align: center !important;
        }

        .mb-0,
        .my-0 {
            margin-bottom: 0 !important;
        }

        .info-box .info-box-number {
            display: block;
            margin-top: .25rem;
            font-weight: 700;
        }

        .list-unstyled {
            padding-left: 0;
            list-style: none;
        }

        dl,
        ol,
        ul {
            margin-top: 0;
            margin-bottom: 1rem;
        }

        ul {
            display: block;
            list-style-type: disc;
            margin-block-start: 1em;
            margin-block-end: 1em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            padding-inline-start: 40px;
            unicode-bidi: isolate;
        }

        .text-secondary {
            color: #6c757d !important;
        }

        .btn-link {
            font-weight: 400;
            color: #007bff;
            text-decoration: none;
        }

        a {
            color: #007bff;
            text-decoration: none;
            background-color: transparent;
        }

        .post {
            border-bottom: 1px solid #adb5bd;
            color: #666;
            margin-bottom: 15px;
            padding-bottom: 15px;
        }

        .post .user-block {
            margin-bottom: 5px;
            width: 100%;
        }

        /* Estilos de la tabla */
        .table-responsive {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            padding: 10px;
            border: 1px solid #dee2e6;
            text-align: left;
        }

        table thead {
            background-color: #f2f2f2;
            color: black;
        }

        table tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Estilos personalizados para enlaces */
        a {
            color: inherit;
            /* Hereda el color del elemento padre */
            text-decoration: none;
            /* Elimina el subrayado */
        }

        a:hover {
            color: #f2f2f2;
            /* Cambia el color al pasar el ratón por encima */
            text-decoration: underline;
            /* Subraya el enlace al pasar el ratón por encima */
        }

        /* Estilos responsivos */
        @media screen and (max-width: 768px) {

            table th,
            table td {
                display: block;
                width: 100%;
            }

            table thead {
                display: none;
            }

            table tbody tr {
                display: block;
                margin-bottom: 10px;
            }

            table tbody tr td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            table tbody tr td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 10px;
                font-weight: bold;
                text-align: left;
            }

        }

        .comments-container {
            max-width: 100%;
            overflow: hidden;
        }

        .comments-list {
            list-style-type: none;
            padding: 0;
        }

        .comment-main-level,
        .reply-list {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .comment-avatar {
            margin-right: 15px;
            flex-shrink: 0;
        }

        .comment-avatar img {
            display: block;
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }

        .comment-box {
            background: #f9f9f9;
            border: 1px solid #e1e1e1;
            padding: 15px;
            border-radius: 5px;
            width: 100%;
        }

        .comment-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .comment-name {
            font-size: 1.1em;
            margin: 0;
        }

        .comment-name a {
            color: #007bff;
            text-decoration: none;
        }

        .comment-name.by-author a {
            font-weight: bold;
        }

        .comment-head span {
            font-size: 0.85em;
            color: #777;
        }

        .comment-content {
            font-size: 0.95em;
        }

        .fa-reply,
        .fa-heart {
            cursor: pointer;
            margin-left: 15px;
            color: #777;
        }

        .reply-list {
            list-style-type: none;
            padding: 0;
            padding-left: 50px;
        }

        @media (max-width: 767.98px) {
            .comment-box {
                padding: 10px;
            }

            .comment-head {
                flex-direction: column;
                align-items: flex-start;
            }

            .fa-reply,
            .fa-heart {
                margin-left: 0;
                margin-top: 10px;
            }
        }

        .scroll-vertical {
            width: 100%;
            /* Ancho del div */
            height: 200px;
            /* Altura del div */
            overflow-y: scroll;
            /* Activar scroll vertical */
            overflow-x: hidden;
            /* Bordes para visualizar mejor el contenedor */
            padding: 5px;
            /* Espacio interno */
            margin-top: 15px;
        }

        /* Estilo para navegadores basados en WebKit (Chrome, Safari) */
        .scroll-vertical::-webkit-scrollbar {
            height: 8px;
            width: 8px;
            background: gray;
            /* Ancho de la barra de desplazamiento vertical */
        }

        .scroll-vertical::-webkit-scrollbar-track {
            background: #f1f1f1;
            /* Color de fondo del track de la barra */
        }

        .scroll-vertical::-webkit-scrollbar-thumb {
            background: #888;
        }

        .scroll-vertical::-webkit-scrollbar-thumb:hover {
            background: #555;
            /* Color de la barra al pasar el ratón */
        }

        ::-webkit-scrollbar-thumb:vertical {
            background: #000;
            border-radius: 8px;
        }

        .padre {
            /* background-color: #fafafa; */
            /* margin: 1rem; */
            /* padding: 1rem; */
            /* border: 2px solid #ccc; */
            /* IMPORTANTE */
            text-align: center;
        }
    </style>
@endsection
@section('title', 'Formatos Rf001 enviados a revisión | SIVyC Icatech')
@php
    $movimiento = json_decode($getConcentrado->movimientos, true);
    $importeTotal = 0;

    $bandera = Crypt::encrypt($solicitud);
    $encrypted = base64_encode($bandera);
    $encrypted = str_replace(['+', '/', '='], ['-', '_', ''], $encrypted);
@endphp
@section('content')
    <div class="card-header"><a href="{{ route('reporte.rf001.sent', ['generado' => $encrypted]) }}">A Revisión </a>/ Detalles
        de Reporte RF-001</div>
    <div class="card card-body  p-5" style=" min-height:450px;">
        @if (session('message'))
            <div class="alert alert-success" role="alert">
                {{ session('message') }}
            </div>
        @endif
        <div class="row">
            <div class="col-12 col-md-12 col-lg-12 order-2 order-md-1">
                <div class="row">
                    <div class="col-12">
                        <h4>DETALLES DEL CONCENTRADO DE INGRESOS</h4>
                        <div class="post">
                            <div class="user-block">
                                <span class="username">UNIDAD:</span>
                                <span class="description" style="font-weight: bold;">{{ $getConcentrado->unidad }}</span>
                            </div>
                            <div class="user-block">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">
                                    Memorándum
                                </span>
                                <span
                                    class="info-box-number text-center text-muted mb-0">{{ $getConcentrado->memorandum }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">
                                    Periodo del concentrado
                                </span>
                                @php
                                    $periodoInicio = Carbon\Carbon::parse($getConcentrado->periodo_inicio);
                                    $periodoFin = Carbon\Carbon::parse($getConcentrado->periodo_fin);
                                @endphp
                                <span
                                    class="info-box-number text-center text-muted mb-0">{{ $periodoInicio->format('d/m/Y') . ' - ' . $periodoFin->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">
                                    Estado
                                </span>
                                <span
                                    class="info-box-number text-center text-muted mb-0">{{ $getConcentrado->estado }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 col-sm-3">
                        <div class="info-box bg-light">
                            <div class="info-box-content">
                                <span class="info-box-text text-center text-muted">
                                    Realizado el
                                    <b>{{ Carbon\Carbon::parse($getConcentrado->created_at)->format('d/m/Y') }}</b>
                                </span>
                                <span class="info-box-number text-center text-muted mb-0">Por:
                                    {{ $getConcentrado->realiza }} </span>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th style="text-align: center;" style="width: 15%;">FOLIO</th>
                                    <th style="text-align: center;">CURSO</th>
                                    <th style="text-align: center;">CONCEPTO</th>
                                    <th style="text-align: center;">FOLIOS</th>
                                    <th style="text-align: center;">RECIBO DE PAGO</th>
                                    <th style="text-align: center;">IMPORTES</th>
                                    <th style="text-align: center;">COMENTARIOS</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($movimiento as $item)
                                    @php

                                        $depositos = isset($item['depositos'])
                                            ? json_decode($item['depositos'], true)
                                            : [];

                                        $observaciones = isset($item['observaciones'])
                                            ? json_decode($item['observaciones'], true)
                                            : [];

                                        $jsonString = (string) json_encode($observaciones);
                                    @endphp
                                    <tr>
                                        <td style="width: 6em;">{{ $item['folio'] }}</td>
                                        <td>
                                            @if ($item['curso'] != null)
                                                {{ $item['curso'] }}
                                            @else
                                                {{ $item['descripcion'] }}
                                            @endif
                                        </td>
                                        <td>{{ $item['concepto'] }}</td>
                                        <td>
                                            @foreach ($depositos as $k)
                                                {{ $k['folio'] }} &nbsp;
                                            @endforeach
                                        </td>
                                        <td style="text-align: center;">
                                            <a class="nav-link pt-0" href="{{ $pathFile }}{{ $item['documento'] }}"
                                                target="_blank">
                                                <i class="far fa-file-pdf  fa-2x {{ $item['documento'] === null || empty($item['documento']) ? 'text-gray' : 'text-danger' }}"
                                                    title='DESCARGAR RECIBO DE PAGO OFICIALIZADO.'></i>
                                            </a>
                                        </td>
                                        <td style="text-align: end;">
                                            $ {{ number_format($item['importe'], 2, '.', ',') }}
                                        </td>
                                        <td style="text-align:center">
                                            <a href="javascript:;"
                                                class="btn {{ empty($jsonString) || $jsonString === 'null' || $jsonString === '[]' ? 'btn-light' : 'btn-warning' }} openModal"
                                                data-toggle="modal" data-folio="{{ $item['folio'] }}"
                                                data-target="#exampleModal" data-observaciones="{{ $jsonString }}">
                                                <i class="fa fa-comment" aria-hidden="true"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @php
                                        $importeTotal += $item['importe'];
                                    @endphp
                                @endforeach
                                <tr>
                                    <td colspan="5" style="text-align: end;"><b>SUBTOTAL</b></td>
                                    <td style="text-align: end;"><b>$
                                            {{ number_format($importeTotal, 2, '.', ',') }}</b></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4 col-sm-4">
                    </div>
                    <div class="col-4 col-sm-4">
                    </div>
                    <div class="col-4 col-sm-4">
                        <div class="padre">
                            <button class="btn btn-success"><i class="fas fa-signature"></i> Firmar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- incluir modal de inserción --}}
    @include('reportes.rf001.modal.showComment')
@endsection
@section('script_content_js')
    <!-- jQuery Validate -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>


    {{-- Todos estos links se ocupan en prueba y en producción --}}
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/plugins/jquery-3.4.1/jquery-3.4.1.min.js"></script>
    {{-- <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/plugins/jasny-bootstrap4/js/jasny-bootstrap.min.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/plugins/bootstrap-4.3.1/js/bootstrap.min.js"></script> --}}

    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/sjcl.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/sha1_002.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/llave.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/jsbn.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/jsbn2.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/rsa.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/rsa2.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/base64_002.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/crypto-1.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/asn1hex-1.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/rsasign-1.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/x509-1.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/pbkdf2.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/tripledes_002.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/aes.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/rc2.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/asn1.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/base64.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/hex_002.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/yahoo-min.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/hex.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/base64x-1.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/x64-core.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/tripledes.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/core.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/md5.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/sha1.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/sha256.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/ripemd160.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/sha512.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/enc-base64.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/hmac.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/pbkdf2_002.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/cipher-core.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/asn1-1.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/rsapem-1.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-sat/keyutil-1.js"></script>

    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/forge-0.7.1/forge-0.7.1.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-scg/mistake.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-scg/validate.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-scg/access.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-scg/dataSign.js"></script>
    <script src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/utilities-scg/dataTransportSign.js">
    </script>

    {{-- link de prueba signature-spv021_doctos-prueba --}}
    <script
        src="https://firmaelectronica.shyfpchiapas.gob.mx:8443/tools/library/signedjs-2.1/signature-spv021_doctos-prueba.js">
    </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        $(document).ready(function() {
            let folio = '';

            $('.openModal').on('click', function() {
                const folio = $(this).data('folio');
                const observaciones = $(this).data('observaciones');
                // Limpiar el contenido anterior del modal
                $('#observacionesModal').empty();
                $('#folio').val();
                $('#memo').val();
                const code = JSON.stringify(observaciones);
                const comentarios = JSON.parse(code);
                let options = {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric'
                };
                let observacionHTMl = '';
                $("#noFolio").html(folio);
                $("#folio").val(folio);
                $("#memo").val('{{ $getConcentrado->memorandum }}');

                if (comentarios.length > 0) {
                    $.each(comentarios, function(index, observacion) {
                        // Dividir la cadena en componentes de año, mes y día
                        let dateParts = observacion.fecha.split('-');
                        let year = parseInt(dateParts[0], 10);
                        let month = parseInt(dateParts[1], 10) - 1; // Meses en JavaScript son 0-11
                        let day = parseInt(dateParts[2], 10);
                        let date = new Date(year, month, day);
                        let options = {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        };

                        observacionHTMl += '<ul id="comments-list" class="comments-list">' +
                            '<li>' +
                            '<div class="comment-main-level">' +
                            '<div class="comment-box">' +
                            '<div class="comment-head">' +
                            '<h6 class="comment-name by-author">' +
                            '<a>Observación:</a>' +
                            '</h6>' +
                            '<span>' + new Intl.DateTimeFormat('es-ES', options).format(date) +
                            '</span>' +
                            '</div>' +
                            '<div class="comment-content">' +
                            observacion.comentario +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '</li>' +
                            '</ul>';
                    });
                    $('#observacionesModal').html(observacionHTMl);
                } else {
                    $('#observacionesModal').html(
                        '<ul id="comments-list" class="comments-list">' +
                        '<li>' +
                        '<div class="comment-main-level">' +
                        '<div class="comment-box">' +
                        '<div class="comment-head">' +
                        '</div>' +
                        '<div class="comment-content">' +
                        ' NO HAY OBSERVACIONES REGISTRADAS.' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</li>' +
                        '</ul>');
                }

                const form = $("#sendComment");
                form.validate({
                    // debug: true,
                    errorClass: "error",
                    rules: {
                        observacion: "required"
                    },
                    messages: {
                        observacion: {
                            required: "Por favor, agregar comentario."
                        }
                    },
                    highlight: function(element, errorClass) {
                        $(element).addClass(errorClass);
                    },
                    submitHandler: function(form, event) {
                        event.preventDefault();
                        const urlRoute = "{{ route('reporte.rf001.add.comments') }}";
                        let fD = new FormData();
                        fD.append('observacion', $('#observacion').val());
                        fD.append('folio', $('#folio').val());
                        fD.append('memo', $('#memo').val());
                        fD.append('_token', $('input[name=_token]')
                            .val()); // Añadir el token CSRF
                        $.ajax({
                            url: urlRoute,
                            method: "POST",
                            dataType: "json",
                            processData: false,
                            contentType: false,
                            data: fD,
                            beforeSend: function() {
                                $('#sendComment_').attr('disabled',
                                    'disabled');
                                $('#exampleModal').modal('hide');
                            },
                            success: function(response) {
                                if (response.data == 1) {
                                    $('#sendComment')?.trigger("reset");
                                    $(".action-close").trigger(
                                        "click"); // ocultar modal
                                    window.location.href =
                                        "{{ route('reporte.rf001.set.details', ['id' => $id, 'solicitud' => $encrypted]) }}"; // redirect
                                }
                            },
                            error: function(xhr, textStatus, error) {
                                // manejar errores
                                console.log('DATOS1' + xhr.statusText);
                                console.log('DATOS2' + xhr.responseText);
                                console.log('DATOS3' + xhr.status);
                                console.log(textStatus);
                                console.log(error);
                            }
                        });
                        return;
                    }
                });
            });
        });
    </script>
@endsection
