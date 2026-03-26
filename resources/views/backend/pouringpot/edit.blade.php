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
                        <li class="breadcrumb-item active" aria-current="page">ویرایش ریخت</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

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

                        <form action="{{ route('pouring_pot.update', $pouring->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">کارمند ریخت‌گر</label>
                                    <select name="employee_id" class="form-select">
                                        <option value="">انتخاب کارمند ریخت‌گر</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}" {{ $pouring->employee_id == $employee->id ? 'selected' : '' }}>
                                                {{ $employee->name }} {{ $employee->father_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ</label>
                                    <input type="date" class="form-control" name="date" value="{{ $pouring->date }}">
                                </div>


<div class="col-md-6 col-sm-12">
    <label class="form-label">نوعیت جنس</label>
    <select name="pot_type" id="pot_type" class="form-select">
        <option value="">انتخاب نوع دیگ</option>
        @foreach($potTypes as $type)
            <option value="{{ $type->name }}" {{ $pouring->pot_type == $type->name ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="col-md-6 col-sm-12" id="number_section">
    <label class="form-label">شماره جنس</label>
    <select name="pot_number" id="pot_number" class="form-select">
        <option value="">ابتدا نوع جنس را انتخاب کنید</option>
    </select>
</div>

<div class="col-md-6 col-sm-12" id="sub_type_section">
    <label class="form-label">زیرنوع</label>
    <select name="pot_sub_type" id="pot_sub_type" class="form-select">
        <option value="">ابتدا شماره جنس را انتخاب کنید</option>
    </select>
</div>



                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">وزن فی جنس(کیلوگرام)</label>
                                    <input type="text" class="form-control" id="weight_per_pot" name="weight_per_pot" value="{{ $pouring->weight_per_pot }}">
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تعداد جنس</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity" value="{{ $pouring->quantity }}">
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت فی جنس (افغانی)</label>
                                    <input type="text" class="form-control" id="price_per_pot" name="price_per_pot" value="{{ $pouring->price_per_pot }}">
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">وزن مجموعی (کیلوگرام)</label>
                                    <input type="text" class="form-control" id="total_weight" name="total_weight" value="{{ $pouring->total_weight }}" readonly>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت مجموعی (افغانی)</label>
                                    <input type="text" class="form-control" id="total_price" name="total_price" value="{{ $pouring->total_price }}" readonly>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">توضیحات</label>
                                    <textarea class="form-control" name="note" rows="3">{{ $pouring->note }}</textarea>
                                </div>

                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="بروزرسانی ریخت">
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

    const potTypeSelect   = document.getElementById('pot_type');
    const potNumberSelect = document.getElementById('pot_number');
    const potSubtypeSelect= document.getElementById('pot_sub_type');

    const numberSection   = document.getElementById('number_section');
    const subTypeSection  = document.getElementById('sub_type_section');

    const weightInput     = document.getElementById('weight_per_pot');
    const quantityInput   = document.getElementById('quantity');
    const priceInput      = document.getElementById('price_per_pot');
    const totalWeightInput= document.getElementById('total_weight');
    const totalPriceInput = document.getElementById('total_price');

    // داده داینامیک از دیتابیس
    const potTypes = @json($potTypes);

    // داده اولیه برای حالت ویرایش
    const initialPotType   = "{{ $pouring->pot_type }}";
    const initialPotNumber = "{{ $pouring->pot_number }}";
    const initialPotSub    = "{{ $pouring->pot_sub_type }}";

    // تبدیل اعداد فارسی به انگلیسی
    function persianToEnglishNumber(str) {
        const persianNumbers = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        const englishNumbers = ['0','1','2','3','4','5','6','7','8','9'];
        let result = str;
        persianNumbers.forEach((p, i) => {
            result = result.replaceAll(p, englishNumbers[i]);
        });
        return result;
    }

    // محاسبه مجموع وزن و قیمت
    function calculateTotals() {
        const weight   = parseFloat(persianToEnglishNumber(weightInput.value).replace(/[^\d.]/g,'')) || 0;
        const quantity = parseFloat(persianToEnglishNumber(quantityInput.value).replace(/[^\d.]/g,'')) || 0;
        const price    = parseFloat(persianToEnglishNumber(priceInput.value).replace(/[^\d.]/g,'')) || 0;

        totalWeightInput.value = (weight * quantity).toFixed(2);
        totalPriceInput.value  = (price * quantity).toFixed(2);
    }

    // پر کردن شماره‌ها بر اساس نوع جنس
    function fillNumbers(typeName) {
        potNumberSelect.innerHTML = '<option value="">انتخاب شماره بخش</option>';
        potNumberSelect.disabled = true;

        potSubtypeSelect.innerHTML = '<option value="">انتخاب زیرنوع</option>';
        potSubtypeSelect.disabled = true;

        const type = potTypes.find(t => t.name == typeName);

        if(!type || !type.pot_numbers || type.pot_numbers.length === 0){
            // اگر شماره‌ای وجود ندارد → شماره و زیرنوع مخفی یا غیرفعال
            numberSection.style.display = 'none';
            subTypeSection.style.display = 'none';
            return;
        }

        // اگر شماره وجود دارد → فعال کردن و پر کردن گزینه‌ها
        type.pot_numbers.forEach(num => {
            const selected = (num.pot_number == initialPotNumber && typeName == initialPotType) ? 'selected' : '';
            potNumberSelect.innerHTML += `<option value="${num.pot_number}" ${selected}>${num.pot_number}</option>`;
        });

        numberSection.style.display = 'block';
        potNumberSelect.disabled = false;

        // اگر قبلاً شماره انتخاب شده، زیرنوع را پر کن
        if(initialPotNumber && typeName == initialPotType){
            fillSubtypes(typeName, initialPotNumber);
        }
    }

    // پر کردن زیرنوع بر اساس شماره
    function fillSubtypes(typeName, numberValue) {
        potSubtypeSelect.innerHTML = '<option value="">انتخاب زیرنوع</option>';

        const type = potTypes.find(t => t.name == typeName);
        if(!type) return;

        const number = type.pot_numbers.find(n => n.pot_number == numberValue);
        if(!number || !number.pot_subtypes || number.pot_subtypes.length === 0){
            subTypeSection.style.display = 'none';
            potSubtypeSelect.disabled = true;
            return;
        }

        number.pot_subtypes.forEach(sub => {
            const selected = (sub.name == initialPotSub) ? 'selected' : '';
            potSubtypeSelect.innerHTML += `<option value="${sub.name}" ${selected}>${sub.name}</option>`;
        });

        subTypeSection.style.display = 'block';
        potSubtypeSelect.disabled = false;
    }

    // Event listener تغییر نوع جنس
    potTypeSelect.addEventListener('change', function() {
        fillNumbers(this.value);
    });

    // Event listener تغییر شماره جنس
    potNumberSelect.addEventListener('change', function() {
        fillSubtypes(potTypeSelect.value, this.value);
    });

    // Event listener برای محاسبه مجموع
    [weightInput, quantityInput, priceInput].forEach(el => {
        el.addEventListener('input', calculateTotals);
        el.addEventListener('keyup', calculateTotals);
        el.addEventListener('change', calculateTotals);
    });

    // پر کردن اولیه برای حالت ویرایش
    if(initialPotType){
        fillNumbers(initialPotType);
    }

    calculateTotals();
});
</script>

@endsection
