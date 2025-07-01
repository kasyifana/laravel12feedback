<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class FeedbackController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/feedback",
     *     summary="Get all feedback with pagination and filters",
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
     *         description="Filter by rating (1-5)",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=5)
     *     ),
     *     @OA\Parameter(
     *         name="has_reply",
     *         in="query",
     *         description="Filter by reply status (true/false)",
     *         required=false,
     *         @OA\Schema(type="boolean")
     *     ),
     *     @OA\Response(response=200, description="Feedback retrieved successfully")
     * )
     */
    public function index(Request $request)
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
        $feedback = $query->orderBy('created_at', 'desc')->paginate(10);

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
     * @OA\Post(
     *     path="/api/feedback",
     *     summary="Create new feedback",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"rating", "komentar"},
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5),
     *             @OA\Property(property="komentar", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Feedback created successfully"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|max:1000',
        ]);

        // Add user_id if user is authenticated
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        $feedback = Feedback::create($data);
        $feedback->load('user:id,name,email');

        return response()->json([
            'message' => 'Feedback berhasil dibuat',
            'feedback' => $feedback
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/feedback/{id}",
     *     summary="Get specific feedback by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Feedback ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Feedback retrieved successfully"),
     *     @OA\Response(response=404, description="Feedback not found")
     * )
     */
    public function show($id)
    {
        $feedback = Feedback::with('user:id,name,email')->find($id);

        if (!$feedback) {
            return response()->json(['error' => 'Feedback tidak ditemukan'], 404);
        }

        return response()->json(['feedback' => $feedback]);
    }

    /**
     * @OA\Get(
     *     path="/api/feedback/my",
     *     summary="Get current user's feedback",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="User feedback retrieved successfully"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function myFeedback(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $feedback = Feedback::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

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
     *     path="/api/feedback/{id}",
     *     summary="Update user's own feedback",
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
     *             @OA\Property(property="rating", type="integer", minimum=1, maximum=5),
     *             @OA\Property(property="komentar", type="string")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Feedback updated successfully"),
     *     @OA\Response(response=403, description="Forbidden - Can only edit own feedback"),
     *     @OA\Response(response=404, description="Feedback not found")
     * )
     */
    public function update(Request $request, $id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['error' => 'Feedback tidak ditemukan'], 404);
        }

        // Check if user owns this feedback
        if (!Auth::check() || $feedback->user_id !== Auth::id()) {
            return response()->json(['error' => 'Anda hanya bisa mengedit feedback sendiri'], 403);
        }

        $data = $request->validate([
            'rating' => 'sometimes|required|integer|min:1|max:5',
            'komentar' => 'sometimes|required|string|max:1000',
        ]);

        $feedback->update($data);
        $feedback->load('user:id,name,email');

        return response()->json([
            'message' => 'Feedback berhasil diupdate',
            'feedback' => $feedback
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/feedback/{id}",
     *     summary="Delete user's own feedback",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Feedback ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Feedback deleted successfully"),
     *     @OA\Response(response=403, description="Forbidden - Can only delete own feedback"),
     *     @OA\Response(response=404, description="Feedback not found")
     *     )
     */
    public function destroy($id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json(['error' => 'Feedback tidak ditemukan'], 404);
        }

        // Check if user owns this feedback or is admin
        if (!Auth::check() || ($feedback->user_id !== Auth::id() && !Auth::user()->is_admin)) {
            return response()->json(['error' => 'Anda hanya bisa menghapus feedback sendiri'], 403);
        }

        $feedback->delete();

        return response()->json(['message' => 'Feedback berhasil dihapus']);
    }

    /**
     * @OA\Get(
     *     path="/api/feedback/stats",
     *     summary="Get feedback statistics",
     *     @OA\Response(response=200, description="Statistics retrieved successfully")
     * )
     */
    public function getStats()
    {
        $stats = [
            'total_feedback' => Feedback::count(),
            'average_rating' => round(Feedback::avg('rating'), 2),
            'feedback_with_replies' => Feedback::withReplies()->count(),
            'feedback_without_replies' => Feedback::withoutReplies()->count(),
            'rating_distribution' => [
                '5' => Feedback::byRating(5)->count(),
                '4' => Feedback::byRating(4)->count(),
                '3' => Feedback::byRating(3)->count(),
                '2' => Feedback::byRating(2)->count(),
                '1' => Feedback::byRating(1)->count(),
            ],
            'recent_feedback' => Feedback::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        return response()->json(['stats' => $stats]);
    }
}
