<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MobilResource;
use App\Models\Mobil;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MobilController extends Controller
{
    /**
     * Mengambil seluruh daftar data mobil dari MySQL.
     *
     * Menampilkan daftar unit mobil yang tersedia di showroom.
     * Dilengkapi fitur pencarian (?search=...), filter (?brand=..., ?status=..., ?transmisi=..., ?bahan_bakar=..., ?min_harga=..., ?max_harga=...), dan paginasi opsional (?paginate=true&per_page=10).
     *
     * @response 200 {
     *   "message": "Daftar mobil berhasil diambil",
     *   "data": [
     *     {
     *       "id": 1,
     *       "nama": "Avanza 1.5 G CVT",
     *       "brand": "Toyota",
     *       "harga": 275000000,
     *       "tahun": 2024,
     *       "transmisi": "CVT",
     *       "bahan_bakar": "Bensin",
     *       "status": "tersedia",
     *       "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341"
     *     }
     *   ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $query = Mobil::query();

        // Bonus: Pencarian berdasarkan nama model atau brand mobil
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%");
            });
        }

        // Bonus: Penyaringan berdasarkan brand
        if ($request->filled('brand')) {
            $query->where('brand', $request->input('brand'));
        }

        // Bonus: Penyaringan berdasarkan status (tersedia/sold)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Bonus: Penyaringan berdasarkan transmisi
        if ($request->filled('transmisi')) {
            $query->where('transmisi', $request->input('transmisi'));
        }

        // Bonus: Penyaringan berdasarkan bahan bakar
        if ($request->filled('bahan_bakar')) {
            $query->where('bahan_bakar', $request->input('bahan_bakar'));
        }

        // Bonus: Penyaringan rentang harga
        if ($request->filled('min_harga')) {
            $query->where('harga', '>=', (int) $request->input('min_harga'));
        }
        if ($request->filled('max_harga')) {
            $query->where('harga', '<=', (int) $request->input('max_harga'));
        }

        // Sorting
        $sortBy = in_array($request->input('sort_by'), ['harga', 'tahun', 'created_at', 'nama'])
            ? $request->input('sort_by')
            : 'id';
        $sortOrder = strtolower($request->input('sort_order', 'asc')) === 'desc' ? 'desc' : 'asc';
        $query->orderBy($sortBy, $sortOrder);

        // Bonus: Paginasi data opsional jika ?paginate=true
        if ($request->boolean('paginate')) {
            $perPage = max(1, min(100, (int) $request->input('per_page', 10)));
            $paginated = $query->paginate($perPage);

            return response()->json([
                'message' => 'Daftar mobil berhasil diambil',
                'data' => MobilResource::collection($paginated->items()),
                'pagination' => [
                    'current_page' => $paginated->currentPage(),
                    'last_page' => $paginated->lastPage(),
                    'per_page' => $paginated->perPage(),
                    'total' => $paginated->total(),
                ],
            ], 200);
        }

        $mobils = $query->get();

        return response()->json([
            'message' => 'Daftar mobil berhasil diambil',
            'data' => MobilResource::collection($mobils),
        ], 200);
    }

    /**
     * Menambahkan unit mobil baru.
     *
     * Menyimpan data unit mobil baru ke dalam basis data MySQL setelah divalidasi.
     *
     * @response 201 {
     *   "message": "Data mobil berhasil ditambahkan",
     *   "data": {
     *     "id": 2,
     *     "nama": "Civic RS Turbo",
     *     "brand": "Honda",
     *     "harga": 615000000,
     *     "tahun": 2024,
     *     "transmisi": "CVT",
     *     "bahan_bakar": "Bensin",
     *     "status": "tersedia",
     *     "foto": "https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2"
     *   }
     * }
     * @response 422 {
     *   "message": "The nama field is required. (and 1 more error)",
     *   "errors": {
     *     "nama": ["Kolom nama mobil wajib diisi."],
     *     "harga": ["Kolom harga harus berupa angka."]
     *   }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:100'],
            'harga' => ['required', 'numeric', 'min:0'],
            'tahun' => ['required', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'transmisi' => ['required', 'string', 'in:Manual,Matic,CVT'],
            'bahan_bakar' => ['required', 'string', 'in:Bensin,Diesel,Hybrid,Listrik'],
            'status' => ['required', 'string', 'in:tersedia,sold'],
            'foto' => ['required', 'string', 'url'],
        ], [
            'nama.required' => 'Kolom nama mobil wajib diisi.',
            'brand.required' => 'Kolom brand mobil wajib diisi.',
            'harga.required' => 'Kolom harga mobil wajib diisi.',
            'harga.numeric' => 'Kolom harga harus berupa angka.',
            'harga.min' => 'Kolom harga tidak boleh kurang dari 0.',
            'tahun.required' => 'Kolom tahun rakit wajib diisi.',
            'tahun.integer' => 'Kolom tahun harus berupa angka bulat.',
            'transmisi.required' => 'Kolom transmisi wajib diisi.',
            'transmisi.in' => 'Kolom transmisi harus salah satu dari: Manual, Matic, CVT.',
            'bahan_bakar.required' => 'Kolom bahan bakar wajib diisi.',
            'bahan_bakar.in' => 'Kolom bahan bakar harus salah satu dari: Bensin, Diesel, Hybrid, Listrik.',
            'status.required' => 'Kolom status wajib diisi.',
            'status.in' => 'Kolom status harus salah satu dari: tersedia, sold.',
            'foto.required' => 'Kolom URL foto wajib diisi.',
            'foto.url' => 'Kolom foto harus berupa URL yang valid.',
        ]);

        $mobil = Mobil::create($validated);

        return response()->json([
            'message' => 'Data mobil berhasil ditambahkan',
            'data' => new MobilResource($mobil),
        ], 201);
    }

    /**
     * Mengambil detail 1 data mobil berdasarkan ID.
     *
     * @response 200 {
     *   "message": "Detail mobil berhasil diambil",
     *   "data": {
     *     "id": 1,
     *     "nama": "Avanza 1.5 G CVT",
     *     "brand": "Toyota",
     *     "harga": 275000000,
     *     "tahun": 2024,
     *     "transmisi": "CVT",
     *     "bahan_bakar": "Bensin",
     *     "status": "tersedia",
     *     "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341"
     *   }
     * }
     * @response 404 {
     *   "message": "Data mobil tidak ditemukan"
     * }
     */
    public function show(string|int $id): JsonResponse
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->json([
                'message' => 'Data mobil tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'message' => 'Detail mobil berhasil diambil',
            'data' => new MobilResource($mobil),
        ], 200);
    }

    /**
     * Memperbarui data mobil yang tersimpan.
     *
     * Memperbarui informasi atribut mobil berdasarkan ID. Menerima metode HTTP PUT atau PATCH.
     *
     * @response 200 {
     *   "message": "Data mobil berhasil diperbarui",
     *   "data": {
     *     "id": 1,
     *     "nama": "Avanza 1.5 G CVT Updated",
     *     "brand": "Toyota",
     *     "harga": 280000000,
     *     "tahun": 2024,
     *     "transmisi": "CVT",
     *     "bahan_bakar": "Bensin",
     *     "status": "tersedia",
     *     "foto": "https://images.unsplash.com/photo-1549399542-7e3f8b79c341"
     *   }
     * }
     * @response 404 {
     *   "message": "Data mobil tidak ditemukan"
     * }
     * @response 422 {
     *   "message": "The given data was invalid.",
     *   "errors": {
     *     "harga": ["Kolom harga harus berupa angka."]
     *   }
     * }
     */
    public function update(Request $request, string|int $id): JsonResponse
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->json([
                'message' => 'Data mobil tidak ditemukan',
            ], 404);
        }

        $presenceRule = $request->isMethod('patch') ? 'sometimes' : 'required';

        $validated = $request->validate([
            'nama' => [$presenceRule, 'string', 'max:255'],
            'brand' => [$presenceRule, 'string', 'max:100'],
            'harga' => [$presenceRule, 'numeric', 'min:0'],
            'tahun' => [$presenceRule, 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'transmisi' => [$presenceRule, 'string', 'in:Manual,Matic,CVT'],
            'bahan_bakar' => [$presenceRule, 'string', 'in:Bensin,Diesel,Hybrid,Listrik'],
            'status' => [$presenceRule, 'string', 'in:tersedia,sold'],
            'foto' => [$presenceRule, 'string', 'url'],
        ], [
            'nama.required' => 'Kolom nama mobil wajib diisi.',
            'brand.required' => 'Kolom brand mobil wajib diisi.',
            'harga.required' => 'Kolom harga mobil wajib diisi.',
            'harga.numeric' => 'Kolom harga harus berupa angka.',
            'harga.min' => 'Kolom harga tidak boleh kurang dari 0.',
            'tahun.required' => 'Kolom tahun rakit wajib diisi.',
            'tahun.integer' => 'Kolom tahun harus berupa angka bulat.',
            'transmisi.required' => 'Kolom transmisi wajib diisi.',
            'transmisi.in' => 'Kolom transmisi harus salah satu dari: Manual, Matic, CVT.',
            'bahan_bakar.required' => 'Kolom bahan bakar wajib diisi.',
            'bahan_bakar.in' => 'Kolom bahan bakar harus salah satu dari: Bensin, Diesel, Hybrid, Listrik.',
            'status.required' => 'Kolom status wajib diisi.',
            'status.in' => 'Kolom status harus salah satu dari: tersedia, sold.',
            'foto.required' => 'Kolom URL foto wajib diisi.',
            'foto.url' => 'Kolom foto harus berupa URL yang valid.',
        ]);

        $mobil->update($validated);

        return response()->json([
            'message' => 'Data mobil berhasil diperbarui',
            'data' => new MobilResource($mobil->fresh()),
        ], 200);
    }

    /**
     * Menghapus unit mobil dari basis data.
     *
     * @response 200 {
     *   "message": "Data mobil berhasil dihapus"
     * }
     * @response 404 {
     *   "message": "Data mobil tidak ditemukan"
     * }
     */
    public function destroy(string|int $id): JsonResponse
    {
        $mobil = Mobil::find($id);

        if (!$mobil) {
            return response()->json([
                'message' => 'Data mobil tidak ditemukan',
            ], 404);
        }

        $mobil->delete();

        return response()->json([
            'message' => 'Data mobil berhasil dihapus',
        ], 200);
    }
}
