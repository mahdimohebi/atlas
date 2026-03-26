<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.clients.index')->with('clients',Client::simplepaginate(10));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.clients.create');
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

        Client::create([
            'name'=>$request->name,
            'f_name'=>$request->f_name,
            'address'=>$request->address,
            'phone'=> persianToEnglishNumber($request->phone)
        ]);

        Session::flash('success','مشتری با موفقیت ثبت شد.');
        return redirect()->route('client.index');

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
    public function edit(Client $client)
    {
        return view('backend.clients.edit')->with('client', $client);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
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

        $client->update([
            'name'=>$request->name,
            'f_name'=>$request->f_name,
            'address'=>$request->address,
            'phone'=> persianToEnglishNumber($request->phone)
        ]);

        Session::flash('success','مشتری با موفقیت ویرایش شد.');
        return redirect()->route('client.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return response()->json([
            'success' => true,
            'message' => 'مشتری با موفقیت حذف شد.'
        ]);
    }

// ======================== live search ==============================
    public function search(Request $request)
    {
        $query = $request->get('query');
        $clients = Client::where('name','like', "%{$query}%")
                            ->orWhere('f_name','like', "%{$query}%")
                            ->orWhere('phone','like', "%{$query}%")
                            ->paginate(10);

        if ($request->ajax()) {
            $table = view('backend.clients.table', compact('clients'))->render();
            $pagination = view('backend.clients.pagination', compact('clients'))->render();
            return response()->json(['table' => $table, 'pagination' => $pagination]);
        }

        return view('backend.clients.index', compact('clients'));
    }
}
