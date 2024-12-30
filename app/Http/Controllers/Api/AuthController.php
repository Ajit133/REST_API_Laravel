<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\User;
use App\Helper\ResponseHelper;
use App\Http\Requests\LoginRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;


class AuthController extends Controller
{
   
    //  Register New User
    public function register(RegisterRequest $request)
    {
        try{
           $user =  User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
            ]);
            if ($user) {
                return ResponseHelper::success(message: 'User has been registered successfully!', data: $user, statusCode: 201);
            }
            return ResponseHelper::error(message: 'Unable to Register user! Please try again.', statusCode: 400);
            

        }
        catch(Exception $e){
            \Log::error('Unable to Register User : ' . $e->getMessage().' - Line no. ' .$e->getLine());
            return ResponseHelper::error(message:'Unable to user Register!' . $e->getMessage() ,statusCode:500);
        }
    }

    /**
     *Login User
     */
    public function login(LoginRequest $request)
    {
    //    dd($request->all());
    try {

        // If credentials are incorrect
        if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return ResponseHelper::error(message: 'Unable to login due to invalid credentials.', statusCode: 400);
        }

        $user = Auth::user();

        // Create API Token
        $token = $user->createToken('My API Token')->plainTextToken;

        $authUser = [
            'user' => $user,
            'token' => $token
        ];

        return ResponseHelper::success(message: 'You are logged in successfully!', data: $authUser, statusCode: 200);
    }
    catch (Exception $e) {
        \Log::error('Unable to Login User : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
        return ResponseHelper::error(message: 'Unable to Login! Please try again.' . $e->getMessage(), statusCode: 500);
    }
    }

    /**
     * Update the specified resource in storage.
     */
    public function userProfile() {
        try {
            $user = Auth::user();
                
            if ($user) {
                return ResponseHelper::success(message: 'User profile fetched successfully!', data: $user, statusCode: 200);
            }

            return ResponseHelper::error(message: 'Unable to fetch user data due to invalid token.', statusCode: 400);
        }
        catch (Exception $e) {
            \Log::error('Unable to Fetch User Profile : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to Fetch User Profile! Please try again.' . $e->getMessage(), statusCode: 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function userLogout() {
        try {
            $user = Auth::user();

            if ($user) {
                $user->currentAccessToken()->delete();
                return ResponseHelper::success(message: 'User logged out successfully!', statusCode: 200);
            }

            return ResponseHelper::error(message: 'Unable to logout due to invalid token.', statusCode: 400);
        }
        catch (Exception $e) {
            \Log::error('Unable to Logout due to some exception : ' . $e->getMessage() . ' - Line no. ' . $e->getLine());
            return ResponseHelper::error(message: 'Unable to Logout due to some exception! Please try again.' . $e->getMessage(), statusCode: 500);
        }
    }
}
