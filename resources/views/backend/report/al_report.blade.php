@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">گزارش المونیم</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">


                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>المونیم</th>
                                        <th>مقدار کلی </th>
                                        <th>ضایعات</th>
                                        <th>مقدار خالص</th>
                                        <th>مصرف شده </th>
                                        <th> باقیمانده</th>

                                    </tr>
                                </thead>
                                <tbody id="customer-data">
                                    <tr>
                                        <td>نرم</td>
                                        <td>{{$total_quantity}}</td>
                                        <td>{{$total_waste}}</td>
                                        <td>{{$total_amount}}</td>
                                        <td>{{$total_used}}</td>
                                        <td>{{$remaining}}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div><br>


                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection


