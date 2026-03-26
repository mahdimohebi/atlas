@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">فاکتور فروش</li>
                </ol>
            </nav>
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

                        <form action="{{ route('sale.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">

                            <div class="row gy-4">
                                <div class="col-md-6">
                                    <label class="form-label">مشتری</label>
                                    <input type="text" class="form-control" 
                                        value="{{ $customer->first_name . ' ' . $customer->last_name }}" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">تاریخ فاکتور</label>
                                    <input type="date" name="sale_date" class="form-control" value="{{ date('Y-m-d') }}">
                                </div>

                                <!-- آیتم‌ها -->
                               <div class="col-12">
    <h5>اقلام خرید</h5>

    <div id="items-container">

        <div class="item-row row gy-2 align-items-end">

            <!-- نوع دیگ -->
            <div class="col-md-3">
                <label>نوع دیگ</label>
                <select name="pot_type[]" class="form-select pot-type">
                    <option value="">انتخاب نوع جنس</option>
                    @foreach($potTypes as $type)
                        <option value="{{ $type->name }}"
                            data-numbers='@json($type->potNumbers->pluck("pot_number"))'>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- شماره دیگ -->
            <div class="col-md-2 number-section" style="display:none;">
                <label>شماره جنس</label>
                <select name="pot_number[]" class="form-select pot-number">
                    <option value="">انتخاب شماره جنس</option>
                </select>
            </div>

            <!--  دیزاین دیگ -->
            <div class="col-md-2 design-section" >
                <label>دیزاین جنس</label>
                <select name="pot_design[]" class="form-select ">
                    <option value="">انتخاب دیزاین جنس </option>
                    @foreach($design as $d)
                     <option value="{{ $d->name }}">{{ $d->name }} </option>
                    @endforeach
                </select>
            </div>

            <!-- تعداد -->
            <div class="col-md-2">
                <label>تعداد</label>
                <input type="text" name="quantity[]" class="form-control quantity">
            </div>

            <!-- قیمت -->
            <div class="col-md-2">
                <label>فی دیگ (AFN)</label>
                <input type="text" name="unit_price[]" class="form-control unit-price">
            </div>

            <!-- توضیحات -->
            <div class="col-md-2">
                <label>توضیحات</label>
                <input type="text" name="remarksforsale[]" class="form-control">
            </div>

            <!-- حذف -->
            <div class="col-md-1 text-center">
                <button type="button" class="btn btn-danger remove-item">حذف</button>
            </div>

        </div>
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
                                    <label>توضیحات کلی فاکتور</label>
                                    <textarea name="remarks" class="form-control" rows="3"></textarea>
                                </div>

                                <div class="col-12 text-center mt-3">
                                    <input type="submit" class="btn btn-success" value="ثبت فاکتور">
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

    const container = document.getElementById('items-container');
    const addBtn = document.getElementById('add-item');
    const totalAmountInput = document.getElementById('total-amount');

    function persianToEnglish(str) {
        if (!str) return '0';
        const p = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        const e = ['0','1','2','3','4','5','6','7','8','9'];
        let r = str.toString();
        p.forEach((n,i)=> r = r.replaceAll(n, e[i]));
        return r;
    }

    function calculateTotal() {
        let total = 0;
        container.querySelectorAll('.item-row').forEach(row => {
            const qty = parseFloat(persianToEnglish(row.querySelector('.quantity')?.value)) || 0;
            const price = parseFloat(persianToEnglish(row.querySelector('.unit-price')?.value)) || 0;
            total += qty * price;
        });
        totalAmountInput.value = total;
    }

    function fillPotNumbers(row) {
        const typeSelect = row.querySelector('.pot-type');
        const numberSection = row.querySelector('.number-section');
        const numberSelect = row.querySelector('.pot-number');

        let numbers = [];
        try {
            numbers = JSON.parse(typeSelect.options[typeSelect.selectedIndex].dataset.numbers || '[]');
        } catch {}

        numberSelect.innerHTML = '<option value="">انتخاب شماره دیگ</option>';

        if (numbers.length === 0) {
            numberSection.style.display = 'none';
            numberSelect.value = '';
            return;
        }

        numbers.forEach(num => {
            numberSelect.innerHTML += `<option value="${num}">${num}</option>`;
        });

        numberSection.style.display = 'block';
    }

    // تغییر نوع دیگ
    container.addEventListener('change', function (e) {
        if (e.target.classList.contains('pot-type')) {
            fillPotNumbers(e.target.closest('.item-row'));
        }
    });

    // محاسبه
    container.addEventListener('input', function (e) {
        if (e.target.classList.contains('quantity') || e.target.classList.contains('unit-price')) {
            e.target.value = persianToEnglish(e.target.value);
            calculateTotal();
        }
    });

    // حذف ردیف
    container.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.item-row').remove();
            calculateTotal();
        }
    });

    // اضافه کردن ردیف
    addBtn.addEventListener('click', function () {
        const firstRow = container.querySelector('.item-row');
        const newRow = firstRow.cloneNode(true);

        newRow.querySelectorAll('input').forEach(i => i.value = '');
        newRow.querySelector('.pot-number').innerHTML = '<option value="">انتخاب شماره دیگ</option>';
        newRow.querySelector('.number-section').style.display = 'none';
        newRow.querySelector('.pot-type').selectedIndex = 0;

        container.appendChild(newRow);
    });

    calculateTotal();
});
</script>

@endsection
