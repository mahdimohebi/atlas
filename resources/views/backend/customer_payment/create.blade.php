@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Breadcrumb -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">پرداخت‌ها</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ثبت پرداخت</li>
                    </ol>
                </nav>
            </div>
        </div>


<!-- اطلاعات فاکتور -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-light p-3">
            <h5 class="mb-3">اطلاعات فاکتور</h5>
            <div class="row">
                <!-- ستون اول -->
                <div class="col-md-6">
                    <p>شماره فاکتور: <strong>{{ $sale->invoice_number }}</strong></p>
                    <p>مشتری: 
                        <strong>{{ $sale->customer->first_name }} {{ $sale->customer->last_name }}</strong>
                    </p>
                    <p>جمع کل: <strong>{{ number_format($sale->items->sum('total_price')) }} AFN</strong></p>
                    <p>تخفیف: <strong>{{ number_format($sale->discount ?? 0) }} AFN</strong></p>
                </div>

                <!-- ستون دوم -->
                <div class="col-md-6">
                    <p>نهایی: 
                        <strong>{{ number_format($sale->items->sum('total_price') - ($sale->discount ?? 0)) }} AFN</strong>
                    </p>
                    <p>پرداخت شده: <strong>{{ number_format($totalPaid) }} AFN</strong></p>
                    <p>باقی‌مانده قبل از پرداخت: 
                        <strong id="remaining_before">
                            {{ number_format(($sale->items->sum('total_price') - ($sale->discount ?? 0)) - $totalPaid) }}
                        </strong> AFN
                    </p>
                    <p>پرداخت شماره: <strong>{{ $sale->payments()->count() + 1 }}</strong></p>
                    <p>باقی‌مانده بعد از این پرداخت: 
                        <strong id="remaining_after">
                            {{ number_format(($sale->items->sum('total_price') - ($sale->discount ?? 0)) - $totalPaid) }}
                        </strong> AFN
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


        <!-- فرم ثبت پرداخت -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="{{ route('customer_payment.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="sale_id" value="{{ $sale->id }}">
                            <input type="hidden" name="customer_id" value="{{ $sale->customer_id }}">

                            <div class="row gy-4">

                                <!-- مبلغ -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">مبلغ پرداختی</label>
                                    <input type="text" id="amount" name="amount" class="form-control" required>
                                    @error('amount')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تاریخ پرداخت -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ پرداخت</label>
                                    <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                    @error('payment_date')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- یادداشت -->
                                <div class="col-md-12">
                                    <label class="form-label">یادداشت</label>
                                    <textarea name="remarks" class="form-control"></textarea>
                                </div>

                                <!-- دکمه -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ثبت پرداخت">
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
window.onload = function() {
    const amountInput = document.getElementById('amount');
    const remainingAfter = document.getElementById('remaining_after');
    const total = {{ $sale->items->sum('total_price') - ($sale->discount ?? 0) }};
    const paid = {{ $totalPaid }};

    function convertToEnglishNumbers(str) {
        const persian = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
        const arabic  = [/٠/g, /١/g, /٢/g, /٣/g, /٤/g, /٥/g, /٦/g, /٧/g, /٨/g, /٩/g];
        for (let i = 0; i < 10; i++) {
            str = str.replace(persian[i], i).replace(arabic[i], i);
        }
        return str;
    }

    amountInput.addEventListener('input', function() {
        let raw = convertToEnglishNumbers(amountInput.value);
        let payment = parseFloat(raw.replace(/,/g, '')) || 0;
        let remaining = total - paid - payment;
        remainingAfter.textContent = remaining.toLocaleString();
    });
}
</script>
@endsection
