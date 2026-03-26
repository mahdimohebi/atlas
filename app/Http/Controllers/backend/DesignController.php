<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Design;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designs = Design::simplepaginate(20);
        return view('backend.extra_design.index',compact('designs'));
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
        // اعتبارسنجی فیلدها
        $request->validate([
            'name' => 'required',

        ], [
            'name.required' => 'وارد کردن نام الزامی است.',

        ]);
        
        Design::create([
            'name' => $request->name,

        ]);

        Session::flash('success', 'دیزاین با موفقیت ثبت شد.');
        return redirect()->route('designs.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Design $design)
    {
        $design->delete();
        return response()->json([
            'success' => true,
            'message' => 'دیزاین با موفقیت حذف شد.'
        ]);
    }
}
