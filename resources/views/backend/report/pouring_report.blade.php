@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">گزارش ریخت ها</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">


                        <div class="table-responsive">
                            <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>نوع جنس</th>
                                    <th>شماره جنس</th>
                                    <th>زیر نوع جنس</th>
                                    <th>مجموع مقدار</th>
                                    <th>مجموع وزن</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($grouped as $item)
                                    <tr>
                                        <td>{{ $item['pot_type'] }}</td>
                                        <td>{{ $item['pot_number'] }}</td>
                                        <td>{{ $item['pot_sub_type'] }}</td>
                                        <td>{{ $item['total_quantity'] }}</td>
                                        <td>{{ $item['total_weight'] }}</td>
                                    </tr>
                                @endforeach
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


