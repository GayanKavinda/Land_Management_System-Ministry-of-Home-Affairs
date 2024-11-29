<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRequest;

class UserRequestController extends Controller
{
    
    public function store(Request $request)
    {
        try {
            // Fetch user details from the authenticated user
            $user = auth()->user();

            // Check if the user request already exists
            $existingRequest = UserRequest::where('user_id', $user->id)->first();

            if ($existingRequest) {
                return response()->json(['error' => 'You have already submitted a request.'], 422);
            }

            // Create a new user request
            UserRequest::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'email' => $user->email,
                'status' => 'pending', // You can set the initial status as 'pending'
            ]);

            return response()->json(['message' => 'Your request has been submitted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()], 500);
        }
    }

}
