<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.customer.index')->with('customers',Customer::simplepaginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // اعتبارسنجی فیلدها
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'phone' => 'required'
        ], [
            'name.required' => 'وارد کردن نام الزامی است.',
            'lastname.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'address.required' => 'وارد کردن آدرس الزامی است.',
            'phone.required' => 'وارد کردن شماره تلفن الزامی است.',
        ]);
        
        Customer::create([
            'first_name' => $request->name,
            'last_name' => $request->lastname,
            'address' => $request->address,
            'phone' => persianToEnglishNumber($request->phone)
        ]);

        Session::flash('success', 'مشتری با موفقیت ثبت شد.');
        return redirect()->route('customer.index');
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
    public function edit(Customer $customer)
    {
        return view('backend.customer.edit')->with('customer',$customer);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // پیدا کردن مشتری
        $customer = Customer::findOrFail($id);

        // اعتبارسنجی فیلدها
        $request->validate([
            'name' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'phone' => 'required'
        ], [
            'name.required' => 'وارد کردن نام الزامی است.',
            'lastname.required' => 'وارد کردن نام خانوادگی الزامی است.',
            'address.required' => 'وارد کردن آدرس الزامی است.',
            'phone.required' => 'وارد کردن شماره تلفن الزامی است.',
        ]);


        $customer->update([
            'first_name' => $request->name,
            'last_name' => $request->lastname,
            'address' => $request->address,
            'phone' => persianToEnglishNumber($request->phone)
        ]);

        // پیام موفقیت
        Session::flash('success', 'اطلاعات مشتری با موفقیت بروزرسانی شد.');
        return redirect()->route('customer.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->json([
            'success' => true,
            'message' => 'مشتری با موفقیت حذف شد.'
        ]);
    }

    // ======================== live search ==============================
    public function search(Request $request)
    {
        $query = $request->get('query');
        $customers = Customer::where('first_name','like', "%{$query}%")
                            ->orWhere('last_name','like', "%{$query}%")
                            ->orWhere('phone','like', "%{$query}%")
                            ->paginate(10);

        if ($request->ajax()) {
            $table = view('backend.customer.table', compact('customers'))->render();
            $pagination = view('backend.customer.pagination', compact('customers'))->render();
            return response()->json(['table' => $table, 'pagination' => $pagination]);
        }

        return view('backend.customer.index', compact('customers'));
    }
}
