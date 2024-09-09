<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    public function getAnimals()
    {

        $animals = Animal::get();

        return view('animals', ['animals' => $animals]);
    }

    public function addAnimal(Request $request)
    {
        try {

            $user = session('user_details');

            if ($request->input('animal_id') != null) {
                $validatedData = $request->validate([
                    'animal_id' => 'nullable',
                    'animalName' => 'required',
                ]);


                $animal = Animal::where('animal_id', $validatedData['animal_id'])->first();

                if (!$animal) {
                    return response()->json(['success' => false, 'message' => 'Animal not found!'], 404);
                }

                if ($request->hasFile('animalImage')) {
                    // Get the path of the image from the animal record
                    $imagePath = public_path($animal->animal_image); // Get the full image path

                    // Delete the image file if it exists
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the image from the file system
                    }

                    $image = $request->file('animalImage');
                    // Store the image in the 'animal_images' folder and get the file path
                    $imagePath = $image->store('animal_images', 'public'); // stored in 'storage/app/public/animal_images'
                    $imageFullPath = 'storage/' . $imagePath;
                    $animal->animal_image = $imageFullPath;
                }

                $animal->animal_name = $validatedData['animalName'];

                $animal->save();

                return response()->json(['success' => true, 'message' => 'Animal updated!'], 200);
            } else {
                $validatedData = $request->validate([
                    'animal_id' => 'nullable',
                    'animalName' => 'required',
                    'animalImage' => 'required',
                ]);
                // Handle the image upload
                if ($request->hasFile('animalImage')) {
                    $image = $request->file('animalImage');
                    // Store the image in the 'animal_images' folder and get the file path
                    $imagePath = $image->store('animal_images', 'public'); // stored in 'storage/app/public/animal_images'
                    $imageFullPath = 'storage/' . $imagePath;
                } else {
                    $imageFullPath = NULL;
                }

                // Create the animal entry in the database
                $animal = Animal::create([
                    'added_user_id' => $user['id'],
                    'animal_name' => $validatedData['animalName'],
                    'animal_image' => $imageFullPath, // Store the relative path without the URL
                ]);

                return response()->json(['success' => true, 'message' => 'Animal added successfully!'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function deleteAnimal($id)
    {
        try {

            $animal = Animal::where('animal_id', $id)->first();

            if (!$animal) {
                return response()->json(['success' => false, 'message' => 'Animal not found!'], 404);
            }

            // Get the path of the image from the animal record
            $imagePath = public_path($animal->animal_image); // Get the full image path

            // Delete the image file if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image from the file system
            }

            // Delete the animal record from the database
            $animal->delete();

            return response()->json(['success' => true, 'message' => 'Animal Deleted!'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
}
