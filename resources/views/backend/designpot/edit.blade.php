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
                        <li class="breadcrumb-item active" aria-current="page">ویرایش دیزاین دیگ</li>
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

                        <form action="{{ route('design_pot.update', $design->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="row gy-4">

                                <!-- کارمند دیزاینر -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">کارمند دیزاینر</label>
                                    <select name="employee_id" class="form-select" required>
                                        <option value="">انتخاب کارمند دیزاینر</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                {{ $design->employee_id == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }} {{ $employee->father_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- تاریخ دیزاین -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ دیزاین</label>
                                    <input type="date" class="form-control" name="date"
                                        value="{{ \Carbon\Carbon::parse($design->date)->format('Y-m-d') }}" required>
                                </div>

                                <!-- نوعیت جنس -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نوعیت جنس</label>
                                    <select name="pot_type" id="pot_type" class="form-select" required>
                                        <option value="">انتخاب نوع دیگ</option>
                                        @foreach($potTypes as $type)
                                            <option value="{{ $type->name }}"
                                                data-numbers='@json($type->potNumbers->pluck("pot_number"))'
                                                {{ $design->pot_type == $type->name ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- شماره جنس -->
                                <div class="col-md-6 col-sm-12" id="number_section">
                                    <label class="form-label">شماره جنس</label>
                                    <select name="pot_number" id="pot_number" class="form-select">
                                        <option value="">انتخاب شماره دیگ</option>
                                        @if($design->pot_type)
                                            @php
                                                $selectedType = $potTypes->firstWhere('id', $design->pot_type);
                                            @endphp
                                            @if($selectedType)
                                                @foreach($selectedType->potNumbers as $num)
                                                    <option value="{{ $num->pot_number }}"
                                                        {{ $design->pot_number == $num->pot_number ? 'selected' : '' }}>
                                                        {{ $num->pot_number }}
                                                    </option>
                                                @endforeach
                                            @endif
                                        @endif
                                    </select>
                                </div>

                                <!-- نوع دیزاین -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نوع دیزاین</label>
                                    <select name="design_type" class="form-select" required>
                                        <option value="">انتخاب نوع دیزاین</option>
                                        @foreach(['رنگ','پالش'] as $dType)
                                            <option value="{{ $dType }}"
                                                {{ $design->design_type == $dType ? 'selected' : '' }}>
                                                {{ $dType }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- تعداد -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تعداد جنس</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity"
                                        value="{{ $design->quantity }}">
                                </div>

                                <!-- قیمت فی جنس -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت فی جنس (افغانی)</label>
                                    <input type="text" class="form-control" id="price_per_pot" name="price_per_pot"
                                        value="{{ $design->price_per_pot }}">
                                </div>

                                <!-- قیمت مجموعی -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت مجموعی (افغانی)</label>
                                    <input type="text" class="form-control" id="total_price" name="total_price"
                                        value="{{ $design->total_price }}" readonly>
                                </div>

                                <!-- توضیحات -->
                                <div class="col-12">
                                    <label class="form-label">توضیحات</label>
                                    <textarea class="form-control" name="note" rows="3">{{ $design->note }}</textarea>
                                </div>

                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="بروزرسانی دیزاین">
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
document.addEventListener('DOMContentLoaded', function() {

    const potTypeSelect = document.getElementById('pot_type');
    const potNumberSelect = document.getElementById('pot_number');
    const numberSection = document.getElementById('number_section');
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price_per_pot');
    const totalPriceInput = document.getElementById('total_price');

    const potTypes = @json($potTypes);
    const initialPotNumber = "{{ $design->pot_number }}";

    // تبدیل اعداد فارسی به انگلیسی
    function persianToEnglishNumber(str) {
        const persianNumbers = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        const englishNumbers = ['0','1','2','3','4','5','6','7','8','9'];
        let result = str;
        persianNumbers.forEach((p, i) => result = result.replaceAll(p, englishNumbers[i]));
        return result;
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
        } catch(e) { numbers = []; }

        // همیشه گزینه پیش‌فرض
        potNumberSelect.innerHTML = '<option value="">انتخاب شماره دیگ</option>';
        potNumberSelect.disabled = true;

        // اگر شماره‌ای وجود داشت و نوعیت "کرایی" نیست → فعال و پر شود
        if(numbers.length > 0 && selectedOption.text !== 'کرایی') {
            numbers.forEach(num => {
                const selected = num == initialPotNumber ? 'selected' : '';
                potNumberSelect.innerHTML += `<option value="${num}" ${selected}>${num} نمبر</option>`;
            });
            potNumberSelect.disabled = false;
            numberSection.style.display = 'block';
        } else {
            // اگر شماره‌ای وجود ندارد → بخش مخفی
            numberSection.style.display = 'none';
        }
    }

    // کنترل نمایش شماره دیگ برای حالت اولیه و تغییر
    function toggleNumberSection() {
        fillNumbers(); // همیشه شماره‌ها را پر کن یا مخفی کن
    }

    // Event Listener
    [quantityInput, priceInput].forEach(el => {
        el.addEventListener('input', calculateTotalPrice);
        el.addEventListener('keyup', calculateTotalPrice);
        el.addEventListener('change', calculateTotalPrice);
    });

    potTypeSelect.addEventListener('change', toggleNumberSection);

    // فعال سازی اولیه
    toggleNumberSection();
    calculateTotalPrice();

});

</script>
@endsection
