<?php

namespace App\Admin\Actions;
use OpenAdmin\Admin\Actions\GridAction;
use Illuminate\Http\Request;
use Dompdf\Dompdf;
use App\Models\Periodo;

class ExportPdfAction extends GridAction
{
    public $name = 'Exportar PDF';

    public function handle(Request $request)
    {
        // Obtener los datos de todos los períodos
        $periodos = Periodo::all();

        // Crear el contenido HTML para el PDF
        $html = view('admin.pdf.periodos', compact('periodos'))->render();

        // Inicializar Dompdf
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Devolver el PDF para descargar
        return response($dompdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="periodos.pdf"');
    }

    public function dialog()
    {
        $this->confirm('¿Estás seguro de que deseas exportar todos los períodos como PDF?', 'Exportar PDF');
    }
}
