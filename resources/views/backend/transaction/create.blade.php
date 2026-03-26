@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">
                            @if($type == 'purchase')
                                ثبت خرید از فروشنده
                            @else
                                ثبت فروش به مشتری
                            @endif
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        
                        <h5 class="mb-3">خلاصه تراکنش‌ها</h5>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-center">
                                <thead class="table-dark">
                                    <tr>
                                        <th>دسته‌بندی</th>
                                        <th>کل مقدار</th>
                                        <th>فروش شده</th>
                                        <th>باقیمانده</th>
                                        <th>واحد</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="table-light">
                                        <td>المونیم نرم</td>
                                        <td>{{ $summary['soft_total_purchase'] ?? 0 }}</td>
                                        <td class="text-success">{{ $summary['soft_total_sale'] ?? 0 }}</td>
                                        <td class="text-danger">{{ $summary['remaining_soft'] ?? 0 }}</td>
                                        <td>کیلوگرم</td>
                                    </tr>
                                    <tr class="table-light">
                                        <td>المونیم سخت</td>
                                        <td>{{ $summary['hard_total_purchase'] ?? 0 }}</td>
                                        <td class="text-success">{{ $summary['hard_total_sale'] ?? 0 }}</td>
                                        <td class="text-danger">{{ $summary['remaining_hard']  ?? 0 }}</td>
                                        <td>کیلوگرم</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> <br>
                        <form id="transactionForm" action="@if($type == 'purchase') {{ route('transactions.storeForSupplier', $supplier->id) }} @else {{ route('transactions.storeForClient', $client->id) }} @endif" method="POST">
                            @csrf
                            <div class="row gy-4">

                                <!-- مشتری یا فروشنده -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">
                                        @if($type == 'purchase') فروشنده @else مشتری @endif
                                    </label>
                                    <input type="text" class="form-control" 
                                        value="@if($type == 'purchase') {{ $supplier->name }} @else {{ $client->name }} @endif" disabled>
                                </div>

                                <!-- دسته‌بندی آلومینیوم -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">دسته‌بندی آلومینیوم</label>
                                    <select name="category" class="form-control" >
                                        <option value="">انتخاب دسته‌بندی</option>
                                        <option value="نرم">نرم</option>
                                        <option value="سخت">سخت</option>
                                    </select>
                                    @error('category')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- مقدار -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">مقدار (کیلوگرم)</label>
                                    <input type="text" id="quantity" name="quantity" class="form-control" >
                                    @error('quantity')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- قیمت واحد -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت واحد</label>
                                    <input type="text" id="price_per_unit" name="price_per_unit" class="form-control" >
                                    @error('price_per_unit')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- واحد پول -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">واحد پول</label>
                                    <select id="currency" name="currency" class="form-control" >
                                        <option value="AFN" {{ old('currency') == 'AFN' ? 'selected' : '' }}>AFN</option>
                                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>USD</option>
                                    </select>
                                     @error('currency')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- نرخ روز (فقط برای USD) -->
                                <div class="col-md-6 col-sm-12 d-none" id="exchangeRateWrapper">
                                    <label class="form-label">نرخ روز (USD → AFN)</label>
                                    <input type="text" id="exchange_rate" name="exchange_rate" class="form-control">
                                    @error('exchange_rate')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>


                                <!-- جمع کل -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">جمع کل</label>
                                    <input type="text" id="total_price" class="form-control" disabled value="0">
                                </div>

                                <!-- تاریخ تراکنش -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ تراکنش</label>
                                    <input type="date" name="transaction_date" class="form-control" >
                                    @error('transaction_date')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                              <!-- یادداشت-->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">یاد داشت</label>
                                    <input type="text" name="description" class="form-control" >
                                </div>
                                <!-- دکمه ثبت -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="@if($type == 'purchase') ثبت خرید @else ثبت فروش @endif">
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- جاوااسکریپت محاسبه لایو با پشتیبانی اعداد فارسی و واحد پول -->
<script>
window.onload = function() {
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price_per_unit');
    const currencySelect = document.getElementById('currency');
    const totalPriceInput = document.getElementById('total_price');
    const exchangeRateWrapper = document.getElementById('exchangeRateWrapper');
    const exchangeRateInput = document.getElementById('exchange_rate');

    // تبدیل اعداد فارسی به انگلیسی
    function convertToEnglishNumbers(str) {
        const persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
        for (let i = 0; i < 10; i++) {
            str = str.replace(persianNumbers[i], i);
        }
        return str.replace(/[^0-9.]/g, '');
    }

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

        function calculateTotal() {
            const quantity = parseFloat(convertToEnglishNumbers(quantityInput.value)) || 0;
            const price = parseFloat(convertToEnglishNumbers(priceInput.value)) || 0;
            const rate = parseFloat(convertToEnglishNumbers(exchangeRateInput?.value)) || 1;
            const currency = currencySelect.value;

            let total = quantity * price;



        if (currency === 'USD') {
        const totalAFN = total * rate;
        totalPriceInput.value = 
        totalAFN.toFixed() + ' افغانی'  + ' | ' +
            total.toFixed() + ' USD';
            
        } else {
            totalPriceInput.value = total.toFixed() + ' AFN';
        }

    }

    quantityInput.addEventListener('input', calculateTotal);
    priceInput.addEventListener('input', calculateTotal);
    exchangeRateInput?.addEventListener('input', calculateTotal);
    currencySelect.addEventListener('change', toggleExchangeRate);

    toggleExchangeRate(); // اجرای اولیه
};
</script>


@endsection
