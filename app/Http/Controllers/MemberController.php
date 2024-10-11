<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;


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
        $members = Member::with('category')->get();

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
            'phone' => 'required|string|unique:members,phone|max:20',
            'exp' => 'required|date'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $input = $request->all();

        $member = Member::create($input);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil di Tambah',
            'data' => $member
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
            'exp' => 'required'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $input = $request->all();

        $member->update($input);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil DiUpdate',
            'data' => $member
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
