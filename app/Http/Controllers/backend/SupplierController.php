<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.supplier.index')->with('suppliers',Supplier::simplepaginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'f_name'=>'required',
            'address'=>'required',
            'phone'=>'required'
        ],[
            'name.required' => 'وارد کردن نام الزامی است.',
            'f_name.required' => 'وارد کردن نام پدر الزامی است.',
            'address.required' => 'وارد کردن آدرس الزامی است.',
            'phone.required' => 'وارد کردن شماره تلفون الزامی است.',
        ]);

        Supplier::create([
            'name'=>$request->name,
            'f_name'=>$request->f_name,
            'address'=>$request->address,
            'phone'=> persianToEnglishNumber($request->phone)
        ]);

        Session::flash('success','فروشنده با موفقیت ثبت شد.');
        return redirect()->route('supplier.index');
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
    public function edit(Supplier $supplier)
    {
        return view('backend.supplier.edit')->with('supplier', $supplier);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'=>'required',
            'f_name'=>'required',
            'address'=>'required',
            'phone'=>'required'
        ],[
            'name.required' => 'وارد کردن نام الزامی است.',
            'f_name.required' => 'وارد کردن نام پدر الزامی است.',
            'address.required' => 'وارد کردن آدرس الزامی است.',
            'phone.required' => 'وارد کردن شماره تلفون الزامی است.',
        ]);

        $supplier->update([
            'name'=>$request->name,
            'f_name'=>$request->f_name,
            'address'=>$request->address,
            'phone'=> persianToEnglishNumber($request->phone)
        ]);

        Session::flash('success','فروشنده با موفقیت ویرایش شد.');
        return redirect()->route('supplier.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json([
            'success' => true,
            'message' => 'فروشنده با موفقیت حذف شد.'
        ]);
    }

    // ======================== live search ==============================
    public function search(Request $request)
    {
        $query = $request->get('query');
        $suppliers = Supplier::where('name','like', "%{$query}%")
                            ->orWhere('f_name','like', "%{$query}%")
                            ->orWhere('phone','like', "%{$query}%")
                            ->paginate(10);

        if ($request->ajax()) {
            $table = view('backend.supplier.table', compact('suppliers'))->render();
            $pagination = view('backend.supplier.pagination', compact('suppliers'))->render();
            return response()->json(['table' => $table, 'pagination' => $pagination]);
        }

        return view('backend.supplier.index', compact('suppliers'));
    }



}
