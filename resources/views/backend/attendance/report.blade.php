@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">گزارش حاضری کارمندان روزمزد</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <!-- فیلترها -->
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <select id="filter" class="form-control">
                                    <option value="daily" @if($filter=='daily') selected @endif>روزانه</option>
                                    <option value="weekly" @if($filter=='weekly') selected @endif>هفتگی</option>
                                    <option value="monthly" @if($filter=='monthly') selected @endif>ماهانه</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" id="date" class="form-control" value="{{ $date }}">
                            </div>
                            <div class="col-md-2">
                                <button id="filterBtn" class="btn btn-primary">اعمال فیلتر</button>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>نام کارمند</th>
                                        <th>نام پدر</th>
                                        <th>تاریخ</th>
                                        <th>روز هفته</th>
                                        <th>وضعیت حضور</th>
                                        <th>توضیح / دلیل</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($employees as $employee)
                                        @foreach($employee->attendances as $att)
                                        <tr>
                                            <td>{{ $employee->name }}</td>
                                            <td>{{ $employee->father_name }}</td>
                                            <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($att->date)->format('Y/m/d') }}</td>
                                            <td>{{ $att->day_name }}</td>
                                            <td>
                                                @if($att->status=='present')
                                                    <span class="badge bg-success">حاضر</span>
                                                @else
                                                    <span class="badge bg-danger">غایب</span>
                                                @endif
                                            </td>
                                            <td>{{ $att->description ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
    $('#filterBtn').click(function(){
        var filter = $('#filter').val();
        var date = $('#date').val();
        var url = "{{ route('attendance.report') }}";
        window.location.href = url + "?filter=" + filter + "&date=" + date;
    });
</script>
@endsection
