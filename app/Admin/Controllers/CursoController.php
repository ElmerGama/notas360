<?php
namespace App\Admin\Controllers;

use App\Models\Curso;
use App\Models\Gestion;
use App\Models\Periodo;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;

class CursoController extends AdminController
{
    /**
     * Configuración del grid para la tabla de cursos.
     */
    public function grid()
    {
        $grid = new Grid(new Curso());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('grado', __('Grado'));
        $grid->column('paralelo', __('Paralelo'));
        $grid->column('nivel', __('Nivel'))->display(function ($nivel) {
            return ucfirst($nivel);
        });
        $grid->column('gestion_id', __('Gestión'))->display(function () {
            return $this->gestion?->nombre_gestion;
        });
        $grid->column('periodo_id', __('Período'))->display(function () {
            return $this->periodo?->nombre_periodo;
        });
        $grid->column('created_at', __('Creado en'))->sortable();
        $grid->column('updated_at', __('Actualizado en'))->sortable();

        return $grid;
    }

    /**
     * Configuración del formulario para crear y editar cursos.
     */
    public function form()
    {
        $form = new Form(new Curso());

        // Campo Nivel
        $form->select('nivel', __('Nivel'))
            ->options([
                'inicial' => 'Inicial',
                'primaria' => 'Primaria',
                'secundaria' => 'Secundaria',
            ])
            ->required();

        // Campo Grado
        $form->select('grado', __('Grado'))
            ->options([
                'Primero' => 'Primero',
                'Segundo' => 'Segundo',
                'Tercero' => 'Tercero',
                'Cuarto' => 'Cuarto',
                'Quinto' => 'Quinto',
                'Sexto' => 'Sexto',
            ])
            ->required();

        // Campo Paralelo
        $form->select('paralelo', __('Paralelo'))
            ->options([
                'Plomo' => 'Plomo',
                'Naranja' => 'Naranja',
                'Amarillo' => 'Amarillo',
                'Verde' => 'Verde',
            ])
            ->required();

        // Campo Gestión
        $form->select('gestion_id', __('Gestión'))
            ->options(Gestion::pluck('nombre_gestion', 'id'))
            ->required();

        // Campo Período
        $form->select('periodo_id', __('Período'))
            ->options(Periodo::pluck('nombre_periodo', 'id'))
            ->required();

        return $form;
    }
}
