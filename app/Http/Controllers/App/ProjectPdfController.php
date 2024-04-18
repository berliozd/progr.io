<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use function Spatie\LaravelPdf\Support\pdf;


class ProjectPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, int $id)
    {

        return pdf('project-pdf', ['project' => Project::findOrFail($id)]);

//        $pdf = Browsershot::url(route('app.projects.presentation', $id))
//            ->setPaper('a4')
//            ->setOptions([
//                'margin-top' => '10mm',
//                'margin-bottom' => '10mm',
//                'margin-left' => '10mm',
//                'margin-right' => '10mm',
//            ])
//            ->waitUntil('#presentation')
//            ->pdf();
//
//        return response($pdf, 200, [
//            'Content-Type' => 'application/pdf',
//            'Content-Disposition' => 'inline; filename="example.pdf"',
//        ]);

    }
}
