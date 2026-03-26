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
                        <li class="breadcrumb-item active" aria-current="page">ویرایش مشتری ها</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <!-- Start::row-1 -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="{{ route('client.update', ['client' => $client->id]) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">

                                <!-- نام -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="name" class="form-label">نام</label>
                                    <input type="text" class="form-control" name="name" id="name" value="{{ $client->name }}">
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- نام پدر -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="f_name" class="form-label">نام پدر</label>
                                    <input type="text" class="form-control" name="f_name" id="f_name" value="{{ $client->f_name }}">
                                    @error('f_name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- آدرس -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="address" class="form-label">آدرس</label>
                                    <input type="text" class="form-control" name="address" id="address" value="{{ $client->address }}">
                                    @error('address')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تلفون -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="phone" class="form-label">تلفون</label>
                                    <input type="tel" class="form-control" name="phone" id="phone" value="{{ $client->phone }}">
                                    @error('phone')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- دکمه ثبت -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-primary" value="ویرایش">
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--End::row-1 -->

    </div>
</div>

@endsection
