<?php

namespace App\Http\Controllers;

use App\Notifications\VerifyEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function register(Request $request)
    {
        $registerUserData = $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'email'=>'required|string|email|unique:users',
        ]);

        $user = User::create([
            'first_name' => $registerUserData['first_name'],
            'last_name' => $registerUserData['last_name'],
            'mobile' => $registerUserData['mobile'],
            'address' => $registerUserData['address'],
            'email' => $registerUserData['email'],
        ]);

        $token = $user->createToken($user->email.'-AuthToken')->plainTextToken;

        $otp = random_int(100000, 999999);
        $user->otp = $otp;
        $user->save();
        $user->notify(new VerifyEmail($otp));

        return response()->json([
            'access_token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        // Validate the incoming request data to ensure the 'email' field is provided, is a string, and is in a valid email format.
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
        ]);

        // Attempt to retrieve the user by email from the database.
        $user = User::where('email', $loginUserData['email'])->first();

        // If no user is found with the provided email, return a 401 Unauthorized response with an error message.
        if (!$user) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        // Check if the user is a provider.
        if ($user->is_provider) {
            // Verify the provided password against the stored hash.
            if (!Hash::check($request->password, $user->password)) {
                // If the password check fails, return a 401 Unauthorized response with an error message.
                return response()->json([
                    'message' => 'Invalid Credentials',
                ], 401);
            }

            // Create an authentication token for the user.
            $token = $user->createToken($user->email . '-AuthToken')->plainTextToken;

            // Return the authentication token in the response.
            return response()->json([
                'access_token' => $token,
            ]);
        }

        // If the user is not a provider, generate a one-time password (OTP) for email verification.
        $otp = random_int(100000, 999999);

        // Save the OTP to the user's record in the database.
        $user->otp = $otp;
        $user->save();

        // Send a notification to the user with the OTP for email verification.
        $user->notify(new VerifyEmail($otp));

        // Return a response indicating that the verification email was sent successfully.
        return response()->json(['message' => 'Verification email sent successfully']);
    }

    public function verifyLogin(Request $request)
    {
        // Validate the incoming request data to ensure the 'email' field is provided, is a string, and is in a valid email format.
        $loginUserData = $request->validate([
            'email' => 'required|string|email',
        ]);

        // Attempt to retrieve the user by email from the database.
        $user = User::where('email', $loginUserData['email'])->first();

        // If no user is found with the provided email, return a 401 Unauthorized response with an error message.
        if (!$user) {
            return response()->json([
                'message' => 'Invalid Credentials'
            ], 401);
        }

        // Check if the provided OTP matches the one stored for the user.
        if ($request->otp === $user->otp) {
            // Clear the OTP after successful verification.
            $user->otp = null;

            // If the user's email has not been verified yet, set the email_verified_at timestamp to the current time.
            if (empty($user->email_verified_at)) {
                $user->email_verified_at = now();
            }

            // Save the user's updated information to the database.
            $user->save();

            // Create an authentication token for the user.
            $token = $user->createToken($user->email . '-AuthToken')->plainTextToken;

            // Return the authentication token in the response.
            return response()->json([
                'access_token' => $token,
            ]);
        }

        // If the provided OTP does not match, return a 401 Unauthorized response with an error message.
        return response()->json([
            'message' => 'Invalid OTP'
        ], 401);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            "message"=>"logged out"
        ]);
    }

    public function getUser()
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user
        ]);
    }

    public function updateUser(Request $request)
    {
        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
        $user = auth()->user();

        $user->clearMediaCollection();
        // Handle profile photo upload
        if ($request->hasFile('profile_image')) {
            $user->addMedia($request->file('profile_image'))->toMediaCollection();
        }

        // Update user details
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->mobile = $request->filled('mobile') ? $request->input('mobile') : null;
        $user->address = $request->filled('address') ? $request->input('address') : null;
        $user->save();

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->fresh()
        ]);
    }

    public function verifyEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($request->otp === $user->otp) {
            $user->email_verified_at = now();
            $user->otp = null; // Clear OTP after verification
            $user->save();

            return response()->json(['message' => 'Email verified successfully']);
        }

        return response()->json(['message' => 'Invalid OTP'], 400);
    }

    public function resendVerificationEmail(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $otp = random_int(100000, 999999);
        $user->otp = $otp;
        $user->save();

        // Send email with OTP
        $user->notify(new VerifyEmail($otp));

        return response()->json(['message' => 'Verification email resent successfully'], 200);
    }
}
