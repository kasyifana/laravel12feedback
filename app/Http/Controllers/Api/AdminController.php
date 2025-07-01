<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/admin/users",
     *     summary="Get all users (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search by name or email",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="role",
     *         in="query",
     *         description="Filter by role",
     *         required=false,
     *         @OA\Schema(type="string", enum={"mahasiswa", "tenaga_pendidik"})
     *     ),
     *     @OA\Response(response=200, description="Users retrieved successfully"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required")
     * )
     */    public function getAllUsers(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Paginate results
        $users = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'users' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/users/{id}/toggle-admin",
     *     summary="Toggle admin status of a user",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User admin status updated successfully"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=422, description="Cannot modify own admin status")
     * )
     */    public function toggleAdminStatus($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Prevent admin from removing their own admin status
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Cannot modify your own admin status'], 422);
        }

        // Toggle admin status
        $user->is_admin = !$user->is_admin;
        $user->save();

        return response()->json([
            'message' => 'User admin status updated successfully',
            'user' => $user,
            'is_admin' => $user->is_admin
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/users/{id}",
     *     summary="Update user information (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="role", type="string", enum={"mahasiswa", "tenaga_pendidik"}),
     *             @OA\Property(property="programStudy", type="string"),
     *             @OA\Property(property="is_admin", type="boolean")
     *         )
     *     ),
     *     @OA\Response(response=200, description="User updated successfully"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $data = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'sometimes|required|string|in:mahasiswa,tenaga_pendidik',
            'programStudy' => 'sometimes|nullable|string',
            'is_admin' => 'sometimes|boolean',
        ]);

        // Prevent admin from removing their own admin status
        if (isset($data['is_admin']) && $user->id === Auth::id() && !$data['is_admin']) {
            return response()->json(['error' => 'Cannot remove your own admin status'], 422);
        }

        $user->update($data);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/users/{id}",
     *     summary="Delete a user (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="User ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User deleted successfully"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required"),
     *     @OA\Response(response=404, description="User not found"),
     *     @OA\Response(response=422, description="Cannot delete own account")
     * )
     */    public function deleteUser($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Prevent admin from deleting their own account
        if ($user->id === Auth::id()) {
            return response()->json(['error' => 'Cannot delete your own account'], 422);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/stats",
     *     summary="Get admin dashboard statistics",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Statistics retrieved successfully"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required")
     * )
     */    public function getStats()
    {        $stats = [
            'total_users' => User::count(),
            'total_admins' => User::where('is_admin', true)->count(),
            'total_mahasiswa' => User::where('role', 'mahasiswa')->count(),
            'total_tenaga_pendidik' => User::where('role', 'tenaga_pendidik')->count(),
            'recent_users' => User::where('created_at', '>=', now()->subDays(7))->count(),
            'total_feedback' => Feedback::count(),
            'feedback_without_replies' => Feedback::whereNull('balasan')->count(),
            'average_rating' => round(Feedback::avg('rating'), 2),
        ];

        return response()->json(['stats' => $stats]);
    }

    /**
     * @OA\Get(
     *     path="/api/admin/feedback",
     *     summary="Get all feedback for admin management",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="rating",
     *         in="query",
     *         description="Filter by rating",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=5)
     *     ),
     *     @OA\Parameter(
     *         name="has_reply",
     *         in="query",
     *         description="Filter by reply status",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response=200, description="Feedback retrieved successfully"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required")
     * )
     */
    public function getAllFeedback(Request $request)
    {
        $query = Feedback::with('user:id,name,email');

        // Filter by rating
        if ($request->has('rating')) {
            $query->byRating($request->rating);
        }

        // Filter by reply status
        if ($request->has('has_reply')) {
            if ($request->boolean('has_reply')) {
                $query->withReplies();
            } else {
                $query->withoutReplies();
            }
        }

        // Paginate results
        $feedback = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'feedback' => $feedback->items(),
            'pagination' => [
                'current_page' => $feedback->currentPage(),
                'last_page' => $feedback->lastPage(),
                'per_page' => $feedback->perPage(),
                'total' => $feedback->total(),
            ]
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/admin/feedback/{id}/reply",
     *     summary="Reply to feedback (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Feedback ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"balasan"},
     *             @OA\Property(property="balasan", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Reply sent successfully"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required"),
     *     @OA\Response(response=404, description="Feedback not found")
     * )
     */
    public function replyToFeedback(Request $request, $id)
    {
        $feedback = Feedback::find($id);
        
        if (!$feedback) {
            return response()->json(['error' => 'Feedback tidak ditemukan'], 404);
        }

        $data = $request->validate([
            'balasan' => 'required|string|max:1000',
        ]);

        $feedback->update($data);
        $feedback->load('user:id,name,email');

        return response()->json([
            'message' => 'Balasan berhasil dikirim',
            'feedback' => $feedback
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/admin/feedback/{id}",
     *     summary="Delete feedback (Admin only)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Feedback ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Feedback deleted successfully"),
     *     @OA\Response(response=403, description="Forbidden - Admin access required"),
     *     @OA\Response(response=404, description="Feedback not found")
     * )
     */
    public function deleteFeedback($id)
    {
        $feedback = Feedback::find($id);
        
        if (!$feedback) {
            return response()->json(['error' => 'Feedback tidak ditemukan'], 404);
        }

        $feedback->delete();

        return response()->json(['message' => 'Feedback berhasil dihapus']);
    }
}
