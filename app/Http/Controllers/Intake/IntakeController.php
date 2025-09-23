<?php

namespace App\Http\Controllers\Intake;

use App\Http\Controllers\Controller;
use App\Services\PdfIntakeFormService;
use App\Services\NeonApiService;
use App\Services\NeonDataTransformer;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class IntakeController extends Controller
{
    // public function index()
    // {
    //     return Inertia::render('Intake/Index');
    // }

    public function generatePdf(int $participantId, NeonApiService $neonApi, NeonDataTransformer $transformer, PdfIntakeFormService $pdfService)
    {
        try {
            // (A) Fetch participant data from Neon
            $raw = $neonApi->getParticipant($participantId);
            $participant = $transformer->transformPerson($raw);

            // (B) Generate filled PDF using your service
            $pdfPath = $pdfService->generate($participant);

            // (C) Return PDF as download
            return Storage::download($pdfPath);

        } catch (\Exception $e) {
            return response("Failed to generate PDF: " . $e->getMessage(), 500);
        }
    }

}
