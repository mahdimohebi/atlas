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
                        <li class="breadcrumb-item active" aria-current="page">ثبت قرارداد</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="{{ route('contract.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row gy-4">

                                <!-- کارمند (فقط یک کارمند مشخص) -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">کارمند</label>
                                    <input type="text" class="form-control" value="{{ $employee->name }} ({{ $employee->tazkira_no }})" disabled>
                                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                                    <input type="hidden" id="employee_job" value="{{ $employee->job_position }}">
                                </div>

                                <!-- عکس قرارداد -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="contract_photo" class="form-label">عکس قرارداد</label>
                                    <input type="file" class="form-control" name="contract_photo" id="contract_photo">
                                    @error('contract_photo')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- نوع ضمانت -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="guarantee_type" class="form-label">نوع ضمانت</label>
                                    <select name="guarantee_type" id="guarantee_type" class="form-select" >
                                        <option value="">انتخاب کنید</option>
                                        <option value="naqdi">نقدی</option>
                                        <option value="shakhs">شخص</option>
                                    </select>
                                    @error('guarantee_type')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- مقدار نقدی -->
                                <div class="col-md-6 col-sm-12" id="cash_amount_div" style="display:none;">
                                    <label for="amount" class="form-label">مقدار نقدی</label>
                                    <input type="text" class="form-control" name="amount" id="amount">
                                    @error('amount')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                     
                                
                                <!-- اطلاعات شخص -->
                           <!-- اطلاعات شخص -->
<div id="person_info_div" class="row gy-3" style="display:none;">
    <div class="col-md-6 col-sm-12">
        <label for="person_name" class="form-label">نام شخص</label>
        <input type="text" class="form-control" name="person_name" id="person_name">
    </div>

    <div class="col-md-6 col-sm-12">
        <label for="person_father_name" class="form-label">نام پدر</label>
        <input type="text" class="form-control" name="person_father_name" id="person_father_name">
    </div>

    <div class="col-md-6 col-sm-12">
        <label for="person_tazkira_no" class="form-label">تذکره نمبر</label>
        <input type="text" class="form-control" name="person_tazkira_no" id="person_tazkira_no">
    </div>

    <div class="col-md-6 col-sm-12">
        <label for="person_address" class="form-label">آدرس</label>
        <input type="text" class="form-control" name="person_address" id="person_address">
    </div>

    <div class="col-md-6 col-sm-12">
        <label for="person_phone" class="form-label">تلفون</label>
        <input type="text" class="form-control" name="person_phone" id="person_phone">
    </div>

    <div class="col-md-6 col-sm-12">
        <label for="person_photo" class="form-label">عکس تعهدنامه</label>
        <input type="file" class="form-control" name="person_photo" id="person_photo">
    </div>
</div>


                                <!-- جزئیات قرارداد -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="duration" class="form-label">مدت قرارداد (ماه)</label>
                                    <input type="text" class="form-control" name="duration" id="duration" >
                                    @error('duration')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تاریخ شروع قرارداد -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="start_date" class="form-label">تاریخ اخذ قرارداد</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date" >
                                    @error('start_date')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تاریخ پایان قرارداد -->
                                <div class="col-md-6 col-sm-12">
                                    <label for="end_date" class="form-label">تاریخ ختم قرارداد</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date" >
                                    @error('end_date')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

<div class="col-md-6 col-sm-12">
    <label for="pricing_type" class="form-label">
        قرارداد بر حسب کیلو یا فی دانه
    </label>
    <select name="pricing_type" id="pricing_type" class="form-control">
        <option value="">انتخاب کنید</option>
        <option value="per_item">قیمت فی دانه</option>
        <option value="per_kg">قیمت فی کیلو</option>
    </select>
</div>

<div class="col-md-6 col-sm-12">
    <label for="price_per_item" class="form-label">قیمت فی دانه</label>
    <input type="text" class="form-control" name="price_per_item" id="price_per_item" disabled>
</div>

<div class="col-md-6 col-sm-12">
    <label for="price_per_kg" class="form-label">قیمت فی کیلو</label>
    <input type="text" class="form-control" name="price_per_kg" id="price_per_kg" disabled>
</div>




                                <!-- دکمه ثبت -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ثبت قرارداد">
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const guaranteeType   = document.getElementById('guarantee_type');
    const cashDiv         = document.getElementById('cash_amount_div');
    const personDiv       = document.getElementById('person_info_div');

    function toggleGuaranteeFields() {
        if (guaranteeType.value === 'naqdi') {
            cashDiv.style.display   = 'block';
            personDiv.style.display = 'none';
        }
        else if (guaranteeType.value === 'shakhs') {
            cashDiv.style.display   = 'none';
            personDiv.style.display = 'flex';
            personDiv.style.flexWrap = 'wrap';
        }
        else {
            cashDiv.style.display   = 'none';
            personDiv.style.display = 'none';
        }
    }

    // تغییر نوع ضمانت
    guaranteeType.addEventListener('change', toggleGuaranteeFields);

    // اگر صفحه با مقدار قبلی لود شد (validation error)
    toggleGuaranteeFields();

});
</script>
<script>
    const pricingType = document.getElementById('pricing_type');
    const pricePerItem = document.getElementById('price_per_item');
    const pricePerKg = document.getElementById('price_per_kg');

    pricingType.addEventListener('change', function () {
        if (this.value === 'per_item') {
            pricePerItem.disabled = false;
            pricePerKg.disabled = true;
            pricePerKg.value = '';
        } else if (this.value === 'per_kg') {
            pricePerKg.disabled = false;
            pricePerItem.disabled = true;
            pricePerItem.value = '';
        } else {
            pricePerItem.disabled = true;
            pricePerKg.disabled = true;
            pricePerItem.value = '';
            pricePerKg.value = '';
        }
    });
</script>
@endsection
