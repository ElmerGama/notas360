<?php
namespace App\Admin\Controllers;

use App\Models\Gestion;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Layout\Content;

class GestionController extends AdminController
{
    protected $title = "Gestiones";

    // Método index modificado para cumplir con la firma
    public function index(Content $content)
    {
        return $content
            ->title($this->title)
            ->description('Listado de Gestiones')
            ->body($this->grid());
    }

    protected function grid()
    {
        $grid = new Grid(new Gestion());
        $grid->column('id', __('ID'))->sortable();
        $grid->column('nombre_gestion', __('Nombre Gestión'));
        $grid->column('estado', __('Estado'))->switch();
        $grid->column('created_at', __('Creado'))->date('Y-m-d H:i:s');
        $grid->column('updated_at', __('Actualizado'))->date('Y-m-d H:i:s');
        return $grid;
    }

    protected function detail($id)
    {
        $show = new Show(Gestion::findOrFail($id));
        $show->field('id', __('ID'));
        $show->field('nombre_gestion', __('Nombre Gestión'));
        $show->field('estado', __('Estado'))->using([1 => 'Abierto', 0 => 'Cerrado']);
        $show->field('created_at', __('Creado'));
        $show->field('updated_at', __('Actualizado'));
        return $show;
    }

    protected function form()
    {
        $form = new Form(new Gestion());
        $form->text('nombre_gestion', __('Nombre Gestión'))->rules('required');
        $form->switch('estado', __('Estado'))->default(1);
        return $form;
    }
}