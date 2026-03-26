@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid d-flex justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-xl-9">

            <!-- دکمه‌ها بالای کارت -->


            <div class="card custom-card printable-area" id="invoiceArea">
            <div class="mb-3 text-end no-print" style="margin: 20px;">
                        <a href="{{ route('customer_payment.create', $sale->id) }}" class="btn btn-sm btn-success me-1">
                            ثبت پرداخت جدید
                        </a>
                        <button class="btn btn-sm btn-secondary" onclick="window.print();">
                            چاپ <i class="ri-printer-line ms-1 align-middle d-inline-block"></i>
                        </button>
            </div>
                <!-- هدر کارت فقط لوگو و متن وسط -->
            <div class="card-header mb-3" style="min-height:150px; position:relative;">
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center;">
                    <div class="avatar avatar-lg mb-2">
                        <img src="{{ asset('assets/logo 2.png') }}" alt="Logo" style="max-height:100px;">
                    </div>
                    <h6 class="fw-bold mb-0">فاربریکه ریخت المونیم اطلس</h6>
                    <p class="mb-0">پرداخت‌ها برای فاکتور: <span class="text-primary">#{{ $sale->invoice_number ?? '' }}</span></p>
                </div>
            </div>

                <div class="card-body">


                    <!-- اطلاعات تراکنش -->
                        <div class="row mx-0 bg-primary bg-opacity-10 p-2 rounded mb-3 payment-info">
                            <div class="col-12 col-md-4 mb-1">
                                <p class="text-muted mb-0">مشتری:</p>
                                <p class="fw-bold mb-0">{{ optional($sale->customer)->first_name ?? '' }} {{ optional($sale->customer)->last_name ?? '' }}</p>
                                <p class="text-muted mb-0">شماره تماس: {{ optional($sale->customer)->phone ?? '-' }}</p>
                                <p class="text-muted mb-0">آدرس: {{ optional($sale->customer)->address ?? '-' }}</p>
                            </div>
                            <div class="col-12 col-md-4 mb-1">
                                @php
                                    $totalPrice = ($sale->items->sum('total_price') ?? 0) - ($sale->discount ?? 0);
                                    $totalPaid = ($sale->payments->sum('amount') ?? 0);
                                    $remaining = $totalPrice - $totalPaid;
                                @endphp
                                <p class="text-muted mb-0">جمع کل فاکتور:</p>
                                <p class="fw-bold mb-0">{{ number_format($totalPrice) }} AFN</p>
                                <p class="text-muted mb-0">پرداخت شده: {{ number_format($totalPaid) }} AFN</p>
                                <p class="text-muted mb-0">باقی‌مانده: {{ number_format($remaining) }} AFN</p>
                            </div>
                            <div class="col-12 col-md-4 mb-1">
                                <p class="text-muted mb-0">تعداد پرداخت‌ها: {{ $sale->payments->count() }}</p>
                            </div>
                        </div>

                    <!-- جدول پرداخت‌ها -->
                           <!-- جدول پرداخت‌ها -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th style="width:5%;">#</th>
                                        <th class="no-print" style="width:15%;">عملیات</th>
                                        <th style="width:25%;">تاریخ پرداخت</th>
                                        <th style="width:20%;">مبلغ (AFN)</th>
                                        @if($sale->payments->pluck('remarks')->filter()->count() > 0)
                                            <th style="width:35%;">یادداشت</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($sale->payments as $index => $payment)
                                    <tr id="row-{{ $payment->id }}">
                                        <td>{{ $index + 1 }}</td>
                                        <td class="no-print">
                                            <a href="{{ route('customer_payment.edit', $payment->id) }}" class="text-info"><i class="ti ti-pencil"></i></a> |
                                            <a href="#" data-id="{{ $payment->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a>
                                        </td>
                                        <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($payment->payment_date))->format('Y/m/d') }}</td>
                                        <td>{{ number_format($payment->amount) }}</td>
                                        @if($payment->remarks)
                                            <td class="note-cell">{{ $payment->remarks }}</td>
                                        @elseif($sale->payments->pluck('remarks')->filter()->count() > 0)
                                            <td class="note-cell"></td>
                                        @endif
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="{{ $sale->payments->pluck('remarks')->filter()->count() > 0 ? 5 : 4 }}" class="text-center text-muted">هیچ پرداختی ثبت نشده است</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!-- امضاها -->


                        </div>

                    <!-- امضاها -->
                    <div class="row mt-5 text-center">
                        <div class="col-6">
                            <p>امضای مدیر کارخانه</p>
                            <br><br>
                            <div style="border-bottom:1px solid #000; width: 60%; margin: auto;"></div>
                        </div>
                        <div class="col-6">
                            <p>امضای مشتری</p>
                            <br><br>
                            <div style="border-bottom:1px solid #000; width: 60%; margin: auto;"></div>
                        </div>
                    </div>

                    <!-- دکمه بازگشت -->
                    <div class="mt-3 text-end no-print">
                        <a href="{{ route('sales.index') }}" class="btn btn-secondary">بازگشت</a>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</div>

<style>
@media print {
      @page {
        margin: 10mm 10mm 10mm 10mm; /* فاصله امن از لبه */
    }
    html, body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        width: 100%;
    }

    .no-print { display: none !important; }

    /* Wrapper چاپ */
    .printable-area {
        display: table !important;        /* مهم: جدول مانند رفتار می‌کند */
        width: 180mm !important;
        margin: 0 0 0 0 !important;        /* وسط صفحه */
        padding: 1mm !important;
        background: #fff !important;
        font-size: 11px;
        text-align: center;
        box-shadow: none !important;
        border: none !important;
    }

    .printable-area .card,
    .printable-area .card-header,
    .printable-area .card-body {
        display: block !important;
        border: none !important;
        box-shadow: none !important;
        background: #fff !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        text-align: center !important;
    }

    /* لوگو و عنوان وسط */
    .card-header {
        display: block !important;
        text-align: center !important;
        min-height: 150px;
        margin-bottom: 20px;
        background: #fff !important;
    }

    .card-header img {
        display: block !important;
        margin: 0 auto !important;
        max-height: 100px;
    }

    /* جدول وسط و عرض کامل */
    table {
        width: 100% !important;
        border-collapse: collapse !important;
        margin: 0 auto !important;
        font-size: 10pt;
        background: #fff !important;
        page-break-inside: auto !important;
    }

    thead { display: table-header-group !important; }
    tfoot { display: table-footer-group !important; }
    tr { page-break-inside: avoid !important; page-break-after: auto !important; }

    th, td {
        border: 1px solid #8b8686ff !important;
        padding: 4px 5px;
        text-align: center !important;
        vertical-align: top;
        background: #fff !important;
        word-break: break-word;
    }

    .payment-info > div { flex: 1 1 45%; margin-bottom: 5px; }
    .row.mt-5.text-center { page-break-inside: avoid !important; }
}



</style>

@section('script')
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
                url: '/customer_payment/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function() {
                    $('#row-' + id).remove();
                    Swal.fire({ icon: 'success', title: 'حذف شد!', text: 'پرداخت با موفقیت حذف شد.', timer:1500, showConfirmButton:false });
                },
                error: function(xhr) {
                    let message = 'مشکلی رخ داده است.';
                    if(xhr.responseJSON && xhr.responseJSON.message){ message = xhr.responseJSON.message; }
                    Swal.fire('خطا!', message, 'error');
                }
            });
        }
    });
});
</script>
@endsection
