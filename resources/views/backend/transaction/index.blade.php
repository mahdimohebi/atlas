@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">تراکنش‌ها</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
  
          

       
                        <!-- جستجو -->
                         <input type="text" id="search" class="form-control mb-3" placeholder="جستجو در تراکنش ها...">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>نمبر تراکنش</th>
                                        <th>نوع تراکنش</th>
                                        <th>مشتری / فروشنده</th>
                                        <th>دسته‌بندی</th>
                                        <th>تاریخ</th>
                                        <th>مقدار (کیلوگرام)</th>
                                        <th>قیمت واحد</th>
                                        <th> نرخ ارز</th>
                                        <th>جمع کل</th>
                                        <th>پرداخت</th>
                                        <th>باقیمانده</th>                                  
                                        <th>یاد داشت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="transaction-data">
                                    @include('backend.transaction.table') {{-- partial برای رندر تراکنش‌ها --}}
                                </tbody>
                            </table>
                        </div>
                        <br>
                        @include('backend.transaction.pagination') {{-- partial pagination --}}
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

    function getTypeFromUrl() {
        const params = new URLSearchParams(window.location.search);
        return params.get('type'); // purchase یا sale
    }

    function fetch_data(query = '', page = 1) {
        $.ajax({
            url: "{{ route('transaction.search') }}",
            type: 'GET',
            data: {
                query: query,
                page: page,
                type: getTypeFromUrl() 
            },
            success: function(data) {
                $('#transaction-data').html(data.table);
                $('#pagination-links').html(data.pagination);
            }
        });
    }

    $('#search').on('keyup', function() {
        fetch_data($(this).val(), 1);
    });

    $(document).on('click', '#pagination-links a', function(e) {
        e.preventDefault();
        const page = $(this).attr('href').split('page=')[1];
        fetch_data($('#search').val(), page);
    });

});


</script>
<script>
// حذف تراکنش
$(document).on('click', '.delete-transaction', function(e) {
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
                url: '/transaction/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function() {
                    $('#row-' + id).remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'حذف شد!',
                        text: 'تراکنش با موفقیت حذف شد.',
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
