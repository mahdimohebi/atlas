@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">خرید المونیم برای فابریکه</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <!-- دکمه ثبت خرید جدید -->
                        <a href="{{ route('factory-purchases.create') }}" class="btn btn-primary mb-3">ثبت خرید المونیم</a>

                        <!-- سرچ ساده -->
                        <input type="text" id="search" class="form-control mb-3" placeholder="جستجو در خریدها...">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>نام فروشنده</th>
                                        <th>نام پدر</th>
                                        <th>دسته‌بندی</th>
                                        <th>تاریخ خرید</th>
                                        <th>مقدار (کیلوگرم)</th>
                                        <th>ضایعات (کیلوگرم)</th>
                                        <th>وزن خالص</th>
                                        <th>قیمت فی واحد</th>
                                        <th>جمع کل</th>
                                        <th>پرداخت شده</th>
                                        <th>مانده</th>
                                        <th>یادداشت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="factory-purchase-data">
                                    @forelse($purchases as $purchase)
                                        @php
                                            $totalPaid = $purchase->payments->sum('amount');
                                            $remaining = $purchase->total_price - $totalPaid;
                                        @endphp
                                        <tr id="row-{{ $purchase->id }}">
                                            <td>{{ $purchase->id }}</td>
                                            <td>{{ $purchase->name }}</td>
                                            <td>{{ $purchase->f_name }}</td>
                                            <td>{{ $purchase->category == 'soft' ? 'نرم' : 'سخت' }}</td>
                                            <td>{{ \Morilog\Jalali\Jalalian::forge($purchase->purchase_date)->format('Y/m/d') }}</td>
                                            <td>{{ number_format($purchase->quantity) }}</td>
                                            <td>{{ number_format($purchase->waste ?? 0) }}</td>
                                            <td>{{ number_format($purchase->net_weight) }}</td>
                                            <td>{{ number_format($purchase->price_per_unit) }}</td>
                                            <td class="fw-bold text-success">{{ number_format($purchase->total_price) }}</td>
                                            <td class="fw-bold text-primary">{{ number_format($totalPaid) }}</td>
                                            <td class="fw-bold text-danger">
                                                @if(number_format($remaining) == 0)
                                                پرداخت تکمیل شده است
                                                @else
                                                {{ number_format($remaining) }}
                                                @endif
                                            </td>
                                            <td>{{ $purchase->note ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('factory-purchase.payments', $purchase->id) }}" 
                                                   class="btn btn-sm btn-info">پرداخت‌ها</a>

                                                <a href="{{ route('factory-purchases.edit', $purchase->id) }}" 
                                                   class="btn btn-sm btn-warning">ویرایش</a>

                                                <button class="btn btn-sm btn-danger delete-purchase" data-id="{{ $purchase->id }}">
                                                    حذف
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="14" class="text-center text-muted">هیچ خریدی ثبت نشده است</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div id="pagination-links" class="mt-3">
                            {{ $purchases->links() }}
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
$(document).ready(function() {

    // سرچ ساده
    $('#search').on('keyup', function(){
        let value = $(this).val().toLowerCase();
        $('#factory-purchase-data tr').filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    // حذف خرید
    $(document).on('click', '.delete-purchase', function(){
        let id = $(this).data('id');
        Swal.fire({
            title: 'آیا مطمئن هستید؟',
            text: 'این عملیات قابل بازگشت نیست!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'بله، حذف شود',
            cancelButtonText: 'لغو'
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: '/factory-purchases/' + id,
                    type: 'DELETE',
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    success: function(){
                        $('#row-' + id).remove();
                        Swal.fire('حذف شد', 'رکورد با موفقیت حذف شد', 'success');
                    },
                    error: function(){
                        Swal.fire('خطا', 'مشکلی رخ داده است', 'error');
                    }
                });
            }
        });
    });

});
</script>
@endsection
