<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Member;
use App\Models\MemberReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function index()
    {
        $MemberReport = MemberReport::with('category')->get();

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
        $memberId = $request->get('id_member'); // Pastikan mendapatkan parameter dengan nama yang sesuai

        // Cari member berdasarkan kolom id_member
        $member = Member::with('category')->where('id_member', $memberId)->first();

        if ($member) {
            return response()->json([
                'id_member' => $member->id_member,
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
            'id_member' => 'required|exists:members,id_member',
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
        //
    }
}
