<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Slider;

class SliderController extends Controller
{
    // Function to add a new slider
    public function addSlider(Request $request)
    {
        $request->validate([
            'titel1' => 'required|string',
            'titel2' => 'required|string',
            'link' => 'nullable|string',
            'titel_link' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slider = new Slider();
        $slider->titel1 = $request->titel1;
        $slider->titel2 = $request->titel2;
        $slider->link = $request->link;
        $slider->titel_link = $request->titel_link;

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->storeAs('public/sliders', $imageName);
            $slider->image = $imageName;
        }

        $slider->save();

        return response()->json(['status' => 'success', 'data' => $slider]);
    }

    // Function to delete a slider
    public function deleteSlider(Request $request)
    {
        $request->validate([
            'id' => 'required|integer', // Assuming 'id' is the identifier of the slider to delete
        ]);
    
        $slider = Slider::find($request->id);
    
        if (!$slider) {
            return response()->json(['status' => 'error', 'message' => 'Slider not found'], 404);
        }
    
        $deleted = $slider->delete();
    
        if ($deleted) {
            return response()->json(['status' => 'success']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Failed to delete slider']);
        }
    }

    // Function to fetch all sliders
    public function fetchSliders(Request $request)
    {
        $sliders = Slider::all();

        if ($sliders->count() > 0) {
            return response()->json(['status' => 'success', 'data' => $sliders]);
        } else {
            return response()->json(['status' => 'error']);
        }
    }

    // Function to update a slider
    public function updateSlider(Request $request)
{
    $request->validate([
        'id' => 'required',
        'titel1' => 'required|string',
        'titel2' => 'required|string',
        'link' => 'nullable|string',
        'titel_link' => 'nullable|string',
    ]);

    $slider = Slider::find($slider->id);

    if (!$slider) {
        return response()->json(['status' => 'error', 'message' => 'Slider not found'], 404);
    }

    $slider->titel1 = $request->titel1;
    $slider->titel2 = $request->titel2;
    $slider->link = $request->link;
    $slider->titel_link = $request->titel_link;

    $updated = $slider->save();

    if ($updated) {
        return response()->json(['status' => 'success']);
    } else {
        return response()->json(['status' => 'error', 'message' => 'Failed to update slider']);
    }
}
}
