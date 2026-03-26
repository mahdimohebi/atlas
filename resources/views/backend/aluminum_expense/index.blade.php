@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">مصارف آلومینیوم</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <!-- دکمه ثبت مصرف جدید -->
                        <a href="{{ route('aluminum_expenses.create', ['type' => $type]) }}" class="btn btn-primary mb-3">
                            ثبت مصرف آلومینیوم
                        </a>
                        <input type="text" id="search" class="form-control mb-3" placeholder="جستجو در مصارف ...">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>نوع مصرف</th>
                                        <th>تاریخ</th>
                                        <th>قیمت</th>
                                        <th>یادداشت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="expense-data">
                                    @include('backend.aluminum_expense.table')
                                </tbody>
                            </table>
                        </div><br>
                        @include('backend.aluminum_expense.pagination')

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
function fa2en(str) {
    if(!str) return '';
    return str.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d))
              .replace(/[٠-٩]/g, d => '٠١٢٣٤٥٦٧٨٩'.indexOf(d));
}

$('#search').on('keyup', function() {
    let value = fa2en($(this).val().toLowerCase().trim());

    $('#expense-data tr').each(function() {
        let text = fa2en($(this).text().toLowerCase());
        $(this).toggle(text.includes(value));
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
                url: '/aluminum_expenses/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function() {
                    $('#row-' + id).remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'حذف شد!',
                        text: 'مصرف آلومینیوم با موفقیت حذف شد.',
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
