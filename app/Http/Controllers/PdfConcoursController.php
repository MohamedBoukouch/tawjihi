<?php

namespace App\Http\Controllers;

use App\Models\Concours;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PdfConcoursController extends Controller
{
    // Function to add a concours
    public function addConcours(Request $request)
    {
        $request->validate([
            'annee_scolaire' => 'required',
            'pdf' => 'required|mimes:pdf|max:2048', // Adjust file validation as needed
            'niveau' => 'required',
            'ecole_id' => 'required|exists:ecoles,id',
            'ville_id' => 'required|exists:ecolevilles,id',
        ]);
    
        if ($request->hasFile('pdf')) {
            $pdf = $request->file('pdf');
            $new_name =uniqid() . '.' . $pdf->getClientOriginalExtension(); // Generate unique filename
            $pdfPath = $pdf->move(public_path('/images/concours'), $new_name); // Store the file in public/images/concours
    
            $concours = Concours::create([
                'annee_scolaire' => $request->annee_scolaire,
                'pdf' => $new_name, // Store the generated filename in database
                'niveau' => $request->niveau,
                'ecole_id' => $request->ecole_id,
                'ville_id' => $request->ville_id,
            ]);
    
            return response()->json(['status' => 'success', 'data' => $concours]);
        }
    
        return response()->json(['status' => 'error', 'message' => 'PDF upload failed']);
    }

    // Function to select concours by type and ecole
    public function getConcoursByTypeAndEcole(Request $request)
    {
        $request->validate([
            'niveau' => 'required',
            'ecole_id' => 'required|exists:ecoles,id',
            'ville_id' => 'required|exists:ecolevilles,id',
        ]);

        $concours = Concours::with(['ecole', 'ville'])
                           ->where('niveau', $request->niveau)
                           ->where('ecole_id', $request->ecole_id)
                           ->where('ville_id', $request->ville_id)
                           ->get();

        if ($concours->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No concours found for the given criteria.']);
        }

        return response()->json(['status' => 'success', 'data' => $concours]);
    }
}
