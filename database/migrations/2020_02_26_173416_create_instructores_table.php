<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructores', function (Blueprint $table) {
            $table->string('numero_control', 30)->unique();
            $table->primary('numero_control');
            $table->string('nombre', 250);
            $table->string('tipo_honorario', 25);
            $table->string('folio_documento', 25);
            $table->text('experencia_laboral');
            $table->text('experencia_docente');
            $table->string('cursos_recibidos', 255);
            $table->boolean('capacitados_icatech')->nullable();
            $table->string('curso_recibido_icatech', 255);
            $table->string('cursos_impartidos', 255);
            $table->string('registro_agente_capacitador_externo', 150);
            $table->string('rfc', 50);
            $table->string('curp', 50);
            $table->char('sexo', 5);
            $table->string('estado_civil', 100);
            $table->date('fecha_nacimiento');
            $table->string('entidad', 80);
            $table->string('municipio', 80);
            $table->string('asentamiento', 80);
            $table->string('domicilio', 200);
            $table->integer('telefono');
            $table->string('correo', 100);
            $table->string('unidad_capacitacion_solicita_validacion_instructor', 100);
            $table->string('memoramdum_validacion', 80);
            $table->date('fecha_validacicon');
            $table->text('observaciones');
            $table->boolean('cursos_conocer')->nullable();
            $table->string('modificacion_memo', 80);
            $table->string('banco', 80);
            $table->string('no_cuenta', 30);
            $table->string('interbancaria', 80);
            $table->string('folio_ine', 80);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('instructores');
    }
}
