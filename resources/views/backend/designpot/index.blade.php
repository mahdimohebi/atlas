@extends('backend.layouts.master')
@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">دیزاین دیگ‌ها</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <!-- دکمه ثبت دیزاین جدید -->
                        <a href="{{ route('design_pot.create') }}" class="btn btn-primary mb-3">ثبت دیزاین جدید</a>

                        <!-- سرچ ساده -->
                        <input type="text" id="search" class="form-control mb-3" placeholder="جستجو در دیزاین ها...">


                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>کارمند</th>
                                        <th>تاریخ</th>
                                        <th>تاریخ میلادی</th>
                                        <th>نوعیت جنس</th>
                                        <th>شماره جنس</th>
                                        <th>نوع دیزاین</th>
                                        <th>تعداد جنس</th>
                                        <th>قیمت فی جنس</th>
                                        <th>قیمت مجموعی</th>
                                        <th>یادداشت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="designpot-data">
                                    @include('backend.designpot.table')
                                </tbody>
                            </table>
                        </div>

                        <br>
                        @include('backend.designpot.pagination')

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
    // ------------------ سرچ ساده ------------------

function fa2en(str) {
    if (!str) return '';
    const fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    const en = ['0','1','2','3','4','5','6','7','8','9'];
    for(let i=0; i<10; i++){
        str = str.replaceAll(fa[i], en[i]);
    }
    return str;
}

$('#search').on('keyup', function(){
    let value = fa2en($(this).val().toLowerCase());

    $('#designpot-data tr').filter(function(){
        let text = fa2en($(this).text().toLowerCase());
        $(this).toggle(text.indexOf(value) > -1);
    });
});

    // ------------------ حذف رکورد ------------------
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
                    url: '/design_pot/' + id,
                    type: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function() {
                        $('#row-' + id).remove();
                        Swal.fire({
                            icon: 'success',
                            title: 'حذف شد!',
                            text: 'دیزاین با موفقیت حذف شد.',
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
});
</script>

@endsection
