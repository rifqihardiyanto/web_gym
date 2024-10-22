<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Reportnonmember;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function indexMember()
    {
        return view('dashboard.registration.regis_member');
    }

    // app/Http/Controllers/MemberController.php
    public function searchMember(Request $request)
    {
        $memberId = $request->get('id');

        // Cari member berdasarkan kolom id_member
        $member = Member::with('category')->where('id_member', $memberId)->first();

        if ($member) {
            return response()->json([
                'id_member' => $member->id_member,
                'name' => $member->name,
                'exp' => $member->exp,
                'type_member' => $member->category ? $member->category->name : null,
            ]);
        } else {
            return response()->json(['error' => 'ID Member tidak ditemukan'], 404);
        }
    }

    // public function indexNonMember()
    // {
    //     $categories = Category::all();
    //     return view('dashboard.registration.regis_nonmember', compact('categories'));
    // }

    public function regisNonMember(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'type_member' => 'require',
            'nama' => 'require',
            'price' => 'require',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $input = $request->all();
        $reportNonMember = Reportnonmember::create($input);

        // Redirect ke WhatsApp
        return response()->json([
            'success' => true,
            'message' => 'Berhasil di Tambah',
            'data' => $reportNonMember
        ]);
    }
}
