@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">گزارش حاضری {{ $employee->name }} - {{ $employee->father_name }}</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card text-center bg-success text-white cursor-pointer filter-btn"
             data-status="present" data-month="{{ now()->month }}">
            <div class="card-body">
                <h5>روزهای حاضر (ماه جاری)</h5>
                <h2>{{ $presentCount }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card text-center bg-danger text-white cursor-pointer filter-btn"
             data-status="absent" data-month="{{ now()->month }}">
            <div class="card-body">
                <h5>روزهای غایب (ماه جاری)</h5>
                <h2>{{ $absentCount }}</h2>
            </div>
        </div>
    </div>
</div>
                        <!-- سرچ ساده -->
                        <input type="text" id="search" class="form-control mb-3" placeholder="جستجو در حاضری...">

                        <div class="table-responsive">
<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>تاریخ</th>
            <th>روز هفته</th>
            <th>وضعیت</th>
            <th>توضیح</th>
        </tr>
    </thead>
    <tbody id="attendance-table">
        @foreach($attendances as $att)
        <tr id="row-{{ $att->id }}" data-status="{{ $att->status }}" data-month="{{ \Carbon\Carbon::parse($att->date)->month }}">
            <td>{{ \Morilog\Jalali\Jalalian::fromDateTime($att->date)->format('Y/m/d') }}</td>
            <td>{{ $att->day_name }}</td>
            <td>
                @if($att->status == 'present')
                    <span class="badge bg-success">حاضر</span>
                @else
                    <span class="badge bg-danger">غایب</span>
                @endif
            </td>
            <td>{{ $att->description ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<script>


function fa2en(str) {
    if (!str) return '';
    const fa = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    const en = ['0','1','2','3','4','5','6','7','8','9'];
    for(let i=0; i<10; i++){
        str = str.replaceAll(fa[i], en[i]);
    }
    return str;
}

$('#search').on('keyup', function(){
    let value = fa2en($(this).val().toLowerCase());

    $('#attendance-table tr').filter(function(){
        let text = fa2en($(this).text().toLowerCase());
        $(this).toggle(text.indexOf(value) > -1);
    });
});

document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        let status = this.dataset.status;
        let month  = this.dataset.month;

        document.querySelectorAll('#attendance-table tr').forEach(row => {
            row.style.display = (row.dataset.status === status && row.dataset.month === month) ? '' : 'none';
        });

        
        $('#search').trigger('keyup');
    });
});

</script>

@endsection


