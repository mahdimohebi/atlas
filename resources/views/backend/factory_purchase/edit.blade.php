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
                        <li class="breadcrumb-item active" aria-current="page">ویرایش خرید فابریکه</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Form Card -->
        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <form action="{{ route('factory-purchases.update', $purchase->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">

                                <!-- نام فروشنده -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نام فروشنده</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $purchase->name) }}">
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- نام پدر -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نام پدر</label>
                                    <input type="text" name="f_name" class="form-control" value="{{ old('f_name', $purchase->f_name) }}">
                                    @error('f_name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- دسته‌بندی المونیم -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">دسته‌بندی المونیم</label>
                                    <select name="category" class="form-control">
                                        <option value="">انتخاب دسته‌بندی</option>
                                        <option value="soft" {{ old('category', $purchase->category) == 'soft' ? 'selected' : '' }}>نرم</option>
                                        <option value="hard" {{ old('category', $purchase->category) == 'hard' ? 'selected' : '' }}>سخت</option>
                                    </select>
                                    @error('category')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تاریخ خرید -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ خرید</label>
                                    <input type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date', $purchase->purchase_date) }}">
                                    @error('purchase_date')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- مقدار -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">مقدار (کیلوگرم)</label>
                                    <input type="text" name="quantity" id="quantity" class="form-control" value="{{ old('quantity', $purchase->quantity) }}">
                                    @error('quantity')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- ضایعات -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">ضایعات (اختیاری)</label>
                                    <input type="text" name="waste" id="waste" class="form-control" value="{{ old('waste', $purchase->waste) }}">
                                    @error('waste')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- وزن خالص -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">وزن خالص (کیلوگرم)</label>
                                    <input type="text" name="net_weight" id="net_weight" class="form-control" value="{{ old('net_weight', $purchase->net_weight) }}" readonly>
                                    @error('net_weight')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- قیمت فی واحد -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت فی واحد (افغانی)</label>
                                    <input type="text" name="price_per_unit" id="price_per_unit" class="form-control" value="{{ old('price_per_unit', $purchase->price_per_unit) }}">
                                    @error('price_per_unit')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- جمع کل -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">جمع کل (افغانی)</label>
                                    <input type="text" name="total_price" id="total_price" class="form-control" readonly value="{{ old('total_price', $purchase->total_price) }}">
                                    @error('total_price')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- یادداشت -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">یادداشت (اختیاری)</label>
                                    <input type="text" name="note" class="form-control" value="{{ old('note', $purchase->note) }}">
                                </div>

                                <!-- دکمه بروزرسانی -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-primary" value="بروزرسانی خرید فابریکه">
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JS محاسبه خودکار وزن خالص و جمع کل -->
<script>
window.onload = function() {
    const quantityInput = document.getElementById('quantity');
    const wasteInput = document.getElementById('waste');
    const netWeightInput = document.getElementById('net_weight');
    const unitPriceInput = document.getElementById('price_per_unit');
    const totalPriceInput = document.getElementById('total_price');

    function convertToEnglishNumbers(str) {
        const persianNumbers = [/۰/g, /۱/g, /۲/g, /۳/g, /۴/g, /۵/g, /۶/g, /۷/g, /۸/g, /۹/g];
        for (let i = 0; i < 10; i++) {
            str = str.replace(persianNumbers[i], i);
        }
        return str.replace(/[^0-9.]/g, '');
    }

    function calculateNetAndTotal() {
        const quantity = parseFloat(convertToEnglishNumbers(quantityInput.value)) || 0;
        const waste = parseFloat(convertToEnglishNumbers(wasteInput.value)) || 0;
        const unitPrice = parseFloat(convertToEnglishNumbers(unitPriceInput.value)) || 0;

        const netWeight = Math.max(quantity - waste, 0);
        netWeightInput.value = netWeight;

        const total = netWeight * unitPrice;
        totalPriceInput.value = total.toFixed(0); // افغانی
    }

    quantityInput.addEventListener('input', calculateNetAndTotal);
    wasteInput.addEventListener('input', calculateNetAndTotal);
    unitPriceInput.addEventListener('input', calculateNetAndTotal);

    calculateNetAndTotal();
};
</script>

@endsection
