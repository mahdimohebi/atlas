@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid d-flex justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-xl-9">

            <!-- دکمه‌ها بالای کارت -->


            <div class="card custom-card printable-area" id="invoiceArea">
            <div class="mb-3 text-end no-print" style="margin: 20px;">
                <a href="{{ route('transaction.payment', $transaction->id) }}" class="btn btn-sm btn-success me-1">ثبت پرداخت جدید</a>
                <button class="btn btn-sm btn-secondary" onclick="window.print();">چاپ</button>
            </div>
                <!-- هدر کارت فقط لوگو و متن وسط -->
            <div class="card-header mb-3" style="min-height:150px; position:relative;">
                <div style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); text-align:center;">
                    <div class="avatar avatar-lg mb-2">
                        <img src="{{ asset('assets/logo 2.png') }}" alt="Logo" style="max-height:100px;">
                    </div>
                    <h6 class="fw-bold mb-0">فاربریکه ریخت المونیم اطلس</h6>
                    <p class="mb-0">پرداخت‌ها برای تراکنش: <span class="text-primary">#{{ $transaction->id }}</span></p>
                </div>
            </div>

                <div class="card-body">
                    @php
                        $totalAFN = $transaction->total_price;
                        $totalPaidAFN = 0;
                        foreach($transaction->payments as $p){
                            $totalPaidAFN += $p->currency === 'USD' ? $p->amount * $p->exchange_rate : $p->amount;
                        }
                        $remainingAFN = $totalAFN - $totalPaidAFN;
                    @endphp

                    <!-- اطلاعات تراکنش -->
                    <div class="row mx-0 bg-primary bg-opacity-10 p-2 rounded mb-3 payment-info">
                        <div class="col-12 col-md-4 mb-1">
                            <p class="text-muted mb-0">نوع تراکنش:</p>
                            <p class="fw-bold mb-0">@if($transaction->type == 'purchase') خرید @else فروش @endif</p>
                            <p class="text-muted mb-0">اسم مشتری :</p>
                            <p class="fw-bold mb-0">
                                @if($transaction->type == 'purchase')
                                    {{ $transaction->supplier->name ?? '-' }}
                                @else
                                    {{ $transaction->client->name ?? '-' }}
                                @endif
                            </p>
                            <p class="text-muted mb-0">اسم پدر :</p>
                            <p class="fw-bold mb-0">
                                @if($transaction->type == 'purchase')
                                    {{ $transaction->supplier->f_name ?? '-' }}
                                @else
                                    {{ $transaction->client->f_name ?? '-' }}
                                @endif
                            </p>
                        </div>
                        <div class="col-12 col-md-4 mb-1">
                            <p class="text-muted mb-0">مجموع تراکنش:</p>
                            <p class="fw-bold mb-0" id="total_price">{{ number_format($totalAFN) }} افغانی</p>
                            <p class="text-muted mb-0">پرداخت شده:</p>
                            <p class="fw-bold mb-0" id="paid_amount">{{ number_format($totalPaidAFN) }} افغانی</p>
                            <p class="text-muted mb-0">باقی‌مانده:</p>
                            <p class="fw-bold mb-0" id="remaining_amount">{{ number_format($remainingAFN) }} افغانی</p>
                        </div>
                        <div class="col-12 col-md-4 mb-1">
                            <p class="text-muted mb-0">تعداد پرداخت‌ها: {{ $transaction->payments()->count() }}</p>
                        </div>
                    </div>

                    <!-- جدول پرداخت‌ها -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th class="no-print">عملیات</th>
                                    <th>تاریخ پرداخت</th>
                                    <th>مبلغ</th>
                                    <th>نرخ ارز</th>
                                    <th>مجموع</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaction->payments as $index => $payment)
                                <tr id="row-{{ $payment->id }}">
                                    <td>{{ $index + 1 }}</td>
                                    <td class="no-print">
                                        <a href="{{ route('payment.edit', $payment->id) }}" class="text-info"><i class="ti ti-pencil"></i></a> |
                                        <a href="#" data-id="{{ $payment->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a>
                                    </td>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($payment->payment_date))->format('Y/m/d') }}</td>
                                    <td>@if($payment->currency === 'USD') {{ number_format($payment->amount) }} USD @else {{ number_format($payment->amount) }} AFN @endif</td>
                                    <td>@if($payment->currency === 'USD') {{ number_format($payment->exchange_rate) }} @endif</td>
                                    <td>@if($payment->currency === 'USD') {{ number_format($payment->amount * $payment->exchange_rate) }} AFN @else {{ number_format($payment->amount) }} AFN @endif</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">هیچ پرداختی ثبت نشده است</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
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

                        @php
                            if($transaction->type == 'purchase') {
                                $page = 'purchase';
                            } else {
                                $page = 'sale';
                            }
                        @endphp
                                    
                        <a href="{{ route('transaction.index', ['type' => $page]) }}" class="btn btn-secondary">بازگشت</a>
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
    if(!confirm('آیا مطمئن هستید؟ این عملیات قابل بازگشت نیست!')) return;

    $.ajax({
        url: '/payment/' + id,
        type: 'DELETE',
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function() {
            $('#row-' + id).remove();
            var paidAFN = 0;
            $('.table tbody tr').each(function() {
                var amountText = $(this).find('td:eq(5)').text();
                paidAFN += parseFloat(amountText.replace(/[^0-9\.]/g,''));
            });
            var totalAFN = parseFloat($('#total_price').text().replace(/[^0-9\.]/g,''));
            var remainingAFN = totalAFN - paidAFN;
            $('#paid_amount').text(paidAFN.toLocaleString() + ' افغانی');
            $('#remaining_amount').text(remainingAFN.toLocaleString() + ' افغانی');
            alert('پرداخت با موفقیت حذف شد!');
        },
        error: function() { alert('مشکلی رخ داده است.'); }
    });
});
</script>
@endsection
