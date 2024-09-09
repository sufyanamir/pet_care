<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\AnimalBreed;
use Illuminate\Http\Request;

class BreedController extends Controller
{
    public function getBreeds()
    {

        $animals = Animal::get();

        $breeds = AnimalBreed::with('animal')->get();

        return view('breed', ['animals' => $animals, 'breeds' => $breeds]);
    }

    public function addBreed(Request $request)
    {
        try {
            
            $user = session('user_details');

            if ($request->input('breed_id') != null) {

                $validatedData = $request->validate([
                    'breed_id' => 'required',
                    'animal_id' => 'required',
                    'animalBreed' => 'required',
                ]);


                $breed = AnimalBreed::where('breed_id', $validatedData['breed_id'])->first();

                if (!$breed) {
                    return response()->json(['success' => false, 'message' => 'Breed not found!'], 404);
                }

                if ($request->hasFile('breedImage')) {
                    // Get the path of the image from the animal record
                    $imagePath = public_path($breed->breed_image); // Get the full image path

                    // Delete the image file if it exists
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the image from the file system
                    }

                    $image = $request->file('breedImage');
                    // Store the image in the 'animal_images' folder and get the file path
                    $imagePath = $image->store('breed_images', 'public'); // stored in 'storage/app/public/animal_images'
                    $imageFullPath = 'storage/' . $imagePath;
                    $breed->breed_image = $imageFullPath;
                }

                $breed->breed_name = $validatedData['animalBreed'];
                $breed->animal_id = $validatedData['animal_id'];

                $breed->save();

                return response()->json(['success' => true, 'message' => 'Breed updated!'], 200);
            } else {
                $validatedData = $request->validate([
                    'animal_id' => 'required',
                    'animalBreed' => 'required',
                    'breedImage' => 'required',
                ]);
    
                if ($request->hasFile('breedImage')) {
                    $image = $request->file('breedImage');
                    // Store the image in the 'animal_images' folder and get the file path
                    $imagePath = $image->store('breed_images', 'public'); // stored in 'storage/app/public/animal_images'
                    $imageFullPath = 'storage/' . $imagePath;
                } else {
                    $imageFullPath = NULL;
                }
    
                $breed = AnimalBreed::create([
                    'added_user_id' => $user['id'],
                    'animal_id' => $validatedData['animal_id'],
                    'breed_name' => $validatedData['animalBreed'],
                    'breed_image' => $imageFullPath,
                ]);
    
                return response()->json(['success' => true, 'message' => 'Breed added successfully!'], 200);
            }

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function deleteBreed($id)
    {
        try {

            $breed = AnimalBreed::where('breed_id', $id)->first();

            if (!$breed) {
                return response()->json(['success' => false, 'message' => 'Breed not found!'], 404);
            }

            // Get the path of the image from the animal record
            $imagePath = public_path($breed->breed_image); // Get the full image path

            // Delete the image file if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image from the file system
            }

            // Delete the animal record from the database
            $breed->delete();

            return response()->json(['success' => true, 'message' => 'Breed Deleted!'], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

}
