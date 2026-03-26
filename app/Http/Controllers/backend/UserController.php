<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('backend.users.index')->with('users', User::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    // اعتبارسنجی فرم
    $request->validate([
        'name'          => 'required|string|max:255',
        'last_name'     => 'required|string|max:255',
        'email'         => 'required|email|unique:users,email',
        'password'      => 'required|min:8',
        'con_password'  => 'required|same:password',
        'role'          => 'required|in:0,1',
        'section'       => 'required|in:Factory,Trade_al',
        'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
    ], [
        'name.required'         => 'وارد کردن نام الزامی است.',
        'last_name.required'    => 'وارد کردن نام خانوادگی الزامی است.',
        'email.required'        => 'وارد کردن ایمیل الزامی است.',
        'email.email'           => 'ایمیل نامعتبر است.',
        'email.unique'          => 'این ایمیل قبلاً ثبت شده است.',
        'role.required'         => 'وارد کردن نقش کاربر الزامی است.',
        'password.required'     => 'وارد کردن پسورد الزامی است.',
        'password.min'          => 'پسورد حداقل 8 کاراکتر باشد.',
        'con_password.required' => 'وارد کردن تایید پسورد الزامی است.',
        'con_password.same'     => 'تایید پسورد با پسورد یکسان نیست.',
        'section.required'      => 'انتخاب بخش الزامی است.',
        'section.in'            => 'بخش انتخاب شده معتبر نیست.',
        'image.image'           => 'فایل انتخابی باید تصویر باشد.',
        'image.mimes'           => 'تصویر باید یکی از فرمت‌های jpeg, png, jpg, gif, webp باشد.',
        'image.max'             => 'حداکثر حجم تصویر 2 مگابایت است.',
    ]);

    // آپلود تصویر اگر انتخاب شده باشد
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('users', 'public'); // ذخیره در storage/app/public/users
    }

    // ایجاد کاربر جدید
    User::create([
        'name'      => $request->name,
        'last_name' => $request->last_name,
        'email'     => $request->email,
        'is_admin'  => $request->role,
        'section'   => $request->section,
        'image'     => $imagePath,
        'password'  => Hash::make($request->password),
    ]);

    Session::flash('success','کاربر با موفقیت ثبت شد.');
    return redirect()->route('user.index');
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
    public function edit(User $user)
    {
        return view('backend.users.edit')->with('user', $user);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required',
        'last_name' => 'required',
        'email' => 'required|email',
        'role' => 'required',
        'section' => 'required|in:Factory,Trade_al',
        'password' => 'nullable|min:8',
        'con_password' => 'nullable|same:password',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
    ], [
        'name.required' => 'وارد کردن نام الزامی است.',
        'last_name.required' => 'وارد کردن نام خانوادگی الزامی است.',
        'email.required' => 'وارد کردن ایمیل آدرس الزامی است.',
        'role.required' => 'وارد کردن نقش کاربر الزامی است.',
        'section.required' => 'انتخاب بخش کاربر الزامی است.',
        'section.in' => 'بخش انتخابی معتبر نیست.',
        'password.min' => 'پسورد باید حداقل 8 کاراکتر باشد.',
        'con_password.same' => 'تایید پسورد با پسورد مطابقت ندارد.',
        'image.image' => 'فایل آپلود شده باید یک تصویر باشد.',
        'image.mimes' => 'تصویر باید یکی از فرمت‌های jpg, jpeg, png, gif باشد.',
        'image.max' => 'حجم تصویر نمی‌تواند بیش از 2 مگابایت باشد.',
    ]);

    // تغییر پسورد در صورت پر شدن
    if (!empty($request->password)) {
        $user->password = Hash::make($request->password);
    }

    // ذخیره داده‌های اصلی
    $user->name = $request->name;
    $user->last_name = $request->last_name;
    $user->email = $request->email;
    $user->is_admin = $request->role;
    $user->section = $request->section;

    // آپلود تصویر در صورت انتخاب
    if ($request->hasFile('image')) {
        // حذف تصویر قبلی در صورت وجود
        if ($user->image && file_exists(storage_path('app/public/' . $user->image))) {
            unlink(storage_path('app/public/' . $user->image));
        }

        $path = $request->file('image')->store('users', 'public');
        $user->image = $path;
    }

    $user->save();

    Session::flash('success', 'کاربر با موفقیت ویرایش شد.');
    return redirect()->route('user.index');
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['success' => true]);
    }
}
