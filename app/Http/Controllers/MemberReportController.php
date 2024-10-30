<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\DaftarMember;
use App\Models\Member;
use App\Models\MemberReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Exports\DaftarMemberExport;
use App\Exports\RegisMemberExport;
use App\Exports\RegisNonMemberExport;
use Maatwebsite\Excel\Facades\Excel;

class MemberReportController extends Controller
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

        // Cek jika start_date dan endDate tidak ada, gunakan tanggal hari ini
        if (!$startDate && !$endDate) {
            $today = Carbon::today();
            $MemberReport = MemberReport::with('category')
                ->whereDate('created_at', $today)
                ->get();
        } else {
            // Jika ada start_date dan endDate, atur rentang waktu secara penuh (awal hari sampai akhir hari)
            $startDate = Carbon::parse($startDate)->startOfDay();
            $endDate = Carbon::parse($endDate)->endOfDay();

            // Query berdasarkan rentang waktu yang sudah diatur
            $MemberReport = MemberReport::with('category')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();
        }

        // Kembalikan data dalam format JSON
        return response()->json([
            'data' => $MemberReport
        ]);
    }

    public function list()
    {
        $categories = Category::all();
        return view('dashboard.registration.regis_member', compact('categories'));
    }

    public function searchMember(Request $request)
    {
        $memberId = $request->get('id'); // Mengambil parameter 'id' dari request

        // Cari member berdasarkan kolom 'id'
        $member = Member::with('category')->where('id', $memberId)->first();

        if ($member) {
            return response()->json([
                'id_member' => $member->id_member, // Masih mengembalikan 'id_member' sebagai respon
                'name' => $member->name,
                'exp' => $member->exp,
                'type_member' => $member->category ? $member->category->name : null,
                'harga' => $member->category ? $member->category->biaya : null // Ambil biaya dari kategori
            ]);
        } else {
            return response()->json(['error' => 'ID Member tidak ditemukan'], 404);
        }
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
            'id_member' => 'required|',
            'nama' => 'required|string|max:50',
            'kategori' => 'required|string|max:255',
            'harga' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Membuat member baru
        $input = $request->all();
        $MemberReport = MemberReport::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil di Tambah',
            'data' => $MemberReport
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(MemberReport $memberReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MemberReport $memberReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MemberReport $memberReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MemberReport $memberReport)
    {
        $memberReport->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Dihapus'
        ]);
    }

    public function exportDaftarMember(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        // Validasi apakah tanggal akhir lebih besar dari tanggal mulai
        if ($startDate > $endDate) {
            return redirect()->back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
        }

        return Excel::download(new DaftarMemberExport($startDate, $endDate), 'daftar_member.xlsx');
    }

    public function daftarMember(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Validasi format dan rentang tanggal
        if (!$startDate || !$endDate) {
            $startDate = Carbon::today()->toDateString();
            $endDate = Carbon::today()->toDateString();
        } elseif ($startDate > $endDate) {
            return redirect()->back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
        }

        $daftarMember = DaftarMember::with('category')
            ->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate)
            ->get();

        return view('dashboard.registration.daftar_member', compact('daftarMember'));
    }

    public function exportRegisMember(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        // Validasi apakah tanggal akhir lebih besar dari tanggal mulai
        if ($startDate > $endDate) {
            return redirect()->back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
        }

        return Excel::download(new RegisMemberExport($startDate, $endDate), 'regis_member.xlsx');
    }

    public function exportRegisNonMember(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::today()->toDateString());
        $endDate = $request->input('end_date', Carbon::today()->toDateString());

        // Validasi apakah tanggal akhir lebih besar dari tanggal mulai
        if ($startDate > $endDate) {
            return redirect()->back()->with('error', 'Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
        }

        return Excel::download(new RegisNonMemberExport($startDate, $endDate), 'regis_nonmember.xlsx');
    }
}
