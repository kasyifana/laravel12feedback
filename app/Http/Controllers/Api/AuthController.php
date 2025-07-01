<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;


/**
 * @OA\Info(title="API Documentation", version="1.0")
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User Login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Successful login"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $token = JWTAuth::attempt($credentials);

        return response()->json(['token' => $token]);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="User Registration",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="role", type="string", enum={"mahasiswa", "tenaga_pendidik"}),
     *             @OA\Property(property="programStudy", type="string", description="Program study code")
     *         )
     *     ),
     *     @OA\Response(response=201, description="User created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:mahasiswa,tenaga_pendidik',
            'programStudy' => 'nullable|string',
        ]);

        $data['password'] = Hash::make($data['password']);
        
        // Set is_admin to false by default since role is used for mahasiswa/tenaga_pendidik distinction
        $data['is_admin'] = false;

        $user = User::create($data);

        return response()->json(['user' => $user], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="User Logout",
     *     @OA\Response(response=200, description="Successfully logged out"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout()
    {
        Auth::logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * @OA\Get(
     *     path="/api/profile",
     *     summary="Get User Profile",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="User profile retrieved successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function profile()
    {
        return response()->json(Auth::user());
    }

    /**
     * @OA\Post(
     *     path="/api/register-admin",
     *     summary="Register First Admin (Only works if no admin exists)",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="password", type="string", format="password"),
     *             @OA\Property(property="role", type="string", enum={"mahasiswa", "tenaga_pendidik"}),
     *             @OA\Property(property="programStudy", type="string", description="Program study code")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Admin created successfully"),
     *     @OA\Response(response=403, description="Admin already exists"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function registerAdmin(Request $request)
    {
        // Check if any admin already exists
        if (User::where('is_admin', true)->exists()) {
            return response()->json(['error' => 'Admin already exists. Only existing admins can create new admins.'], 403);
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:mahasiswa,tenaga_pendidik',
            'programStudy' => 'nullable|string',
        ]);

        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = true; // Set as admin

        $user = User::create($data);

        return response()->json([
            'message' => 'First admin created successfully',
            'user' => $user
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/check-admin",
     *     summary="Check if current user is admin",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Admin status checked"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function checkAdmin()
    {
        $user = Auth::user();
        
        return response()->json([
            'is_admin' => $user ? $user->is_admin : false,
            'user' => $user
        ]);
    }
}
