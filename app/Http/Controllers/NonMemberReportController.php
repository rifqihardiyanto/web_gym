<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NonMemberReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class NonMemberReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth')->only(['list']);
        $this->middleware('auth:api')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        // Ambil input dari request untuk rentang waktu
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Inisialisasi query dasar
        $query = NonMemberReport::with('category');

        // Jika tidak ada rentang waktu, ambil data hari ini
        if (!$startDate && !$endDate) {
            // Set tanggal hari ini
            $today = Carbon::today();

            // Tambahkan kondisi untuk mengambil data hari ini
            $query->whereDate('created_at', $today);
        } else {
            // Jika ada start_date dan endDate, atur rentang waktu secara penuh (awal hari sampai akhir hari)
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();

            // Query berdasarkan rentang waktu yang sudah diatur
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Ambil hasil query
        $nonMemberReport = $query->get();

        // Kembalikan data dalam format JSON
        return response()->json([
            'data' => $nonMemberReport
        ]);
    }

    public function list()
    {
        $categories = Category::all();
        return view('dashboard.registration.regis_non_member', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:50',
            'kategori' => 'required|string|max:255',
            'payment' => 'required|string|max:255',
            'harga' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Membuat member baru
        $input = $request->all();
        $nonMemberReport = NonMemberReport::create($input);

        // Redirect ke WhatsApp
        return response()->json([
            'success' => true,
            'message' => 'Berhasil di Tambah',
            'data' => $nonMemberReport
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(NonMemberReport $nonMemberReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(NonMemberReport $nonMemberReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, NonMemberReport $nonMemberReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(NonMemberReport $nonMemberReport)
    {
        //
    }
}
