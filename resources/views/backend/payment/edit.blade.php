@extends('backend.layouts.master')
@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            ویرایش پرداخت #{{ $payment->id }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <h5>تراکنش: #{{ $payment->transaction->id }} - 
                            @if($payment->transaction->type == 'purchase') خرید @else فروش @endif
                        </h5>

                        <form action="{{ route('payment.update', $payment->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">

                                <!-- مبلغ پرداخت -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">مبلغ</label>
                                    <input type="text" id="amount" name="amount" class="form-control" required
                                        value="{{ $payment->amount }}">
                                    @error('amount')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- واحد پول -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">واحد پول</label>
                                    <select name="currency" id="currency" class="form-control" required>
                                        <option value="AFN" {{ $payment->currency=='AFN'?'selected':'' }}>AFN</option>
                                        <option value="USD" {{ $payment->currency=='USD'?'selected':'' }}>USD</option>
                                    </select>
                                </div>

                                <!-- نرخ ارز (برای USD) -->
                                <div class="col-md-6 col-sm-12 {{ $payment->currency=='USD'?'':'d-none' }}" id="exchangeWrapper">
                                    <label class="form-label">نرخ ارز (AFN per USD)</label>
                                    <input type="text" name="exchange_rate" id="exchange_rate" class="form-control" 
                                        value="{{ $payment->exchange_rate ?? '' }}">
                                </div>

                                <!-- تاریخ پرداخت -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ پرداخت</label>
                                    <input type="date" name="payment_date" class="form-control" required
                                        value="{{ $payment->payment_date }}">
                                </div>

                                <!-- باقی‌مانده تراکنش -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">باقی‌مانده بعد از پرداخت (AFN)</label>
                                    <input type="text" id="remaining_after" class="form-control" disabled>
                                </div>

                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ویرایش پرداخت">
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
    const exchangeWrapper = document.getElementById('exchangeWrapper');
    const exchangeInput = document.getElementById('exchange_rate');

    const totalAFN = {{ $payment->transaction->total_price }};
    const paidExcludingCurrentAFN = {{ $payment->transaction->payments()->where('id','!=',$payment->id)->get()->map(function($p){ return $p->currency=='USD' ? $p->amount*$p->exchange_rate : $p->amount; })->sum() }};

    function convertPersianNumbers(str) {
        if(!str) return 0;
        const persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        for(let i=0;i<10;i++){
            str = str.replace(new RegExp(persian[i],'g'), i);
        }
        return parseFloat(str.replace(/,/g,'')) || 0;
    }

    function toggleExchange() {
        if(currencySelect.value === 'USD'){
            exchangeWrapper.classList.remove('d-none');
            exchangeInput.setAttribute('required', 'required');
        } else {
            exchangeWrapper.classList.add('d-none');
            exchangeInput.removeAttribute('required');
            exchangeInput.value = '';
        }
        updateRemaining();
    }

    function updateRemaining() {
        const amount = convertPersianNumbers(amountInput.value);
        const rate = parseFloat(convertPersianNumbers(exchangeInput?.value)) || 1;
        const amountAFN = currencySelect.value==='USD' ? amount*rate : amount;
        const remaining = totalAFN - paidExcludingCurrentAFN - amountAFN;
        remainingAfter.value = remaining.toLocaleString(undefined,{minimumFractionDigits:2, maximumFractionDigits:2}) + ' AFN';
    }

    amountInput.addEventListener('input', updateRemaining);
    currencySelect.addEventListener('change', toggleExchange);
    exchangeInput?.addEventListener('input', updateRemaining);

    // محاسبه اولیه
    toggleExchange();
});
</script>
@endsection
