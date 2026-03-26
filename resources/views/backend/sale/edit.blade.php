@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item">صفحات</li>
                    <li class="breadcrumb-item active">ویرایش فاکتور فروش</li>
                </ol>
            </nav>
        </div>

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

                <form action="{{ route('sale.update',$sale->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="customer_id" value="{{ $sale->customer_id }}">

                    <div class="row gy-4">

                        <div class="col-md-6">
                            <label>مشتری</label>
                            <input type="text" class="form-control"
                                   value="{{ $sale->customer->first_name.' '.$sale->customer->last_name }}" readonly>
                        </div>

                        <div class="col-md-6">
                            <label>تاریخ فاکتور</label>
                            <input type="date" name="sale_date" class="form-control"
                                   value="{{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d') }}">
                        </div>

                        <!-- آیتم‌ها -->
                        <div class="col-12">
                            <h5>اقلام خرید</h5>

                            <div id="items-container">

                                @foreach($sale->items as $item)
                                <div class="item-row row gy-2 align-items-end mt-2">

                                    <!-- نوع جنس -->
<!-- نوع جنس -->
<div class="col-md-3">
    <label>نوع جنس</label>
    <select name="pot_type[]" class="form-select pot-type">
        <option value="">انتخاب نوع جنس</option>
        @foreach($potTypes as $type)
            <option value="{{ $type->name }}" 
                data-numbers='@json($type->potNumbers->pluck("pot_number"))'
                {{ $item->pot_type == $type->name ? 'selected' : '' }}>
                {{ $type->name }}
            </option>
        @endforeach
    </select>
</div>

<!-- شماره -->
<div class="col-md-2 number-section" data-selected="{{ $item->pot_number }}">
    <label>شماره جنس</label>
    <select name="pot_number[]" class="form-select pot-number">
        <option value="">انتخاب شماره</option>
    </select>
</div>



                                    <!-- دیزاین -->
                                    <div class="col-md-2">
                                        <label>دیزاین</label>
                                        <select name="pot_design[]" class="form-select">
                                            <option value="">انتخاب دیزاین</option>
                                            @foreach($design as $d)
                                                <option value="{{ $d->name }}"
                                                    {{ $item->pot_design == $d->name ? 'selected' : '' }}>
                                                    {{ $d->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- تعداد -->
                                    <div class="col-md-2">
                                        <label>تعداد</label>
                                        <input type="text" name="quantity[]" class="form-control quantity"
                                               value="{{ $item->quantity }}">
                                    </div>

                                    <!-- قیمت -->
                                    <div class="col-md-2">
                                        <label>فی (AFN)</label>
                                        <input type="text" name="unit_price[]" class="form-control unit-price"
                                               value="{{ $item->unit_price }}">
                                    </div>

                                    <!-- توضیح -->
                                    <div class="col-md-2">
                                        <label>توضیحات</label>
                                        <input type="text" name="remarksforsale[]" class="form-control"
                                               value="{{ $item->remarks }}">
                                    </div>

                                    <div class="col-md-1 text-center">
                                        <button type="button" class="btn btn-danger remove-item">حذف</button>
                                    </div>

                                </div>
                                @endforeach

                            </div>

                            <button type="button" id="add-item" class="btn btn-success mt-3">
                                اضافه کردن قلم جدید
                            </button>
                        </div>

                        <div class="col-md-6">
                            <label>جمع کل (AFN)</label>
                            <input type="text" id="total-amount" class="form-control" readonly>
                        </div>

                        <div class="col-12">
                            <label>توضیحات کلی</label>
                            <textarea name="remarks" class="form-control" rows="3">{{ $sale->remarks }}</textarea>
                        </div>

                        <div class="col-12 text-center">
                            <button class="btn btn-success">ویرایش فاکتور</button>
                        </div>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

@endsection
@section('script')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item');
    const totalInput = document.getElementById('total-amount');

    function toEN(v){
        if(!v) return '0';
        return v.replace(/[۰-۹]/g,d=>'۰۱۲۳۴۵۶۷۸۹'.indexOf(d));
    }

    function calc(){
        let t=0;
        container.querySelectorAll('.item-row').forEach(r=>{
            let q=parseFloat(toEN(r.querySelector('.quantity')?.value))||0;
            let p=parseFloat(toEN(r.querySelector('.unit-price')?.value))||0;
            t+=q*p;
        });
        totalInput.value=t;
    }

   function fillNumbers(row){
    const type = row.querySelector('.pot-type');
    const sec = row.querySelector('.number-section');
    const sel = row.querySelector('.pot-number');

    let nums = [];
    try {
        nums = JSON.parse(type.options[type.selectedIndex]?.dataset.numbers || '[]');
    } catch {}

    sel.innerHTML = '<option value="">انتخاب شماره</option>';

    if(nums.length === 0){
        sec.style.display = 'none';
        return;
    }

    // مقدار قبلی انتخاب شده
    const selectedValue = sec.dataset.selected ? String(sec.dataset.selected) : null;

    nums.forEach(n => {
        sel.innerHTML += `<option value="${n}" ${selectedValue !== null && String(n) === selectedValue ? 'selected' : ''}>${n}</option>`;
    });

    sec.style.display = 'block';
}


    // init rows
container.querySelectorAll('.item-row').forEach(r=>{
    fillNumbers(r, r.dataset.selected);
});


    container.addEventListener('change',e=>{
        if(e.target.classList.contains('pot-type')){
            fillNumbers(e.target.closest('.item-row'));
        }
    });

    container.addEventListener('input',e=>{
        if(e.target.classList.contains('quantity')||e.target.classList.contains('unit-price')){
            e.target.value=toEN(e.target.value);
            calc();
        }
    });

    container.addEventListener('click',e=>{
        if(e.target.classList.contains('remove-item')){
            e.target.closest('.item-row').remove();
            calc();
        }
    });

    addBtn.addEventListener('click',()=>{
        const row=container.querySelector('.item-row').cloneNode(true);
        row.querySelectorAll('input,select').forEach(el=>el.value='');
        row.querySelector('.number-section').style.display='none';
        container.appendChild(row);
    });

    calc();
});
</script>
@endsection
