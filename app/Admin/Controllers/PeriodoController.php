<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\ExportPdfAction;
use Dompdf\Dompdf;
use App\Admin\Controllers\Controller;
use App\Models\Periodo;
use App\Models\Gestion;
use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;

class PeriodoController extends AdminController
{
    protected $title = 'Períodos';

    /**
     * Configurar la vista en formato grid para listar los períodos.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Periodo());

        $grid->column('id', __('ID'))->sortable();
        $grid->column('gestion_id', __('Gestión'))->display(function ($gestionId) {
            return Gestion::find($gestionId)?->nombre_gestion;
        });
        $grid->column('nombre_periodo', __('Nombre Período'));
        $grid->column('estado', __('Estado'))->switch();
        //$grid->column('created_at', __('Creado en'));
       // $grid->column('updated_at', __('Actualizado en'));


       $grid->tools(function ($tools) {
        $tools->append('<a href="'.route('admin.periodos.export.pdf').'" class="btn btn-sm btn-danger me-1" target="_blank">Exportar PDF</a>');
    });
    
        return $grid;
    }

    /**
     * Configurar la vista de detalles para un período.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Periodo::findOrFail($id));

        $show->field('id', __('ID'));
        $show->field('gestion_id', __('Gestión'))->as(function ($gestionId) {
            return Gestion::find($gestionId)?->nombre_gestion;
        });
        $show->field('nombre_periodo', __('Nombre Período'));
        $show->field('estado', __('Estado'))->using([0 => 'Abierto', 1 => 'Cerrado']);
        $show->field('created_at', __('Creado en'));
        $show->field('updated_at', __('Actualizado en'));

        return $show;
    }

    /**
     * Configurar el formulario de creación y edición de períodos.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Periodo());

        $form->select('gestion_id', __('Gestión'))->options(Gestion::all()->pluck('nombre_gestion', 'id'))->required();
        $form->text('nombre_periodo', __('Nombre Período'))->required();
        $form->switch('estado', __('Estado'))->default(0);

        return $form;
    }

    public function exportPdf()
    {
        $periodos = Periodo::all();

        // Ruta a la imagen del logo
        $path = public_path('images/logo.png');

        // Leer la imagen y convertirla a base64
        $logo = '';
        if (file_exists($path)) {
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $logo = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        // Crear el contenido HTML para el PDF, pasando la variable $logo a la vista
        $html = view('periodos', compact('periodos', 'logo'))->render();

        // Inicializar Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Generar y devolver el PDF
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="periodos.pdf"')
            ->header('Cache-Control', 'public, must-revalidate, max-age=0')
            ->header('Pragma', 'public')
            ->header('Expires', '0');
    }

}
