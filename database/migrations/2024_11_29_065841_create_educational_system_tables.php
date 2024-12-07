<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Modificar tabla admin_users creada por OpenAdmin
        Schema::table('admin_users', function (Blueprint $table) {
            $table->string('nombre_completo')->nullable();
            $table->string('profesion')->nullable();
            $table->string('grado_academico')->nullable(); // Grado académico en varchar
            $table->string('celular', 15)->nullable();
            $table->boolean('estado')->default(1); // 1: Activo, 0: Inactivo
        });

        // Tabla gestiones
        Schema::create('gestiones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_gestion');
            $table->boolean('estado')->default(1); // 1: Abierto, 0: Cerrado
            $table->timestamps();
        });

        // Tabla periodos
        Schema::create('periodos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gestion_id')->constrained('gestiones');
            $table->string('nombre_periodo');
            $table->boolean('estado')->default(0); // 0: Abierto, 1: Cerrado
            $table->timestamps();
        });

        // Tabla tutores
        Schema::create('tutores', function (Blueprint $table) {
            $table->id();
            $table->string('cedula_identidad', 15)->unique();
            $table->string('nombre_completo');
            $table->string('profesion_ocupacion')->nullable();
            $table->enum('grado_parentesco', ['madre', 'padre', 'abuelo', 'abuela', 'tio', 'tia', 'hermano', 'hermana', 'tutor', 'tutora']);
            $table->string('celular', 15)->nullable();
            $table->string('correo_electronico')->unique();
            $table->timestamps();
        });

        // Tabla estudiantes
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_rude')->unique();
            $table->string('cedula_identidad', 15)->unique();
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable();
            $table->string('nombres');
            $table->enum('genero', ['masculino', 'femenino']);
            $table->date('fecha_nacimiento');
            $table->string('nacionalidad');
            $table->foreignId('tutor_id')->constrained('tutores');
            $table->timestamps();
        });

        // Tabla configuraciones_notas
        Schema::create('configuraciones_notas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_configuracion');
            $table->float('ser')->default(0);
            $table->float('saber')->default(0);
            $table->float('hacer')->default(0);
            $table->float('decidir')->default(0);
            $table->float('autoevaluacion')->default(0);
            $table->float('total')->default(100);
            $table->timestamps();
        });

        // Tabla cursos
        Schema::create('cursos', function (Blueprint $table) {
            $table->id();
            $table->enum('grado', ['primero', 'segundo', 'tercero', 'cuarto', 'quinto', 'sexto']);
            $table->string('paralelo');
            $table->enum('nivel', ['inicial', 'primaria', 'secundaria']);
            $table->foreignId('gestion_id')->constrained('gestiones');
            $table->foreignId('periodo_id')->constrained('periodos');
            $table->timestamps();
        });

        // Tabla grupos_materias
        Schema::create('grupos_materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_grupo');
            $table->foreignId('gestion_id')->constrained('gestiones');
            $table->foreignId('periodo_id')->constrained('periodos');
            $table->foreignId('curso_id')->constrained('cursos');
            $table->timestamps();
        });

        // Tabla materias
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_materia');
            $table->foreignId('gestion_id')->constrained('gestiones');
            $table->foreignId('grupo_materia_id')->constrained('grupos_materias');
            $table->float('ponderado')->default(0);
            $table->timestamps();
        });

// Tabla asignacion_docente
Schema::create('asignacion_docente', function (Blueprint $table) {
    $table->id();
    $table->unsignedInteger('usuario_id'); // Cambiado a unsignedInteger para ser compatible con admin_users.id
    $table->foreign('usuario_id')->references('id')->on('admin_users')->onDelete('cascade');
    $table->foreignId('gestion_id')->constrained('gestiones');
    $table->foreignId('periodo_id')->constrained('periodos');
    $table->foreignId('curso_id')->constrained('cursos');
    $table->foreignId('materia_id')->constrained('materias');
    $table->timestamps();
});

        // Tabla filiaciones
        Schema::create('filiaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('estudiante_id')->constrained('estudiantes');
            $table->foreignId('gestion_id')->constrained('gestiones');
            $table->foreignId('periodo_id')->constrained('periodos');
            $table->foreignId('curso_id')->constrained('cursos');
            $table->boolean('estado')->default(1); // 1: Efectivo, 0: Retirado
            $table->timestamps();
        });

        // Tabla criterios
        Schema::create('criterios', function (Blueprint $table) {
            $table->id();
            $table->enum('dimension', ['ser', 'saber', 'hacer', 'decidir']);
            $table->string('descripcion');
            $table->foreignId('gestion_id')->constrained('gestiones');
            $table->foreignId('periodo_id')->constrained('periodos');
            $table->foreignId('curso_id')->constrained('cursos');
            $table->foreignId('materia_id')->constrained('materias');
            $table->unsignedInteger('usuario_id'); // Cambiado a unsignedInteger para ser compatible con admin_users.id
            $table->foreign('usuario_id')->references('id')->on('admin_users')->onDelete('cascade');
            $table->timestamps();
        });

        // Tabla calificaciones
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('filiacion_id')->constrained('filiaciones');
            $table->foreignId('materia_id')->constrained('materias');
            $table->json('notas');
            $table->float('promedio_trimestral')->default(0);
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
        Schema::dropIfExists('calificaciones');
        Schema::dropIfExists('criterios');
        Schema::dropIfExists('filiaciones');
        Schema::dropIfExists('asignacion_docente');
        Schema::dropIfExists('materias');
        Schema::dropIfExists('grupos_materias');
        Schema::dropIfExists('cursos');
        Schema::dropIfExists('configuraciones_notas');
        Schema::dropIfExists('estudiantes');
        Schema::dropIfExists('tutores');
        Schema::dropIfExists('periodos');
        Schema::dropIfExists('gestiones');
        Schema::table('admin_users', function (Blueprint $table) {
            $table->dropColumn(['nombre_completo', 'profesion', 'grado_academico', 'celular', 'estado']);
        });
    }

};
