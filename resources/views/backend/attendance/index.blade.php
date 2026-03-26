@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">حاضری روزانه</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <!-- نمایش تاریخ شمسی -->
                        <h5 class="mb-3">تاریخ امروز: {{ $jalaliDate }}</h5>


                        <form action="{{ route('attendance.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="date" value="{{ $today }}">

                            <div class="table-responsive">
                                <table class="table table-bordered table-striped text-nowrap w-100">
                                    <thead>
                                        <tr>
                                            <th>نام کارمند</th>
                                            <th>تلفن</th>
                                            <th>وضعیت حضور</th>
                                            <th>شرح / Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($employees as $employee)
                                        <tr>
                                            <td>
                                                <a href="{{ route('attendance.show', ['employee' => $employee->id]) }}" class="text-success">{{ $employee->name }} ({{ $employee->tazkira_no }})</a>
                                                
                                            </td>
                                            <td>{{ $employee->phone }}</td>
                                            <td>
                                                <select name="status[]" class="form-control">
                                                    <option value="present" {{ optional($employee->attendances->first())->status == 'present' ? 'selected' : '' }}>حاضر</option>
                                                    <option value="absent" {{ optional($employee->attendances->first())->status == 'absent' ? 'selected' : '' }}>غایب</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="description[]" class="form-control" 
                                                    value="{{ optional($employee->attendances->first())->description }}">
                                            </td>
                                            <input type="hidden" name="employee_id[]" value="{{ $employee->id }}">
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mt-3">
                                <input type="submit" class="btn btn-success" value="ثبت حاضری">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
