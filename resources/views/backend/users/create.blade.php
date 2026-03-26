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
                        <li class="breadcrumb-item active" aria-current="page">ثبت  کاربر</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

  

<form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row gy-4">

        <div class="col-md-6">
            <label class="form-label">نام</label>
            <input type="text" name="name" class="form-control" >
            @error('name') <p class="text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">نام خانوادگی</label>
            <input type="text" name="last_name" class="form-control" >
            @error('last_name') <p class="text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">ایمیل</label>
            <input type="email" name="email" class="form-control" >
            @error('email') <p class="text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">پسورد</label>
            <input type="password" name="password" class="form-control" >
            @error('password') <p class="text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">تایید پسورد</label>
            <input type="password" name="con_password" class="form-control" >
            @error('con_password') <p class="text-danger">{{ $message }}</p> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">صلاحیت</label>
            <select name="role" class="form-select">
                <option value="">انتخاب نقش</option>
                @php $roles = [1=>'Admin', 0=>'Author']; @endphp
                @foreach($roles as $value=>$role)
                    <option value="{{ $value }}">{{ $role }}</option>
                @endforeach
            </select>
            @error('role') <p class="text-danger">{{$message}}</p> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">بخش</label>
            <select name="section" class="form-select">
                <option value="">انتخاب بخش</option>
                @php $sections = ['Factory'=>'Factory', 'Trade_al'=>'Trade_al']; @endphp
                @foreach($sections as $value=>$section)
                    <option value="{{ $value }}">{{ $section }}</option>
                @endforeach
            </select>
            @error('section') <p class="text-danger">{{$message}}</p> @enderror
        </div>

        <div class="col-md-6">
            <label class="form-label">تصویر</label>
            <input type="file" name="image" class="form-control">
            @error('image') <p class="text-danger">{{$message}}</p> @enderror
        </div>

        <div class="col-12 text-center">
            <input type="submit" class="btn btn-success" value="ثبت کاربر">
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
