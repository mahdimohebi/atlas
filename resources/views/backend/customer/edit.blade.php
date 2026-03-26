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
                        <li class="breadcrumb-item active" aria-current="page">ویرایش مشتری</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <form action="{{ route('customer.update', $customer->id) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- متد PUT برای ویرایش -->
                            <div class="row gy-4">

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نام</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نام خانوادگی</label>
                                    <input type="text" name="lastname" class="form-control" value="{{ old('lastname', $customer->lastname) }}" required>
                                    @error('lastname')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">آدرس</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}" required>
                                    @error('address')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تلفن</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}" required>
                                    @error('phone')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ویرایش مشتری">
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
