<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
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
        $slider = Slider::all();

        return response()->json([
            'data' =>$slider
        ]);
    }

    public function list() 
    {
        $sliders = Slider::all();
        return view('dashboard.master_data.slider', compact('sliders')
        );
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
            'title' => 'required',
            'sub_title' => 'required',
            'image' => 'required|image|mimes:jpg,png,jpeg,webp'
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $input = $request->all();
        if ($request->has('image')){
            $gambar = $request->file('image');
            $nama_gambar = time() . rand(1,9) . '.' .$gambar->getClientOriginalExtension();
            $gambar->move('uploads', $nama_gambar);
            $input['image'] =  $nama_gambar;
        }

        {
            $slider = Slider::create($input);

            return response()->json([
                'success' => true,
                'message' => 'Berhasil di Tambah',
                'data' => $slider
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Slider $slider)
    {
        return response()->json([
            'data' => $slider
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Slider $slider)
    {
        return response()->json([
            'data' => $slider
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Slider $slider)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'sub_title' => 'required',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $input = $request->all();
        
        if ($request->has('image')){
            File::delete('uploads/' . $slider->gambar);

            $gambar = $request->file('image');
            $nama_gambar = time() . rand(1,9) . '.' .$gambar->getClientOriginalExtension();
            $gambar->move('uploads', $nama_gambar);
            $input['image'] =  $nama_gambar;
        }else{
            unset($input['image']);
        }

        $slider->update($input);
        return response()->json([
            'success' => true,
            'message' => 'Berhasil di Update',
            'data' => $slider
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Slider $slider)
    {
        File::delete('uploads/' . $slider->gambar);
        $slider->delete();

        return response()->json([
            'message' => 'Berhasil Dihapus'
        ]);
    }
}
