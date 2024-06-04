<?php

namespace App\Http\Controllers;

use App\Models\Ecoleville;
use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EcolevilleController extends Controller
{
    public function addEcoleville(Request $request)
    {
        // Custom validation rule to check unique combination of ville and type
        $validator = Validator::make($request->all(), [
            'ville' => 'required',
            'type' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ecole_id' => 'required|exists:ecoles,id',
        ]);

        $validator->after(function ($validator) use ($request) {
            $existingEcoleville = Ecoleville::where('ville', $request->ville)
                                            ->where('type', $request->type)
                                            ->first();
            if ($existingEcoleville) {
                $validator->errors()->add('ville', 'The combination of ville and type already exists.');
                $validator->errors()->add('type', 'The combination of ville and type already exists.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $new_name = rand() . '.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('/images/ecolevilleLogo'), $new_name);
            $logoPath = $new_name;

            $ecoleville = Ecoleville::create([
                'ville' => $request->ville,
                'type' => $request->type,
                'logo' => $logoPath,
                'ecole_id' => $request->ecole_id,
            ]);

            return response()->json(['status' => 'success', 'data' => $ecoleville]);
        }

        return response()->json(['status' => 'error', 'message' => 'Logo upload failed']);
    }

    // Function to select ecoleville by type and ecole
    public function getEcolevillesByTypeAndEcole(Request $request)
    {
        $request->validate([
            'type' => 'required',
            'ecole_id' => 'required|exists:ecoles,id',
        ]);

        $ecolevilles = Ecoleville::with('ecole')
                                  ->where('type', $request->type)
                                  ->where('ecole_id', $request->ecole_id)
                                  ->get();

        if ($ecolevilles->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No ecoleville found for the given type and ecole.']);
        }

        return response()->json(['status' => 'success', 'data' => $ecolevilles->map(function ($ecoleville) {
            return [
                'id_ville' => $ecoleville->id,
                'ville' => $ecoleville->ville,
                'type' => $ecoleville->type,
                'logo' => $ecoleville->logo,
                'ecole' => $ecoleville->ecole_name, // This should now correctly fetch the ecole name
            ];
        })]);
    }
}
