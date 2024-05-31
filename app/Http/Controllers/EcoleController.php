<?php

namespace App\Http\Controllers;

use App\Models\Ecole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class EcoleController extends Controller
{
    // Function to add an ecole
    public function addEcole(Request $request)
    {
        // Custom validation rule to check unique combination of name and type
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'logo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        

        $validator->after(function ($validator) use ($request) {
            $existingEcole = Ecole::where('name', $request->name)
                                  ->where('type', $request->type)
                                  ->first();
            if ($existingEcole) {
                $validator->errors()->add('name', 'The combination of name and type already exists.');
                $validator->errors()->add('type', 'The combination of name and type already exists.');
            }
        });

        if ($validator->fails()) {
            return response()->json(['status' => 'error', 'errors' => $validator->errors()]);
        }

        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $new_name = time() . '-' . $logo->getClientOriginalName();
            $logo->move(public_path('/images/ecoleLogo'), $new_name);
            $logoPath = $new_name;

            $ecole = Ecole::create([
                'name' => $request->name,
                'type' => $request->type,
                'logo' => $logoPath,
            ]);

            return response()->json(['status' => 'success', 'data' => $ecole]);
        }

        return response()->json(['status' => 'error', 'message' => 'Logo upload failed']);
    }

    // Function to select ecoles based on type
    public function showEcoleByType(Request $request)
    {
        $request->validate([
            'type' => 'required',
        ]);

        $ecoles = Ecole::where('type', $request->type)->get();

        if ($ecoles->count() > 0) {
            return response()->json(['status' => 'success', 'data' => $ecoles]);
        }

        return response()->json(['status' => 'error', 'message' => 'No ecoles found']);
    }
}
