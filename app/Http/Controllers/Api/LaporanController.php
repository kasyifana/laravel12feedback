<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Laporan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $laporan = Laporan::latest()->get();
        
        return response()->json([
            'success' => true,
            'message' => 'List of all laporan',
            'data' => $laporan
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'nullable|exists:users,id',
            'judul' => 'required|string|max:255',
            'kategori' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'prioritas' => 'required|in:Low,Medium,High',
            'tanggal_lapor' => 'required|date',
            'waktu_lapor' => 'required',
            'nama_pelapor' => 'nullable|string|max:100',
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file upload if provided
        $lampiranPath = null;
        if ($request->hasFile('lampiran')) {
            $file = $request->file('lampiran');
            $fileName = $file->getClientOriginalName();
            
            // If we're getting a filename from the request directly, use that exact name
            if ($request->has('filename') && !empty($request->filename)) {
                $fileName = $request->filename;
            }
            // Otherwise, keep the original name if it follows our pattern
            else if (!preg_match('/^lampiran_\d+\.\w+$/', $fileName)) {
                // If not in expected format, generate one with current timestamp
                $fileName = 'lampiran_' . time() . '.' . $file->getClientOriginalExtension();
            }
            
            // Make sure the uploads directory exists
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            
            $file->move(public_path('uploads'), $fileName);
            $lampiranPath = 'public/uploads/' . $fileName;
        }

        // Create laporan
        $laporan = Laporan::create([
            'user_id' => $request->user_id ?? (Auth::check() ? Auth::id() : null),
            'judul' => $request->judul,
            'kategori' => $request->kategori,
            'deskripsi' => $request->deskripsi,
            'prioritas' => $request->prioritas,
            'status' => 'Pending', // Default status
            'tanggal_lapor' => $request->tanggal_lapor,
            'waktu_lapor' => $request->waktu_lapor,
            'nama_pelapor' => $request->nama_pelapor ?? (Auth::check() ? Auth::user()->name : null),
            'lampiran' => $lampiranPath,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan created successfully',
            'data' => $laporan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $laporan = Laporan::find($id);
        
        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $laporan
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find laporan
        $laporan = Laporan::find($id);
        
        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan not found'
            ], 404);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'user_id' => 'sometimes|nullable|exists:users,id',
            'judul' => 'sometimes|required|string|max:255',
            'kategori' => 'sometimes|required|string|max:100',
            'deskripsi' => 'sometimes|required|string',
            'prioritas' => 'sometimes|required|in:Low,Medium,High',
            'status' => 'sometimes|required|in:Pending,In Progress,Selesai',
            'tanggal_lapor' => 'sometimes|required|date',
            'waktu_lapor' => 'sometimes|required',
            'nama_pelapor' => 'nullable|string|max:100',
            'lampiran' => 'nullable|file|mimes:jpeg,png,jpg,pdf,doc,docx|max:2048',
            'respon' => 'nullable|string',
            'oleh' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Handle file upload if provided
        if ($request->hasFile('lampiran')) {
            // Delete old file if exists
            if ($laporan->lampiran) {
                $oldPath = public_path($laporan->lampiran);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }
            
            $file = $request->file('lampiran');
            $fileName = $file->getClientOriginalName();
            
            // If we're getting a filename from the request directly, use that exact name
            if ($request->has('filename') && !empty($request->filename)) {
                $fileName = $request->filename;
            }
            // Otherwise, keep the original name if it follows our pattern
            else if (!preg_match('/^lampiran_\d+\.\w+$/', $fileName)) {
                // If not in expected format, generate one with current timestamp
                $fileName = 'lampiran_' . time() . '.' . $file->getClientOriginalExtension();
            }
            
            // Make sure the uploads directory exists
            if (!file_exists(public_path('uploads'))) {
                mkdir(public_path('uploads'), 0777, true);
            }
            
            $file->move(public_path('uploads'), $fileName);
            $lampiranPath = 'uploads/' . $fileName;
            $laporan->lampiran = $lampiranPath;
        }

        // Update laporan
        $laporan->user_id = $request->user_id ?? $laporan->user_id;
        $laporan->judul = $request->judul ?? $laporan->judul;
        $laporan->kategori = $request->kategori ?? $laporan->kategori;
        $laporan->deskripsi = $request->deskripsi ?? $laporan->deskripsi;
        $laporan->prioritas = $request->prioritas ?? $laporan->prioritas;
        
        // If status is changed or response is added, update related fields
        if (($request->has('status') && $request->status !== $laporan->status) || 
            ($request->has('respon') && $request->respon)) {
            $laporan->status = $request->status ?? $laporan->status;
            $laporan->respon = $request->respon ?? $laporan->respon;
            $laporan->oleh = $request->oleh ?? $laporan->oleh;
            $laporan->waktu_respon = now();
        }
        
        $laporan->tanggal_lapor = $request->tanggal_lapor ?? $laporan->tanggal_lapor;
        $laporan->waktu_lapor = $request->waktu_lapor ?? $laporan->waktu_lapor;
        $laporan->nama_pelapor = $request->nama_pelapor ?? $laporan->nama_pelapor;
        
        $laporan->save();

        return response()->json([
            'success' => true,
            'message' => 'Laporan updated successfully',
            'data' => $laporan
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $laporan = Laporan::find($id);
        
        if (!$laporan) {
            return response()->json([
                'success' => false,
                'message' => 'Laporan not found'
            ], 404);
        }

        // Delete file if exists
        if ($laporan->lampiran) {
            $filePath = public_path($laporan->lampiran);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }

        // Delete laporan
        $laporan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Laporan deleted successfully'
        ]);
    }
    
    /**
     * Get laporan by status.
     */
    public function getByStatus(Request $request, string $status)
    {
        // Validate status
        if (!in_array($status, ['Pending', 'In Progress', 'Selesai'])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status parameter'
            ], 400);
        }
        
        $laporan = Laporan::where('status', $status)->latest()->get();
        
        return response()->json([
            'success' => true,
            'message' => "List of {$status} laporan",
            'data' => $laporan
        ]);
    }
    
    /**
     * Get laporan by authenticated user
     */
    public function myLaporan(Request $request)
    {
        // Make sure user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }
        
        $laporan = Laporan::where('user_id', Auth::id())->latest()->get();
        
        return response()->json([
            'success' => true,
            'message' => "My laporan",
            'data' => $laporan
        ]);
    }
    
    /**
     * Get statistics for dashboard
     */
    public function getStats(Request $request)
    {
        // Get counts by status
        $pendingCount = Laporan::where('status', 'Pending')->count();
        $inProgressCount = Laporan::where('status', 'In Progress')->count();
        $selesaiCount = Laporan::where('status', 'Selesai')->count();
        
        // Get counts by priority
        $lowCount = Laporan::where('prioritas', 'Low')->count();
        $mediumCount = Laporan::where('prioritas', 'Medium')->count();
        $highCount = Laporan::where('prioritas', 'High')->count();
        
        // Get user's laporan counts if authenticated
        $userStats = null;
        if (Auth::check()) {
            $userStats = [
                'total' => Laporan::where('user_id', Auth::id())->count(),
                'pending' => Laporan::where('user_id', Auth::id())->where('status', 'Pending')->count(),
                'inProgress' => Laporan::where('user_id', Auth::id())->where('status', 'In Progress')->count(),
                'selesai' => Laporan::where('user_id', Auth::id())->where('status', 'Selesai')->count(),
            ];
        }
        
        // Get latest laporan
        $latestLaporan = Laporan::latest()->take(5)->get();
        
        return response()->json([
            'success' => true,
            'data' => [
                'status' => [
                    'pending' => $pendingCount,
                    'inProgress' => $inProgressCount,
                    'selesai' => $selesaiCount,
                    'total' => $pendingCount + $inProgressCount + $selesaiCount
                ],
                'priority' => [
                    'low' => $lowCount,
                    'medium' => $mediumCount,
                    'high' => $highCount
                ],
                'user' => $userStats,
                'latest' => $latestLaporan
            ]
        ]);
    }
}
