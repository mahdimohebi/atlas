@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ویرایش تراکنش</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form id="transactionForm" action="{{ route('transaction.update', $transaction->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row gy-4">

                                <!-- مشتری یا فروشنده -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label"> 
                                        @if($transaction->type == 'purchase') فروشنده @else مشتری @endif
                                    </label>
                                    <input type="text" class="form-control" 
                                        value="@if($transaction->type == 'purchase') {{ $transaction->supplier->name ?? '' }} @else {{ $transaction->client->name ?? '' }} @endif" disabled>
                                </div>

                                <!-- نوع تراکنش -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نوع تراکنش</label>
                                    <input type="text" class="form-control" value="@if($transaction->type == 'purchase') خرید @else فروش @endif" disabled>
                                </div>

                                <!-- دسته‌بندی -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">دسته‌بندی آلومینیوم</label>
                                    <select name="category" class="form-control" required>
                                        <option value="">انتخاب دسته‌بندی</option>
                                        <option value="soft" {{ $transaction->category == 'soft' ? 'selected' : '' }}>نرم</option>
                                        <option value="hard" {{ $transaction->category == 'hard' ? 'selected' : '' }}>سخت</option>
                                    </select>
                                    @error('category')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- مقدار -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">مقدار (کیلوگرم)</label>
                                    <input type="text" id="quantity" name="quantity" class="form-control" required value="{{ $transaction->quantity }}">
                                    @error('quantity')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- قیمت واحد -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت واحد</label>
                                    <input type="text" id="price_per_unit" name="price_per_unit" class="form-control" required value="{{ $transaction->price_per_unit }}">
                                    @error('price_per_unit')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- واحد پول -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">واحد پول</label>
                                    <select id="currency" name="currency" class="form-control" required>
                                        <option value="AFN" {{ $transaction->currency == 'AFN' ? 'selected' : '' }}>AFN</option>
                                        <option value="USD" {{ $transaction->currency == 'USD' ? 'selected' : '' }}>USD</option>
                                    </select>
                                </div>

                                <!-- نرخ روز (فقط برای USD) -->
                                <div class="col-md-6 col-sm-12" id="exchangeRateWrapper">
                                    <label class="form-label">نرخ روز (AFN)</label>
                                    <input type="text" id="exchange_rate" name="exchange_rate" class="form-control" 
                                        value="{{ $transaction->exchange_rate ?? '' }}">
                                    @error('exchange_rate')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- جمع کل -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">جمع کل</label>
                                    <input type="text" id="total_price" class="form-control" disabled value="">
                                </div>

                                <!-- تاریخ تراکنش -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ تراکنش</label>
                                    <input type="date" name="transaction_date" class="form-control" required value="{{ $transaction->transaction_date }}">
                                </div>

                                <!-- دکمه ثبت -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ویرایش تراکنش">
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
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price_per_unit');
    const currencySelect = document.getElementById('currency');
    const exchangeRateWrapper = document.getElementById('exchangeRateWrapper');
    const exchangeRateInput = document.getElementById('exchange_rate');
    const totalPriceInput = document.getElementById('total_price');

    // تبدیل اعداد فارسی به انگلیسی
    function convertToEnglishNumbers(str) {
        const persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
        for (let i = 0; i < 10; i++) str = str.replace(persianNumbers[i], i);
        return str.replace(/[^0-9.]/g, '');
    }

    // نمایش یا مخفی کردن نرخ روز
    function toggleExchangeRate() {
        if (currencySelect.value === 'USD') {
            exchangeRateWrapper.classList.remove('d-none');
            exchangeRateInput.setAttribute('required', 'required');
        } else {
            exchangeRateWrapper.classList.add('d-none');
            exchangeRateInput.removeAttribute('required');
            exchangeRateInput.value = '';
        }
        calculateTotal();
    }

    // محاسبه جمع کل
    function calculateTotal() {
        const quantity = parseFloat(convertToEnglishNumbers(quantityInput.value)) || 0;
        const price = parseFloat(convertToEnglishNumbers(priceInput.value)) || 0;
        const rate = parseFloat(convertToEnglishNumbers(exchangeRateInput?.value)) || 1;
        const currency = currencySelect.value;

        let total = quantity * price;
        if (currency === 'USD') {
            totalPriceInput.value = total.toFixed(2) + ' USD (' + (total * rate).toFixed(2) + ' AFN)';
        } else {
            totalPriceInput.value = total.toFixed(2) + ' AFN';
        }
    }

    quantityInput.addEventListener('input', calculateTotal);
    priceInput.addEventListener('input', calculateTotal);
    exchangeRateInput?.addEventListener('input', calculateTotal);
    currencySelect.addEventListener('change', toggleExchangeRate);

    // اجرای اولیه
    toggleExchangeRate();
}
</script>
@endsection
