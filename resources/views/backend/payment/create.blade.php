@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ثبت پرداخت</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- اطلاعات تراکنش -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-light p-3">
                    <h5>اطلاعات تراکنش</h5>
                    @php
                        // همه مقادیر فقط به AFN هستند
                        $transactionAFN = $transaction->total_price;
                        $paidAFN = $totalPaid;
                        $remainingAFN = $transactionAFN - $paidAFN;
                    @endphp
                    <p>مجموع کل تراکنش: <strong>{{ number_format($transactionAFN) }} افغانی</strong></p>
                    <p>پرداخت شده: <strong>{{ number_format($paidAFN) }} افغانی</strong></p>
                    <p>باقی‌مانده قبل از پرداخت: <strong id="remaining_before">{{ number_format($remainingAFN) }}</strong> افغانی</p>
                    <p>این پرداخت: <strong>پرداخت شماره {{ $transaction->payments()->count() + 1 }}</strong></p>
                    <p>باقی‌مانده بعد از این پرداخت: <strong id="remaining_after">{{ number_format($remainingAFN) }}</strong> افغانی</p>

                </div>
            </div>
        </div>

        <!-- فرم پرداخت -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
<form id="paymentForm" action="{{ route('transaction.paymentstore', $transaction->id) }}" method="POST">
    @csrf
    <div class="row gy-4">

        <!-- مبلغ -->
        <div class="col-md-6 col-sm-12">
            <label class="form-label">مبلغ</label>
            <input type="text" id="amount" name="amount" class="form-control" required value="{{ old('amount') }}">
            @error('amount')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <!-- واحد پول -->
        <div class="col-md-6 col-sm-12">
            <label class="form-label">واحد پول</label>
            <select name="currency" id="currency" class="form-control" required>
                <option value="AFN" {{ old('currency')=='AFN'?'selected':'' }}>AFN</option>
                <option value="USD" {{ old('currency')=='USD'?'selected':'' }}>USD</option>
            </select>
            @error('currency')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <!-- نرخ ارز -->
        <div class="col-md-6 col-sm-12 d-none" id="exchangeRateWrapper">
            <label class="form-label">نرخ ارز روز (AFN per USD)</label>
            <input type="text" id="exchange_rate" name="exchange_rate" class="form-control" value="{{ old('exchange_rate') }}">
            @error('exchange_rate')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <!-- تاریخ پرداخت -->
        <div class="col-md-6 col-sm-12">
            <label class="form-label">تاریخ پرداخت</label>
            <input type="date" name="payment_date" class="form-control" required value="{{ old('payment_date') }}">
            @error('payment_date')
                <p class="text-danger">{{ $message }}</p>
            @enderror
        </div>

        <!-- دکمه ثبت -->
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
window.addEventListener('DOMContentLoaded', function() {
    const amountInput = document.getElementById('amount');
    const remainingAfter = document.getElementById('remaining_after');
    const currencySelect = document.getElementById('currency');
    const exchangeWrapper = document.getElementById('exchangeRateWrapper');
    const exchangeInput = document.getElementById('exchange_rate');

    // مقادیر AFN مستقیم از دیتابیس هستند، بدون ضرب نرخ ارز
    const totalAFN = {{ $transaction->total_price }};
    const paidAFN  = {{ $totalPaid }};

    function convertToEnglishNumbers(str) {
        if(!str) return '';
        const persian = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
        const arabic  = [/٠/g, /١/g, /٢/g, /٣/g, /٤/g, /٥/g, /٦/g, /٧/g, /٨/g, /٩/g];
        for (let i = 0; i < 10; i++) {
            str = str.replace(persian[i], i).replace(arabic[i], i);
        }
        return str.replace(/[^0-9.]/g, '');
    }

    function toggleExchangeRate() {
        if(currencySelect.value === 'USD') {
            exchangeWrapper.classList.remove('d-none');
            exchangeInput.setAttribute('required','required');
        } else {
            exchangeWrapper.classList.add('d-none');
            exchangeInput.removeAttribute('required');
            exchangeInput.value = null; // مهم: برای AFN مقدار null ارسال شود
        }
    }


    function updateRemaining() {
        let rawAmount = convertToEnglishNumbers(amountInput.value);
        let payment = parseFloat(rawAmount) || 0;
        let rate = parseFloat(convertToEnglishNumbers(exchangeInput?.value)) || 1;

        let remainingAFNCalc;

        if(currencySelect.value === 'USD') {
            // مبلغ به دلار وارد شده، به AFN تبدیل شود
            remainingAFNCalc = totalAFN - paidAFN - (payment * rate);
        } else {
            remainingAFNCalc = totalAFN - paidAFN - payment;
        }

        remainingAfter.textContent = remainingAFNCalc.toLocaleString() ;
    }

    // Event ها
    currencySelect.addEventListener('change', toggleExchangeRate);
    amountInput.addEventListener('input', updateRemaining);
    exchangeInput?.addEventListener('input', updateRemaining);

    document.getElementById('paymentForm').addEventListener('submit', function() {
        amountInput.value = convertToEnglishNumbers(amountInput.value);
        if(exchangeInput){
            exchangeInput.value = convertToEnglishNumbers(exchangeInput.value) || null;
        }
    });


    toggleExchangeRate();
});

</script>
@endsection
