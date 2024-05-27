<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Enums\Format;
use Spatie\LaravelPdf\Enums\Unit;
use function Spatie\LaravelPdf\Support\pdf;


class ProjectPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $id)
    {

        $project = Project::findOrFail($id);
        $data = ['project' => $project];
        $viewTemplate = 'project-pdf';

        if ($request->get('view')) {
            return view($viewTemplate, $data);
        }
        return pdf($viewTemplate, $data)
            ->landscape()
            ->format(Format::A4)
            ->margins(20, 20, 20, 20, Unit::Pixel);
    }
}
