<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalBreed;
use App\Models\Feeds;
use App\Models\Pet;
use App\Models\PetImages;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isEmpty;

class ApiController extends Controller
{

    // user Defined
    protected function errorResponse(Exception $e, $code = 400): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], $code);
    }
    // user Defined

    // update user details
    public function updateUserDetails(Request $request)
    {
        try {
            $user = Auth::user();

            $validatedData = $request->validate([
                'fullName' => 'nullable',
                'phone' => 'nullable',
                'email' => 'nullable',
                'address' => 'nullable',
                'password' => 'nullable',
            ]);

            $updateUser = User::where('id', $user->id)->first();

            if (isset($validatedData['fullName']) && !empty($validatedData['fullName'])) {
                $updateUser->name = $validatedData['fullName'];
            }
            if (isset($validatedData['phone']) && !empty($validatedData['phone'])) {
                $updateUser->phone = $validatedData['phone'];
            }
            if (isset($validatedData['email']) && !empty($validatedData['email'])) {
                $updateUser->email = $validatedData['email'];
            }
            if (isset($validatedData['address']) && !empty($validatedData['address'])) {
                $updateUser->address = $validatedData['address'];
            }
            if (isset($validatedData['password']) && !empty($validatedData['password'])) {
                $updateUser->password = $validatedData['password'];
            }

            $updateUser->save();

            return response()->json(['success' => true, 'message' => 'Profile Updated'], 200);

        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
    // update user details

    // get feeds
    public function getFeed()
    {
        $feed = Feeds::get();

        if ($feed->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No feed found', 'data' => []]);
        }

        return response()->json(['success' => true, 'data' => $feed], 200);
    }
    // get feeds

    // add feeds
    public function addFeed(Request $request)
    {
        try {

            $user = Auth::user();

            $validatedData = $request->validate([
                'pet_id' => 'required',
                'feed_post' => 'required',
                'post_desc' => 'nullable',
            ]);

            if ($request->hasFile('feed_post')) {
                $image = $request->file('feed_post');
                // Store the image in the 'animal_images' folder and get the file path
                $imagePath = $image->store('feed_posts', 'public'); // stored in 'storage/app/public/animal_images'
                $imageFullPath = 'storage/' . $imagePath;
            } else {
                $imageFullPath = NULL;
            }

            $feed = Feeds::create([
                'added_user_id' => $user->id,
                'pet_id' => $validatedData['pet_id'],
                'feed_post' => $imageFullPath,
                'post_desc' => $validatedData['post_desc'],
            ]);

            return response()->json(['success' => true, 'message' => 'Posts created successfully'], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
    // add feeds

    // add pet images
    public function addPetImages(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            // Validate that 'pet_id' is required and 'pet_image' is an array of required images
            $validatedData = $request->validate([
                'pet_id' => 'required',  // Assuming you have a pets table
                'pet_image.*' => 'required',  // Multiple image validation
            ]);

            $petId = $validatedData['pet_id'];
            $images = $request->file('pet_image');  // Get the array of images

            $imagePaths = [];

            foreach ($images as $image) {
                // Store each image and get the path
                $imagePath = $image->store('pet_images', 'public'); // stored in 'storage/app/public/animal_images'
                $imageFullPath[] = 'storage/' . $imagePath;

                // Optionally save image paths to a database table
                PetImages::create([
                    'added_user_id' => $user->id,
                    'pet_id' => $petId,
                    'pet_image' => $imageFullPath,
                ]);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Images uploaded successfully!',
                'image_paths' => $imagePaths,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }
    // add pet images

    // get Pet Details
    public function getPetDetails($id, $key = null)
    {
        try {
            if ($key == 'about') {

                $pet = Pet::with(['animal', 'breed'])->where('pet_id', $id)->first();

                if (is_null($pet)) {
                    return response()->json(['success' => false, 'message' => 'Pet not found', 'data' => []], 404);
                }
            } elseif ($key == 'photos') {
                $pet = PetImages::where('pet_id', $id)->first();

                if ($pet->isEmpty()) {
                    return response()->json(['success' => false, 'message' => 'Pet not found', 'data' => []], 404);
                }
            }

            return response()->json(['success' => true, 'data' => $pet], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
    // get Pet Details

    // get Pet
    public function getPets()
    {
        try {

            $user = Auth::user();

            $pets = Pet::with([
                'animal:animal_id,animal_name',
                'breed:breed_id,breed_name'
            ])
                ->select('pet_id', 'animal_id', 'breed_id', 'pet_name', 'pet_age', 'pet_gender', 'pet_image')
                ->where('added_user_id', $user->id)
                ->get();

            if ($pets->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'No pets found', 'data' => []], 404);
            }

            return response()->json(['success' => true, 'data' => $pets], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
    // get Pet

    // add Pet
    public function addPet(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();

            $validatedData = $request->validate([
                'animal_id' => 'required',
                'breed_id' => 'required',
                'pet_name' => 'required',
                'pet_age' => 'required',
                'pet_gender' => 'required',
                'pet_height' => 'required',
                'pet_weight' => 'required',
                'pet_variation' => 'required',
                'pet_apearance_desc' => 'nullable',
                'pet_nature_desc' => 'nullable',
                'pet_image' => 'required',
                'check_dob' => 'nullable',
                'check_feed' => 'nullable',
                'pet_dob' => 'nullable',
            ]);

            if ($request->hasFile('pet_image')) {
                $image = $request->file('pet_image');
                // Store the image in the 'animal_images' folder and get the file path
                $imagePath = $image->store('pet_images', 'public'); // stored in 'storage/app/public/animal_images'
                $imageFullPath = 'storage/' . $imagePath;
            } else {
                $imageFullPath = NULL;
            }

            $pet = Pet::create([
                'added_user_id' => $user->id,
                'animal_id' => $validatedData['animal_id'],
                'breed_id' => $validatedData['breed_id'],
                'pet_name' => $validatedData['pet_name'],
                'pet_age' => $validatedData['pet_age'],
                'pet_gender' => $validatedData['pet_gender'],
                'pet_height' => $validatedData['pet_height'],
                'pet_weight' => $validatedData['pet_weight'],
                'pet_variation' => $validatedData['pet_variation'],
                'pet_apearance_desc' => $validatedData['pet_apearance_desc'],
                'pet_nature_desc' => $validatedData['pet_nature_desc'],
                'pet_image' => $imageFullPath,
                'check_dob' => $validatedData['check_dob'],
                'check_feed' => $validatedData['check_feed'],
                'pet_dob' => $validatedData['pet_dob'],
            ]);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pet added successfully', 'data' => $pet], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }
    // add Pet

    // get Breed
    public function getBreed($id)
    {
        $breed = AnimalBreed::where('animal_id', $id)->get();

        if ($breed->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Breed not found', 'data' => []], 404);
        }

        return response()->json(['success' => true, 'data' => $breed], 200);
    }
    // get Breed

    // get Animals
    public function getAnimals()
    {
        $animals = Animal::get();

        if ($animals->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Animal not found', 'data' => []], 404);
        }

        return response()->json(['success' => true, 'data' => $animals], 200);
    }
    // get Animals

    // Login
    public function login(Request $request)
    {
        try {
            $email = $request->input('email');
            $password = $request->input('password');
            $user = User::where('email', $email)->first();

            if (!$user || !Hash::check($password, $user->password)) {
                return response()->json(['success' => false, 'message' => 'Invalid credentials'], 401);
            }

            // Generate a personal access token for the user
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json(['success' => true, 'message' => 'Login successful!', 'access_token' => $token, 'user_details' => $user], 200);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }
    // Login

    // Register
    public function register(Request $request)
    {
        try {
            DB::beginTransaction();
            $validatedData = $request->validate([
                'fullName' => 'required',
                'phone' => 'nullable',
                'email' => 'required',
                'address' => 'nullable',
                'password' => 'required',
            ]);

            $existingUser = User::where('email', $validatedData['email'])->first();

            if ($existingUser) {
                return response()->json(['success' => false, 'message' => 'Email already in use'], 400);
            }

            $user = User::create([
                'name' => $validatedData['fullName'],
                'phone' => $validatedData['phone'],
                'email' => $validatedData['email'],
                'address' => $validatedData['address'],
                'password' => $validatedData['password'],
            ]);
            DB::commit();
            return response()->json(['success' => true, 'message' => 'Registration Completed'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }
    // Register

    // get User
    public function getUserDetails()
    {
        $user = Auth::user();

        return response()->json(['success' => true, 'message' => 'user get successfully', 'data' => $user]);
    }
    // get User
}
