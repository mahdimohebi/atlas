@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active">ویرایش قرارداد</li>
                </ol>
            </nav>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
                        <form action="{{ route('contract.update',$contract->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row gy-4">

                                <!-- کارمند -->
                                <div class="col-md-6">
                                    <label class="form-label">کارمند</label>
                                    <input type="text" class="form-control"
                                           value="{{ $contract->employee->name }} ({{ $contract->employee->tazkira_no }})"
                                           disabled>
                                </div>

                                <!-- عکس قرارداد -->
                                <div class="col-md-6">
                                    <label class="form-label">عکس قرارداد</label>
                                    <input type="file" class="form-control" name="contract_photo">
                                    @if($contract->contract_photo)
                                        <img src="{{ asset($contract->contract_photo) }}" width="100" class="mt-2">
                                    @endif
                                </div>

                                <!-- نوع ضمانت -->
                                <div class="col-md-6">
                                    <label class="form-label">نوع ضمانت</label>
                                    <select name="guarantee_type" id="guarantee_type" class="form-select">
                                        <option value="">انتخاب کنید</option>
                                        <option value="naqdi"  {{ $contract->guarantee_type=='naqdi'?'selected':'' }}>نقدی</option>
                                        <option value="shakhs" {{ $contract->guarantee_type=='shakhs'?'selected':'' }}>شخص</option>
                                    </select>
                                </div>

                                <!-- ضمانت نقدی -->
                                <div class="col-md-6" id="cash_amount_div" style="display:none;">
                                    <label class="form-label">مقدار نقدی</label>
                                    <input type="text" class="form-control" name="amount"
                                           value="{{ old('amount',$contract->guarantee->amount ?? '') }}">
                                </div>

                                <!-- ضمانت شخص -->
                                <div id="person_info_div" class="row gy-3" style="display:none;">
                                    <div class="col-md-6">
                                        <label class="form-label">نام شخص</label>
                                        <input type="text" class="form-control" name="person_name"
                                               value="{{ old('person_name',$contract->guarantee->name ?? '') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">نام پدر</label>
                                        <input type="text" class="form-control" name="person_father_name"
                                               value="{{ old('person_father_name',$contract->guarantee->father_name ?? '') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">تذکره نمبر</label>
                                        <input type="text" class="form-control" name="person_tazkira_no"
                                               value="{{ old('person_tazkira_no',$contract->guarantee->tazkira_no ?? '') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">آدرس</label>
                                        <input type="text" class="form-control" name="person_address"
                                               value="{{ old('person_address',$contract->guarantee->address ?? '') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">تلفون</label>
                                        <input type="text" class="form-control" name="person_phone"
                                               value="{{ old('person_phone',$contract->guarantee->phone ?? '') }}">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">عکس تعهدنامه</label>
                                        <input type="file" class="form-control" name="person_photo">
                                        @if($contract->guarantee && $contract->guarantee->photo)
                                            <img src="{{ asset($contract->guarantee->photo) }}" width="100" class="mt-2">
                                        @endif
                                    </div>
                                </div>

                                <!-- سایر اطلاعات -->
                                <div class="col-md-6">
                                    <label class="form-label">مدت قرارداد (ماه)</label>
                                    <input type="text" class="form-control" name="duration"
                                           value="{{ old('duration',$contract->duration) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">تاریخ شروع</label>
                                    <input type="date" class="form-control" name="start_date"
                                           value="{{ old('start_date',$contract->start_date) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">تاریخ ختم</label>
                                    <input type="date" class="form-control" name="end_date"
                                           value="{{ old('end_date',$contract->end_date) }}">
                                </div>

            <div class="col-md-6">
                <label class="form-label">نوع قیمت</label>
                <select name="pricing_type" id="pricing_type" class="form-select">
                    <option value="">انتخاب کنید</option>
                    <option value="per_item" {{ old('pricing_type',$contract->pricing_type)=='per_item'?'selected':'' }}>
                        قیمت فی دانه
                    </option>
                    <option value="per_kg" {{ old('pricing_type',$contract->pricing_type)=='per_kg'?'selected':'' }}>
                        قیمت فی کیلو
                    </option>
                </select>
            </div>
            <div class="col-md-6" id="price_per_item_div">
                <label class="form-label">قیمت فی دانه</label>
                <input type="text" class="form-control" name="price_per_item"
                    value="{{ old('price_per_item',$contract->price_per_item) }}">
            </div>

            <div class="col-md-6" id="price_per_kg_div">
                <label class="form-label">قیمت فی کیلو</label>
                <input type="text" class="form-control" name="price_per_kg"
                    value="{{ old('price_per_kg',$contract->price_per_kg) }}">
            </div>

                                <div class="col-12 text-center">
                                    <button class="btn btn-success px-4">ویرایش قرارداد</button>
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const guarantee = document.getElementById('guarantee_type');
    const cashDiv   = document.getElementById('cash_amount_div');
    const personDiv = document.getElementById('person_info_div');

    function toggleGuarantee() {
        cashDiv.style.display   = 'none';
        personDiv.style.display = 'none';

        if (guarantee.value === 'naqdi')  cashDiv.style.display = 'block';
        if (guarantee.value === 'shakhs') personDiv.style.display = 'flex';
    }

    guarantee.addEventListener('change', toggleGuarantee);
    toggleGuarantee(); // حالت اولیه در edit
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ضمانت
    const guarantee = document.getElementById('guarantee_type');
    const cashDiv   = document.getElementById('cash_amount_div');
    const personDiv = document.getElementById('person_info_div');

    function toggleGuarantee() {
        cashDiv.style.display   = 'none';
        personDiv.style.display = 'none';

        if (guarantee.value === 'naqdi')  cashDiv.style.display = 'block';
        if (guarantee.value === 'shakhs') personDiv.style.display = 'flex';
    }

    guarantee.addEventListener('change', toggleGuarantee);
    toggleGuarantee();


    // 🔥 قیمت
    const pricingType = document.getElementById('pricing_type');
    const itemDiv = document.getElementById('price_per_item_div');
    const kgDiv   = document.getElementById('price_per_kg_div');

    function togglePricing() {
        itemDiv.style.display = 'none';
        kgDiv.style.display   = 'none';

        if (pricingType.value === 'per_item') {
            itemDiv.style.display = 'block';
        } else if (pricingType.value === 'per_kg') {
            kgDiv.style.display = 'block';
        }
    }

    pricingType.addEventListener('change', togglePricing);
    togglePricing(); // مهم برای حالت edit
});
</script>
@endsection
