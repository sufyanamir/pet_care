<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Animal;
use App\Models\AnimalBreed;
use App\Models\Feeds;
use App\Models\Pet;
use App\Models\PetImages;
use App\Models\Reminders;
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

    // delete reminder
    public function deleteReminder(Request $request)
    {
        try {
            $user = Auth::user();
            $validatedData = $request->validate([
                'reminder_id' => 'required',
            ]);

            $reminder = Reminders::where('reminder_id', $validatedData['reminder_id'])->where('added_user_id', $user->id)->first();
            $reminder->delete();

            return response()->json(['success' => true, 'message' => 'Reminder deleted'], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
    // delete reminder

    // get reminder
    public function getReminder()
    {
        $user = Auth::user();
        $reminders = Reminders::where('added_user_id', $user->id)->get();

        if ($reminders->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'No reminders found', 'data' => []]);
        }

        // Format each reminder's date and time
        $formattedReminders = $reminders->map(function ($reminder) {
            $reminder->reminder_date = \DateTime::createFromFormat('Y-m-d', $reminder->reminder_date)->format('d/m/Y');
            $reminder->reminder_time = \DateTime::createFromFormat('H:i:s', $reminder->reminder_time)->format('h:i A');
            return $reminder;
        });

        return response()->json(['success' => true, 'data' => $formattedReminders], 200);
    }
    // get reminder

    // add reminder
    public function addReminder(Request $request)
    {
        try {
            DB::beginTransaction();
            $user = Auth::user();
            $reminderId = $request->input('reminder_id');

            // Validate request data
            $validatedData = $request->validate([
                'reminder_title' => 'required',
                'reminder_date' => 'required',
                'reminder_time' => 'required',
                'reminder_icon' => 'nullable',
            ]);

            // Convert date to YYYY-MM-DD and time to 24-hour format
            $formattedDate = \DateTime::createFromFormat('d/m/Y', $validatedData['reminder_date'])->format('Y-m-d');
            $formattedTime = \DateTime::createFromFormat('h:i A', $validatedData['reminder_time'])->format('H:i:s');

            if ($reminderId != null) {
                $reminder = Reminders::where('reminder_id', $reminderId)->first();
                $reminder->reminder_title = $validatedData['reminder_title'];
                $reminder->reminder_date = $formattedDate;
                $reminder->reminder_time = $formattedTime;
                $reminder->reminder_icon = $validatedData['reminder_icon'];
                $reminder->save();
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Reminder updated successfully'], 200);
            } else {
                $reminder = Reminders::create([
                    'added_user_id' => $user->id,
                    'reminder_title' => $validatedData['reminder_title'],
                    'reminder_date' => $formattedDate,
                    'reminder_time' => $formattedTime,
                    'reminder_icon' => $validatedData['reminder_icon'],
                ]);
                DB::commit();
                return response()->json(['success' => true, 'message' => 'Reminder added successfully'], 200);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }
    // add reminder

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

            if ($request->hasFile('user_image')) {
                $image = $request->file('user_image');
                // Store the image in the 'animal_images' folder and get the file path
                $imagePath = $image->store('user_images', 'public'); // stored in 'storage/app/public/animal_images'
                $imageFullPath = 'storage/' . $imagePath;
                $updateUser->user_image = $imageFullPath;
            }

            $updateUser->save();

            return response()->json(['success' => true, 'message' => 'Profile Updated'], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
    // update user details

    // like feeds
    public function likeFeed(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'feed_id' => 'required',
                'key' => 'required',
            ]);

            $feed = Feeds::where('feed_id', $validatedData['feed_id'])->first();

            if ($validatedData['key'] == 'like') {
                $feed->feed_likes += 1;
                $feed->save();

                return response()->json(['success' => true, 'message' => 'Post Liked'], 200);
            } elseif ($validatedData['key'] == 'disLike') {
                $feed->feed_likes -= 1;
                $feed->save();

                return response()->json(['success' => true, 'message' => 'Post Disliked'], 200);
            }
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
    // like feeds

    // delete feeds
    public function deleteFeed(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'feed_id' => 'required',
            ]);

            $feed = Feeds::where('feed_id', $validatedData['feed_id'])->first();

            $imagePath = public_path($feed->feed_post); // Get the full image path

            // Delete the image file if it exists
            if (file_exists($imagePath)) {
                unlink($imagePath); // Delete the image from the file system
            }

            $feed->delete();

            return response()->json(['success' => true, 'message' => 'Feed deleted'], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
    // delete feeds

    // get feeds
    public function getFeed()
    {
        $feed = Feeds::with('user')->get();

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
                'pet_image' => 'required',  // Multiple image validation
            ]);

            $petId = $validatedData['pet_id'];
            $images = $request->file('pet_image');  // Get the array of images

            $imagePaths = [];

            foreach ($images as $image) {
                // Store each image and get the path
                $imagePath = $image->store('pet_images', 'public'); // stored in 'storage/app/public/animal_images'
                $imageFullPath = 'storage/' . $imagePath;

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
                'message' => 'Images uploaded successfully!'
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
                $pet = PetImages::where('pet_id', $id)->get();

                if ($pet->isEmpty()) {
                    return response()->json(['success' => false, 'message' => 'Pet image not found', 'data' => []], 404);
                }
            }

            return response()->json(['success' => true, 'data' => $pet], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
    // get Pet Details

    // delete Pet
    public function deletePet(Request $request)
    {
        try {

            $validatedData = $request->validate([
                'pet_id' => 'required',
            ]);

            $pet = Pet::where('pet_id', $validatedData['pet_id'])->first();

            $pet->pet_status = 0;
            $pet->save();

            return response()->json(['success' => true, 'message' => 'Pet delted'], 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e);
        }
    }
    // delete Pet

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
                ->where('pet_status', 1)
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
            $petId = $request->input('pet_id');
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

            if ($petId != null) {

                $pet = Pet::where('pet_id', $petId)->first();

                if ($request->hasFile('pet_image')) {
                    // Get the path of the image from the animal record
                    $imagePath = public_path($pet->pet_image); // Get the full image path

                    // Delete the image file if it exists
                    if (file_exists($imagePath)) {
                        unlink($imagePath); // Delete the image from the file system
                    }

                    $image = $request->file('pet_image');
                    // Store the image in the 'animal_images' folder and get the file path
                    $imagePath = $image->store('pet_images', 'public'); // stored in 'storage/app/public/animal_images'
                    $imageFullPath = 'storage/' . $imagePath;
                    $pet->pet_image = $imageFullPath;
                }

                $pet->animal_id = $validatedData['animal_id'];
                $pet->breed_id = $validatedData['breed_id'];
                $pet->pet_name = $validatedData['pet_name'];
                $pet->pet_age = $validatedData['pet_age'];
                $pet->pet_gender = $validatedData['pet_gender'];
                $pet->pet_height = $validatedData['pet_height'];
                $pet->pet_weight = $validatedData['pet_weight'];
                $pet->pet_variation = $validatedData['pet_variation'];
                $pet->pet_apearance_desc = $validatedData['pet_apearance_desc'];
                $pet->pet_nature_desc = $validatedData['pet_nature_desc'];
                $pet->check_dob = $validatedData['check_dob'];
                $pet->check_feed = $validatedData['check_feed'];
                $pet->pet_dob = $validatedData['pet_dob'];

                $pet->save();

                DB::commit();
                return response()->json(['success' => true, 'message' => 'Pet updated successfully', 'data' => $pet], 200);
            } else {
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
            }
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
