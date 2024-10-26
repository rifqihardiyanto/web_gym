<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\MemberCreated;
use App\Models\Category;
use App\Models\DaftarMember;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;


class MemberController extends Controller
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
        $members = Member::with('category')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'data' => $members
        ]);
    }

    public function list()
    {
        $categories = Category::all();
        return view('dashboard.master_data.member', compact('categories'));
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
            'type_member' => 'required|string|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'price' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'exp' => 'required|date',
            'payment' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Membuat member baru
        $input = $request->all();
        $member = Member::create($input);

        $daftarMember = DaftarMember::create([
            'name' => $member->name,
            'id_member' => $member->id,
            'price' => $member->price,
            'payment' => $request->payment,
            'type_member' => $member->type_member,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil di Tambah',
            'data' => $member,
            'daftar_member' => $daftarMember
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return response()->json([
            'data' => $member
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validator = Validator::make($request->all(), [
            'type_member' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'payment' => 'required',
            'exp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $input = $request->all();
        $member->update($input);

        $daftarMember = DaftarMember::create([
            'name' => $member->name,
            'id_member' => $member->id,
            'type_member' => $member->type_member,
            'payment' => $member->payment,
        ]);

        // Mengembalikan respons
        return response()->json([
            'success' => true,
            'message' => 'Berhasil DiUpdate dan Data Baru Ditambahkan ke Daftar Member',
            'data' => $member,
            'daftar_member' => $daftarMember
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        File::delete('uploads/' . $member->gambar);
        $member->delete();

        return response()->json([
            'success' => true,
            'message' => 'Berhasil Dihapus'
        ]);
    }
}
