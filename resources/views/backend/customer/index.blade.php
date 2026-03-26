@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">مشتریان</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <!-- دکمه ثبت مشتری جدید -->
                        <a href="{{ route('customer.create') }}" class="btn btn-primary mb-3">ثبت مشتری</a>
                        <input type="text" id="search" class="form-control mb-3" placeholder="جستجو در مشتریان...">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>نام</th>
                                        <th>نام خانوادگی</th>
                                        <th>آدرس</th>
                                        <th>تلفن</th>
                                        <th>تعداد فروش‌ها</th>
                                        <th>مجموع پرداخت‌ها</th>
                                        <th>باقی‌مانده</th>
                                        <th>جمع کل</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="customer-data">
                                    @include('backend.customer.table')
                                </tbody>
                            </table>
                        </div><br>
                        @include('backend.customer.pagination')

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function() {
    function fetch_data(query = '', page = 1) {
        $.ajax({
            url: "{{ route('customer.search') }}",
            type: 'GET',
            data: { 
                query: query, 
                page: page 
            },
            success: function(data) {
                $('#customer-data').html(data.table);
                $('#pagination-links').html(data.pagination);
            }
        });
    }

    $('#search').on('keyup', function() {
        var query = $(this).val();
        fetch_data(query, 1);
    });

    $(document).on('click', '#pagination-links a', function(e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        var query = $('#search').val();
        fetch_data(query, page);
    });
});
</script>

<script>
$(document).on('click', '.delete', function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    Swal.fire({
        title: "آیا مطمئن هستید؟",
        text: "این عملیات قابل بازگشت نیست!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "بله، حذف شود!",
        cancelButtonText: "لغو"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/customer/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function() {
                    $('#row-' + id).remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'حذف شد!',
                        text: 'مشتری با موفقیت حذف شد.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function() {
                    Swal.fire('خطا!', 'مشکلی رخ داده است.', 'error');
                }
            });
        }
    });
});
</script>
@endsection
