<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Intentar autenticar al usuario
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Obtener el usuario autenticado después de la autenticación
        $user = auth()->user();

        // Agregar claims personalizados al token
        $token = JWTAuth::claims(['userId' => $user->id, 'userType' => $user->type])->fromUser($user);

        // Guardar el token en la tabla `sessions`
        DB::table('sessions')->insert([
            'userId' => $user->id,
            'token' => $token,
            'createdAt' => now(),
            'expiresAt' => now()->addHours(6), // Cambiado a 6 horas
        ]);

        // Respuesta con el token JWT y tipo de usuario
        return response()->json([
            'token' => $token,
            'userType' => $user->type,
            'userId' => $user->encrypted_id, // ID del usuario encriptado
            'userName' => $user->name . ' ' . $user->lastnames, // Nombre completo del usuario
            'userEmail' => $user->email, // Correo electrónico del usuario
        ]);
    }

    // Logout function to invalidate the token and deactivate the session changing the expiration date to the past
    public function logout(Request $request)
    {
        // Get the token from the request
        $token = $request->bearerToken();

        // Check if the token exists in the sessions table
        $session = DB::table('sessions')->where('token', $token)->first();

        if ($session) {
            // Update the expiresAt field to a past date to invalidate the session
            DB::table('sessions')->where('token', $token)->update(['expiresAt' => now()->subHours(1)]);
            return response()->json(['message' => 'Logged out successfully']);
        } else {
            return response()->json(['error' => 'Session not found'], 404);
        }
    }
}