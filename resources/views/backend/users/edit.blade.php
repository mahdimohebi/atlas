@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ویرایش کاربر</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <form action="{{ route('user.update', ['user' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row gy-4">

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نام</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نام خانوادگی</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}">
                                    @error('last_name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">ایمیل آدرس</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">پسورد (تغییر در صورت تمایل)</label>
                                    <input type="password" name="password" class="form-control">
                                    @error('password')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تایید پسورد</label>
                                    <input type="password" name="con_password" class="form-control">
                                    @error('con_password')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">صلاحیت</label>
                                    <select name="role" class="form-select">
                                        @php
                                            $types = [1 => 'Admin', 0 => 'Author'];
                                        @endphp
                                        @foreach($types as $value => $type)
                                            <option value="{{ $value }}" {{ old('role', $user->is_admin) == $value ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">بخش</label>
                                    <select name="section" class="form-select">
                                        @php
                                            $sections = ['Factory' => 'کارخانه', 'Trade_al' => 'تامین آلومینیوم'];
                                        @endphp
                                        @foreach($sections as $value => $label)
                                            <option value="{{ $value }}" {{ old('section', $user->section) == $value ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تصویر کاربر</label>
                                    <input type="file" name="image" class="form-control">
                                    @if($user->image)
                                        <img src="{{ asset('storage/' . $user->image) }}" alt="User Image" class="mt-2" style="width:80px;height:80px;object-fit:cover;">
                                    @endif
                                    @error('image')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ویرایش کاربر">
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
