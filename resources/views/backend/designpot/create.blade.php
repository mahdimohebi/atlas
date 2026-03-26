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
                        <li class="breadcrumb-item active" aria-current="page">ثبت دیزاین دیگ</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('design_pot.store') }}" method="POST">
                            @csrf
                            <div class="row gy-4">

                                <!-- کارمند دیزاینر -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">کارمند دیزاینر</label>
                                    <select name="employee_id" class="form-select" required>
                                        <option value="">انتخاب کارمند دیزاینر</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }} {{ $employee->father_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- تاریخ دیزاین -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ دیزاین</label>
                                    <input type="date" class="form-control" name="date" required>
                                </div>

                                <!-- نوعیت جنس -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نوعیت جنس</label>
                                    <select name="pot_type" id="pot_type" class="form-select" required>
                                        <option value="">انتخاب نوع جنس</option>
                                        @foreach($potTypes as $type)
                                            <option value="{{ $type->name }}" 
                                                    data-numbers='@json($type->potNumbers->pluck("pot_number"))'>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- شماره جنس -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">شماره جنس</label>
                                    <select name="pot_number" id="pot_number" class="form-select" disabled required>
                                        <option value="">ابتدا نوع جنس را انتخاب کنید</option>
                                    </select>
                                </div>

                                <!-- نوع دیزاین -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نوع دیزاین</label>
                                    <select name="design_type" class="form-select" required>
                                        <option value="">انتخاب نوع دیزاین</option>
                                        @foreach($design as $d)
                                            <option value="{{$d->name}}">{{$d->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- تعداد -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تعداد جنس</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity" required>
                                </div>

                                <!-- قیمت فی جنس -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت فی جنس (افغانی)</label>
                                    <input type="text" class="form-control" id="price_per_pot" name="price_per_pot" required>
                                </div>

                                <!-- قیمت مجموعی -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت مجموعی (افغانی)</label>
                                    <input type="text" class="form-control" id="total_price" name="total_price" readonly>
                                </div>

                                <!-- توضیحات -->
                                <div class="col-12">
                                    <label class="form-label">توضیحات</label>
                                    <textarea class="form-control" name="note" rows="3"></textarea>
                                </div>

                                <!-- دکمه ثبت -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ثبت دیزاین">
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
document.addEventListener('DOMContentLoaded', function () {

    const potTypeSelect = document.getElementById('pot_type');
    const potNumberSelect = document.getElementById('pot_number');
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price_per_pot');
    const totalPriceInput = document.getElementById('total_price');

    // تبدیل اعداد فارسی به انگلیسی
    function persianToEnglishNumber(str) {
        return str.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
    }

    // محاسبه قیمت مجموعی
    function calculateTotalPrice() {
        const quantity = parseFloat(persianToEnglishNumber(quantityInput.value).replace(/[^\d.]/g,'')) || 0;
        const price = parseFloat(persianToEnglishNumber(priceInput.value).replace(/[^\d.]/g,'')) || 0;
        totalPriceInput.value = (quantity * price).toFixed(2);
    }

    // پر کردن شماره‌ها بر اساس نوع جنس
    function fillNumbers() {
        const selectedOption = potTypeSelect.options[potTypeSelect.selectedIndex];
        let numbers = [];

        try {
            numbers = JSON.parse(selectedOption.dataset.numbers || '[]');
        } catch(e) {
            numbers = [];
        }

        // reset
        potNumberSelect.innerHTML = '<option value="">انتخاب شماره دیگ</option>';
        potNumberSelect.disabled = true;

        if(numbers.length > 0) {
            numbers.forEach(num => {
                potNumberSelect.innerHTML += `<option value="${num}">${num} نمبر</option>`;
            });
            potNumberSelect.disabled = false;
        }
    }

    // Event listener برای محاسبه مجموع
    [quantityInput, priceInput].forEach(el => {
        el.addEventListener('input', calculateTotalPrice);
        el.addEventListener('keyup', calculateTotalPrice);
        el.addEventListener('change', calculateTotalPrice);
    });

    // تغییر نوع جنس
    potTypeSelect.addEventListener('change', fillNumbers);

    // فعال‌سازی اولیه
    fillNumbers();
    calculateTotalPrice();
});
</script>
@endsection
