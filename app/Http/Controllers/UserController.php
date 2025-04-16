<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $userType = JWTAuth::parseToken()->getClaim('userType');
        if ($userType != 0) {
            return response()->json(['message' => 'Unauthorized the user is ' . $userType], 403);
        }

        // Validar los datos de entrada
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:80',
            'lastname' => 'required|string|max:80', // Updated field
            'dateBirth' => 'required|date',
            'userType' => 'required|integer|between:0,1', // Updated field
            'stateBirth' => 'required|integer|between:1,32', // Updated field
            'email' => 'required|string|email|max:80|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'lastname' => $request->lastname, // Updated field
            'lastnames' => $request->lastnames,
            'dateBirth' => $request->dateBirth,
            'userType' => $request->userType, // Updated field
            'stateBirth' => $request->stateBirth, // Updated field
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Respuesta
        return response()->json([
            'message' => 'User registered successfully',
            'user' => [
                'id' => $user->encrypted_id,
                'name' => $user->name,
                'lastname' => $user->lastname, // Updated field
                'dateBirth' => $user->dateBirth,
                'userType' => $user->userType, // Updated field
                'stateBirth' => $user->stateBirth, // Updated field
                'email' => $user->email,
            ],
        ], 201);
    }

    public function index()
    {
        $userType = JWTAuth::parseToken()->getClaim('userType');
        if ($userType != 0) {
            return response()->json(['message' => 'Unauthorized the user is ' . $userType], 403);
        }

        $users = User::all()->map(function ($user) {
            return [
                'id' => $user->encrypted_id,
                'name' => $user->name,
                'lastname' => $user->lastname, // Updated field
                'dateBirth' => $user->dateBirth,
                'userType' => $user->userType, // Updated field
                'stateBirth' => $user->stateBirth, // Updated field
                'email' => $user->email,
            ];
        });

        return response()->json($users);
    }

    public function show($id)
    {
        $userType = JWTAuth::parseToken()->getClaim('userType');
        $userId = JWTAuth::parseToken()->getClaim('userId');
        $decryptedId = User::decryptId($id);

        if ($userType != 0 && $decryptedId != $userId) {
            return response()->json(['message' => 'Unauthorized the user is ' . $userType], 403);
        }

        $user = User::find($decryptedId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json([
            'id' => $user->encrypted_id,
            'name' => $user->name,
            'lastname' => $user->lastname, // Updated field
            'dateBirth' => $user->dateBirth,
            'userType' => $user->userType, // Updated field
            'stateBirth' => $user->stateBirth, // Updated field
            'email' => $user->email,
        ]);
    }

    public function update(Request $request, $id)
    {
        $userType = JWTAuth::parseToken()->getClaim('userType');
        $userId = JWTAuth::parseToken()->getClaim('userId');
        $decryptedId = User::decryptId($id);

        if ($userType != 0 && $decryptedId != $userId) {
            return response()->json(['message' => 'Unauthorized the user is ' . $userType], 403);
        }

        $user = User::find($decryptedId);

        if ($user && ($user->status == 1 || ($user->status == 0 && $request->has('status') && $request->status == 1))) {
            // Desencripta el ID del área
            if ($request->has('areaId')) {

                // convert $request to array
                $request = $request->toArray();
            }

            $validator = Validator::make($request, [
                'name' => 'sometimes|string|max:80',
                'lastname' => 'sometimes|string|max:80', // Updated field
                'dateBirth' => 'sometimes|date',
                'userType' => 'sometimes|integer|between:0,1', // Updated field
                'stateBirth' => 'sometimes|integer|between:1,32', // Updated field
                'email' => 'sometimes|string|email|max:80',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user->update($request);

            return response()->json(['message' => 'User updated successfully']);
        } else {
            return response()->json(['message' => 'User not found or inactive'], 404);
        }
    }

    public function destroy($id)
    {
        $userType = JWTAuth::parseToken()->getClaim('userType');
        if ($userType != 0) {
            return response()->json(['message' => 'Unauthorized the user is ' . $userType], 403);
        }

        $decryptedId = User::decryptId($id);
        $user = User::find($decryptedId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    public function changePassword(Request $request)
    {
        $userType = JWTAuth::parseToken()->getClaim('userType');
        $userId = JWTAuth::parseToken()->getClaim('userId');

        if ($userType != 0) {
            return response()->json(['message' => 'Unauthorized the user is ' . $userType], 403);
        }

        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 403);
        }

        $user->update(['password' => Hash::make($request->new_password)]);

        return response()->json(['message' => 'Password changed successfully']);
    }
}
