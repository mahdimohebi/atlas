@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid d-flex justify-content-center">
        <div class="row w-100 justify-content-center">
            <div class="col-xl-9">

            <!-- دکمه‌ها بالای کارت -->


            <div class="card custom-card printable-area" id="invoiceArea">
             <div class="mb-3 text-end no-print" style="margin: 20px;">
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
                    <p> فاکتور خرید : <span class="text-primary">{{ $sale->invoice_number }}</span></p> 
                </div>
            </div>

                <div class="card-body">


                    <!-- اطلاعات تراکنش -->
                        <div class="row mx-0 bg-primary bg-opacity-10 p-2 rounded mb-3 payment-info">
                            <div class="col-xl-12">
                                <div class="row mx-0 ">
                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                                        <p class="text-muted mb-2">صدور صورتحساب از :</p>
                                        <p class="fw-bold mb-1">فابریکه ریخت المونیم اطلس</p>
                                        <p class="mb-1 text-muted">شماره فاکتور: {{ $sale->invoice_number }}</p>
                                        <p class="mb-1 text-muted">تاریخ خرید: 
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($sale->sale_date))->format('Y/m/d') }}
                                        </p>
                                    </div>

                                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6 ms-auto mt-sm-0 mt-3">
                                        <p class="text-muted mb-2">صورتحساب به:</p>
                                        <p class="fw-bold mb-1">{{ $sale->customer->first_name ?? '' }} {{ $sale->customer->last_name ?? '' }}</p>
                                        <p class="text-muted mb-1">آدرس: {{ $sale->customer->address ?? '-' }}</p>
                                        <p class="text-muted mb-1">شماره تماس: {{ $sale->customer->phone ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    <!-- جدول پرداخت‌ها -->
                           <!-- جدول پرداخت‌ها -->
                                <div class="table-responsive">
                                    <table class="table nowrap text-nowrap border mt-4">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>نوع جنس</th>
                                                <th>شماره جنس</th>
                                                <th>دیزاین</th>
                                                <th>تعداد</th>
                                                <th>فی دیگ (AFN)</th>
                                                <th>جمع جزئی (AFN)</th>
                                                <th>توضیحات</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i = 1; $total_price = 0; @endphp
                                            @foreach($sale->items as $item)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $item->pot_type }}</td>
                                                <td>{{ $item->pot_number ?? '-' }}</td>
                                                <td>{{$item->pot_design}}</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td>{{ number_format($item->unit_price) }}</td>
                                                <td>{{ number_format($item->total_price) }}</td>
                                                <td class="desc-cell">{{ $item->remarks ?? '-' }}</td>
                                            </tr>
                                            @php $total_price += $item->total_price; @endphp
                                            @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="6" class="text-end">جمع کل</th>
                                                <td colspan="2">{{ number_format($total_price) }} AFN</td>
                                            </tr>
                                            <tr>
                                                <th colspan="6" class="text-end">جمع کل نهایی</th>
                                                <td colspan="2"><strong>{{ number_format($total_price - $sale->discount) }} AFN</strong></td>
                                            </tr>
                                        </tfoot>
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
        margin: 0 0 0 10px!important;        /* وسط صفحه */
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
    font-size: 7pt !important;       /* کمی بزرگ‌تر برای خوانایی */
    background: #fff !important;
    page-break-inside: auto !important;
    table-layout: fixed;             /* ستون‌ها ثابت */
}

thead { display: table-header-group !important; }
tfoot { display: table-footer-group !important; }

tr { 
    page-break-inside: avoid !important; 
    page-break-after: auto !important; 
}

th, td {
    border: 1px solid #8b8686ff !important;
    padding: 4px 5px !important;
    text-align: center !important;
    vertical-align: top;
    background: #fff !important;
    word-break: break-word;         /* شکستن متن طولانی */
    overflow-wrap: break-word;      /* جلوگیری از overflow */
    max-width: 120px;               /* محدود کردن عرض ستون */
    white-space: normal;            /* اجازه به متن برای شکستن خط */
    font-size: 7pt !important;      /* افزایش فونت نسبت به 5pt */
}



    .payment-info > div { flex: 1 1 45%; margin-bottom: 5px; }
    .row.mt-5.text-center { page-break-inside: avoid !important; }
}



</style>

