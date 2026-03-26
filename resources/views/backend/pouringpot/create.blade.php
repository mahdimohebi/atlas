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
                        <li class="breadcrumb-item active" aria-current="page">ثبت رخت ها</li>
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

                        <form action="{{ route('pouring_pot.store') }}" method="POST">
                            @csrf
                            <div class="row gy-4">

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">کارمند ریخت‌گر</label>
                                    <select name="employee_id" class="form-select">
                                        <option value="">انتخاب کارمند ریخت‌گر</option>
                                        @foreach($employees as $employee)
                                            <option value="{{ $employee->id }}">{{ $employee->name }} {{ $employee->father_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ</label>
                                    <input type="date" class="form-control" name="date">
                                </div>


                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نوعیت جنس</label>
                                    <select name="pot_type" id="pot_type" class="form-select">
                                        <option value="">انتخاب نوع جنس</option>
                                        @foreach($potTypes as $type)
                                            <option value="{{ $type->name }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">شماره جنس</label>
                                    <select name="pot_number" id="pot_number" class="form-select" disabled>
                                        <option value="">ابتدا نوع جنس را انتخاب کنید</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">زیرنوع</label>
                                    <select name="pot_sub_type" id="pot_subtype" class="form-select" disabled>
                                        <option value="">ابتدا شماره جنس را انتخاب کنید</option>
                                    </select>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">وزن فی جنس(کیلوگرام)</label>
                                    <input type="text" class="form-control" id="weight_per_pot" name="weight_per_pot">
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تعداد جنس</label>
                                    <input type="text" class="form-control" id="quantity" name="quantity">
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت فی جنس (افغانی)</label>
                                    <input type="text" class="form-control" id="price_per_pot" name="price_per_pot">
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">وزن مجموعی (کیلوگرام)</label>
                                    <input type="text" class="form-control" id="total_weight" name="total_weight" readonly>
                                </div>

                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت مجموعی (افغانی)</label>
                                    <input type="text" class="form-control" id="total_price" name="total_price" readonly>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">توضیحات</label>
                                    <textarea class="form-control" name="note" rows="3"></textarea>
                                </div>

                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ثبت ریخت">
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

    const potTypes = @json($potTypes);

    const potTypeSelect   = document.getElementById('pot_type');
    const potNumberSelect = document.getElementById('pot_number');
    const potSubtypeSelect= document.getElementById('pot_subtype');

    const weightInput = document.getElementById('weight_per_pot');
    const quantityInput = document.getElementById('quantity');
    const priceInput = document.getElementById('price_per_pot');
    const totalWeightInput = document.getElementById('total_weight');
    const totalPriceInput = document.getElementById('total_price');

    // تبدیل اعداد فارسی به انگلیسی
    function persianToEnglish(str) {
        return str.replace(/[۰-۹]/g, d => '۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
    }

    // محاسبه وزن و قیمت مجموعی
    function calculateTotals() {
        const weight = parseFloat(persianToEnglish(weightInput.value)) || 0;
        const qty    = parseFloat(persianToEnglish(quantityInput.value)) || 0;
        const price  = parseFloat(persianToEnglish(priceInput.value)) || 0;

        totalWeightInput.value = (weight * qty).toFixed(2);
        totalPriceInput.value  = (price * qty).toFixed(2);
    }

    // اتصال رویداد input برای محاسبه لحظه‌ای
    weightInput.addEventListener('input', calculateTotals);
    quantityInput.addEventListener('input', calculateTotals);
    priceInput.addEventListener('input', calculateTotals);

    // مدیریت تغییر نوع جنس
    potTypeSelect.addEventListener('change', function () {
        potNumberSelect.innerHTML = '<option value="">انتخاب شماره جنس</option>';
        potSubtypeSelect.innerHTML = '<option value="">انتخاب زیرنوع</option>';
        potNumberSelect.disabled = true;
        potSubtypeSelect.disabled = true;

        const type = potTypes.find(t => t.name == this.value);
        if (!type || !type.pot_numbers.length) return;

        type.pot_numbers.forEach(num => {
            potNumberSelect.innerHTML +=
                `<option value="${num.pot_number}">${num.pot_number}</option>`;
        });

        potNumberSelect.disabled = false;
    });

    // مدیریت تغییر شماره جنس
    potNumberSelect.addEventListener('change', function () {
        potSubtypeSelect.innerHTML = '<option value="">انتخاب زیرنوع</option>';
        potSubtypeSelect.disabled = true;

        const type = potTypes.find(t => t.name == potTypeSelect.value);
        if (!type) return;

        const number = type.pot_numbers.find(n => n.pot_number == this.value);
        if (!number || !number.pot_subtypes.length) return;

        number.pot_subtypes.forEach(sub => {
            potSubtypeSelect.innerHTML +=
                `<option value="${sub.name}">${sub.name}</option>`;
        });

        potSubtypeSelect.disabled = false;
    });

});

</script>
@endsection




