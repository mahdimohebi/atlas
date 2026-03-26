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
                        <li class="breadcrumb-item active" aria-current="page">ثبت معاش کارمند</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="{{ route('salary.store') }}" method="POST">
                            @csrf

                            <!-- آی‌دی کارمند (مخفی) -->
                            <input type="hidden" name="employee_id" value="{{ $employee->id }}">

                            <div class="row gy-4">

                                <!-- نام کارمند -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">کارمند</label>
                                    <input type="text" class="form-control" value="{{ $employee->name }} ({{ $employee->tazkira_no }})" disabled>
                                </div>

                                <!-- نوع معاش (از جدول کارمند گرفته شده) -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نوع معاش</label>
                                    <input type="text" class="form-control" value="{{ $employee->contract_type == 'ejaraei' ? 'اجاره‌ای' : 'روزمزد' }}" disabled>
                                    <input type="hidden" name="type" value="{{ $employee->contract_type }}">
                                </div>

                                <!-- مقدار -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="amount" class="form-label">مقدار</label>
                                    <input type="text" class="form-control" name="amount" id="amount" value="{{ old('amount') }}">
                                    @error('amount')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تاریخ پرداخت -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="date" class="form-label">تاریخ پرداخت</label>
                                    <input type="date" class="form-control" name="date" id="date" value="{{ old('date') }}">
                                    @error('date')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- وضعیت پرداختی-->
                                <div class="col-md-6 col-sm-12">
                                    <label for="status" class="form-label">وضعیت پرداختی</label>
                                    <select name="status" class="form-control">
                                        <option value="">انتخاب وضعیت پرداختی</option>
                                        <option value="1">پرداخت</option>
                                        <option value="0">پرداخت نشده</option>
                                    </select>
                                    @error('status')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- یادداشت -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="notes" class="form-label">یادداشت</label>
                                    <input type="text" class="form-control" name="notes" id="notes" value="{{ old('notes') }}">
                                    @error('notes')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- دکمه ثبت -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ثبت معاش">
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
