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
                        <li class="breadcrumb-item active" aria-current="page">ثبت کارمندان</li>
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
                        <form action="{{ route('employee.store') }}" method="POST">
                            @csrf
                            <div class="row gy-4">

                                <!-- نام -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="name" class="form-label">نام</label>
                                    <input type="text" class="form-control" name="name" id="name" >
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- نام پدر -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="f_name" class="form-label">نام پدر</label>
                                    <input type="text" class="form-control" name="father_name" id="f_name" >
                                    @error('father_name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- آدرس -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="address" class="form-label">آدرس</label>
                                    <input type="text" class="form-control" name="address" id="address" >
                                    @error('address')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تلفون -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="phone" class="form-label">تلفون</label>
                                    <input type="tel" class="form-control" name="phone" id="phone" >
                                    @error('phone')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                 <!-- تذکره نمبر -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="tazkira_no" class="form-label">تذکره نمبر</label>
                                    <input type="tel" class="form-control" name="tazkira_no" id="tazkira_no" >
                                    @error('tazkira_no')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <!--  شغل -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="job_position" class="form-label">شغل</label>
                                    <select name="job_position" id="job_position" class="form-select">
                                        <option value="">انتخاب شغل</option>
                                        <option value="raikhtgar">ریخت گر</option>
                                        <option value="designer">دیزاینر</option>
                                    </select>
                                    @error('job_position')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>


                                <!--  نوعیت قرارداد -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="contract_type" class="form-label"> نوعیت قرارداد</label>
                                    <select name="contract_type" id="contract_type" class="form-select">
                                        <option value="">انتخاب نوعیت قرارداد</option>
                                        @php
                                            $contracts = ['ejaraei'=> 'اجاره ایی', 'rozmozd'=>'روزمزد'];
                                        @endphp
                                        @foreach($contracts as $con=> $label)
                                            <option value="{{ $con }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('contract_type')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>


                                <!-- دکمه ثبت -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value=" ثبت کارمندان">
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
