<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of all program studi.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $programStudi = ProgramStudi::all();
            
            return response()->json([
                'success' => true,
                'message' => 'Data program studi berhasil diambil',
                'data' => $programStudi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data program studi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created program studi.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|unique:program_studi,code',
                'name' => 'required|string'
            ]);

            $programStudi = ProgramStudi::create($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Program studi berhasil ditambahkan',
                'data' => $programStudi
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan program studi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified program studi by code.
     *
     * @param string $code
     * @return JsonResponse
     */
    public function show(string $code): JsonResponse
    {
        try {
            $programStudi = ProgramStudi::where('code', $code)->first();
            
            if (!$programStudi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program studi tidak ditemukan'
                ], 404);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Data program studi berhasil diambil',
                'data' => $programStudi
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data program studi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified program studi.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $programStudi = ProgramStudi::find($id);
            
            if (!$programStudi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program studi tidak ditemukan'
                ], 404);
            }

            $validated = $request->validate([
                'code' => ['required', 'string', Rule::unique('program_studi', 'code')->ignore($id)],
                'name' => 'required|string'
            ]);

            $programStudi->update($validated);
            
            return response()->json([
                'success' => true,
                'message' => 'Program studi berhasil diperbarui',
                'data' => $programStudi
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui program studi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified program studi.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $programStudi = ProgramStudi::find($id);
            
            if (!$programStudi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Program studi tidak ditemukan'
                ], 404);
            }

            $programStudi->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Program studi berhasil dihapus'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus program studi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
