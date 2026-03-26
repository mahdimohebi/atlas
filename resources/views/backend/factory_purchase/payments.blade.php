@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('factory-purchases.index') }}">خریدهای فابریکه</a></li>
                    <li class="breadcrumb-item active" aria-current="page">پرداخت‌ها</li>
                </ol>
            </nav>
        </div>

        <!-- اطلاعات خرید -->
        <div class="card mb-3">
            <div class="card-body">
                <h5>خرید شماره: {{ $purchase->id }}</h5>
                <p>فروشنده: {{ $purchase->name }} | نام پدر: {{ $purchase->f_name }}</p>
                <p>دسته‌بندی: {{ $purchase->category=='soft'?'نرم':'سخت' }} | تاریخ: {{ \Morilog\Jalali\Jalalian::forge($purchase->purchase_date)->format('Y/m/d') }}</p>
                <p>مقدار: {{ $purchase->quantity }} | ضایعات: {{ $purchase->waste ?? 0 }} | وزن خالص: {{ $purchase->net_weight }}</p>
                <p>قیمت فی واحد: {{ number_format($purchase->price_per_unit) }} | جمع کل: {{ number_format($purchase->total_price) }}</p>
                <p>پرداخت شده: <span id="total-paid">0</span> | مانده: <span id="remaining">{{ number_format($purchase->total_price) }}</span></p>
            </div>
        </div>

        <!-- فرم ثبت پرداخت جدید -->
        <div class="card mb-3">
            <div class="card-body">
                <h5>ثبت پرداخت جدید</h5>
                <form id="add-payment-form">
                    @csrf
                    <input type="hidden" name="factory_purchase_id" value="{{ $purchase->id }}">
                    <div class="row gy-2">
                        <div class="col-md-3">
                            <input type="date" name="payment_date" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <input type="text"  name="amount" class="form-control" placeholder="مبلغ" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="note" class="form-control" placeholder="یادداشت (اختیاری)">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100" type="submit">ثبت پرداخت</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- جدول پرداخت‌ها -->
        <div class="card">
            <div class="card-body">
                <h5>لیست پرداخت‌ها</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="payments-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>تاریخ پرداخت</th>
                                <th>مبلغ</th>
                                <th>یادداشت</th>
                                <th>عملیات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- داده‌ها با Ajax پر می‌شوند -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
function persianToEnglishNumber(str) {
    if(!str) return 0;
    const persianNumbers = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    for(let i=0;i<10;i++){
        str = str.replace(new RegExp(persianNumbers[i],'g'), i);
    }
    return str;
}

$(document).ready(function() {
    const purchaseId = {{ $purchase->id }};
    let payments = [];

    function renderPayments() {
        const tbody = $('#payments-table tbody');
        tbody.empty();
        let totalPaid = 0;

        payments.forEach((p, index)=>{
            let amount = parseFloat(p.amount);
            totalPaid += amount;
            tbody.append(`
                <tr data-id="${p.id}">
                    <td>${index+1}</td>
                    <td>${p.payment_date}</td>
                    <td>${amount.toFixed(2)}</td>
                    <td>${p.note ?? '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning edit-payment">ویرایش</button>
                        <button class="btn btn-sm btn-danger delete-payment">حذف</button>
                    </td>
                </tr>
            `);
        });

        $('#total-paid').text(totalPaid.toFixed());
        const remaining = {{ $purchase->total_price }} - totalPaid;
        $('#remaining').text(remaining.toFixed());
        return remaining;
    }

    function loadPayments() {
        $.get('/factory-purchase/' + purchaseId + '/payments/list', function(data){
            payments = data;
            renderPayments();
        });
    }

    loadPayments();

    // ثبت پرداخت جدید
    $('#add-payment-form').submit(function(e){
        e.preventDefault();
        const remaining = renderPayments();
        let amountStr = $('input[name="amount"]').val();
        let amount = parseFloat(persianToEnglishNumber(amountStr));
        if(amount > remaining){
            Swal.fire('خطا', 'مقدار پرداخت نمی‌تواند از مانده بیشتر باشد.', 'error');
            return;
        }
        $('input[name="amount"]').val(amount); // مقدار فارسی به انگلیسی تبدیل شد
        const formData = $(this).serialize();
        $.post('{{ route("factory-purchase.payments.store") }}', formData, function(){
            $('#add-payment-form')[0].reset();
            loadPayments();
            Swal.fire('موفق', 'پرداخت ثبت شد', 'success');
        });
    });

    // ویرایش پرداخت
    $(document).on('click', '.edit-payment', function(){
        const row = $(this).closest('tr');
        const id = row.data('id');
        const payment = payments.find(p => p.id == id);
        const totalPaidExcludingCurrent = payments.reduce((sum, p)=> p.id != id ? sum + parseFloat(p.amount) : sum, 0);
        const remainingForEdit = {{ $purchase->total_price }} - totalPaidExcludingCurrent;

        Swal.fire({
            title: 'ویرایش پرداخت',
            html: `
                <input type="date" id="swal-payment-date" class="swal2-input" value="${payment.payment_date}">
                <input type="text" id="swal-payment-amount" class="swal2-input" value="${payment.amount}">
                <input type="text" id="swal-payment-note" class="swal2-input" value="${payment.note ?? ''}">
            `,
            preConfirm: ()=>{
                let newAmountStr = $('#swal-payment-amount').val();
                let newAmount = parseFloat(persianToEnglishNumber(newAmountStr));
                if(newAmount > remainingForEdit){
                    Swal.showValidationMessage(`مقدار پرداخت نمی‌تواند از مانده بیشتر باشد.`);
                }
                return {
                    payment_date: $('#swal-payment-date').val(),
                    amount: newAmount,
                    note: $('#swal-payment-note').val()
                };
            },
            showCancelButton: true,
            confirmButtonText: 'بروزرسانی',
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: '/factory-purchase/payments/' + id,
                    type: 'PUT',
                    data: {
                        _token: '{{ csrf_token() }}',
                        payment_date: result.value.payment_date,
                        amount: result.value.amount,
                        note: result.value.note
                    },
                    success: function(){
                        loadPayments();
                        Swal.fire('موفق', 'پرداخت بروزرسانی شد', 'success');
                    }
                });
            }
        });
    });

    // حذف پرداخت
    $(document).on('click', '.delete-payment', function(){
        const row = $(this).closest('tr');
        const id = row.data('id');
        Swal.fire({
            title: 'آیا مطمئن هستید؟',
            text: 'این عملیات قابل بازگشت نیست!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'بله، حذف شود',
            cancelButtonText: 'لغو'
        }).then((result)=>{
            if(result.isConfirmed){
                $.ajax({
                    url: '/factory-purchase/payments/' + id,
                    type: 'DELETE',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    success: function(){
                        loadPayments();
                        Swal.fire('حذف شد', 'پرداخت با موفقیت حذف شد', 'success');
                    }
                });
            }
        });
    });
});

</script>
@endsection
