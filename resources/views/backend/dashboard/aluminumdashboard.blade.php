@extends('backend.layouts.master')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <!-- ================= HEADER ================= -->
        <div class="d-flex align-items-center justify-content-between my-3 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-18 mb-0">داشبورد خرید و فروش المونیم</p>
            </div>

            <div>
                <button class="btn btn-primary btn-wave" data-bs-toggle="dropdown">
                    فیلتر: {{ ucfirst($filter) }}
                    <i class="ri-arrow-down-s-fill ms-1"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('al_dashboard.index',['filter'=>'daily']) }}">امروز</a></li>
                    <li><a class="dropdown-item" href="{{ route('al_dashboard.index',['filter'=>'weekly']) }}">۷ روز گذشته</a></li>
                    <li><a class="dropdown-item" href="{{ route('al_dashboard.index',['filter'=>'monthly']) }}">ماهانه</a></li>
                    <li><a class="dropdown-item" href="{{ route('al_dashboard.index',['filter'=>'yearly']) }}">سالانه</a></li>
                </ul>
            </div>
        </div>

        <!-- ================= SUMMARY ================= -->
        <div class="row mb-3">
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card text-center">
                    <div class="card-body">
                        <div class="fs-13">فروشندگان</div>
                        <div class="fs-22 fw-semibold text-primary">{{ $totalSuppliers }}</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card custom-card text-center">
                    <div class="card-body">
                        <div class="fs-13">مشتریان</div>
                        <div class="fs-22 fw-semibold text-info">{{ $totalClients }}</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card custom-card text-center">
                    <div class="card-body">
                        <div class="fs-13">درآمد دریافتی</div>
                        <div class="fs-22 fw-semibold text-success">
                            {{ number_format($totalSalePaid) }} افغانی
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card custom-card text-center">
                    <div class="card-body">
                        <div class="fs-13">سود خالص</div>
                        <div class="fs-22 fw-semibold text-danger">
                            {{ number_format($net_profit) }} افغانی
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= PURCHASE & SALE ================= -->
        <div class="row">
            <div class="col-lg-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6>پرداخت‌های خرید</h6>
                        <canvas id="purchaseChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6>پرداخت‌های فروش</h6>
                        <canvas id="saleChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= ALUMINUM ================= -->
        <div class="row mt-3">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6>گزارش المونیم (سخت / نرم)</h6>
                        <canvas id="alChart" height="130"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= EXPENSE ================= -->
<div class="row mt-3">
    <div class="col-lg-6">
        <div class="card custom-card">
            <div class="card-body">
                <h6>مصارف خرید</h6>
                <canvas id="purchaseExpenseChart" height="120"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card custom-card">
            <div class="card-body">
                <h6>مصارف فروش</h6>
                <canvas id="saleExpenseChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>

<script>
function createBarChart(id, labels, data, labelText, bgColor) {
    return new Chart(document.getElementById(id), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: labelText,
                data: data,
                backgroundColor: bgColor
            }]
        },
        options: {
            responsive: true,
            plugins: {
                datalabels: {
                    anchor: 'end',
                    align: 'end',
                    formatter: function(value) {
                        return Math.round(value).toLocaleString();// فرمت عدد انگلیسی
                    },
                    font: { weight: 'bold' }
                },
                legend: { display: true }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'مبلغ / تعداد' },
                    ticks: {
                        callback: function(value) {
                            return Math.round(value).toLocaleString();
                        }
                    }
                },
                x: {
                    title: { display: true, text: 'تاریخ' }
                    // بدون تغییر callback → تاریخ به انگلیسی نمایش داده می‌شود
                }
            }
        },
        plugins: [ChartDataLabels]
    });
}

/* ================= PURCHASE ================= */
createBarChart(
    'purchaseChart',
    {!! json_encode($purchasePayments->pluck('date')) !!},
    {!! json_encode($purchasePayments->pluck('total')) !!},
    'پرداخت خرید (AFN)',
    'rgba(54,162,235,0.6)'
);

/* ================= SALE ================= */
createBarChart(
    'saleChart',
    {!! json_encode($salePayments->pluck('date')) !!},
    {!! json_encode($salePayments->pluck('total')) !!},
    'پرداخت فروش (AFN)',
    'rgba(75,192,192,0.6)'
);

/* ================= ALUMINUM ================= */
new Chart(document.getElementById('alChart'), {
    type: 'bar',
    data: {
        labels: {!! json_encode($chart['dates']) !!},
        datasets: [
            { label:'سخت خرید', data:{!! json_encode($chart['hard']['purchase']) !!}, backgroundColor:'rgba(255,99,132,0.3)' },
            { label:'سخت فروش', data:{!! json_encode($chart['hard']['sale']) !!}, backgroundColor:'rgba(255,99,132,0.7)' },
            { label:'نرم خرید', data:{!! json_encode($chart['soft']['purchase']) !!}, backgroundColor:'rgba(75,192,192,0.3)' },
            { label:'نرم فروش', data:{!! json_encode($chart['soft']['sale']) !!}, backgroundColor:'rgba(75,192,192,0.7)' }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            datalabels: {
                anchor: 'end',
                align: 'end',
                formatter: function(value) {
                    return value.toLocaleString();
                },
                font: { weight: 'bold' }
            },
            legend: { display: true }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: { display: true, text: 'تعداد' },
                ticks: { callback: function(value) { return value.toLocaleString(); } }
            },
            x: { title: { display: true, text: 'تاریخ' } }
        }
    },
    plugins: [ChartDataLabels]
});

/* ================= EXPENSE ================= */
createBarChart(
    'purchaseExpenseChart',
    {!! json_encode($purchaseExpenses->pluck('date')) !!},
    {!! json_encode($purchaseExpenses->pluck('total')) !!},
    'مصارف خرید (AFN)',
    'rgba(255,99,132,0.6)'
);

createBarChart(
    'saleExpenseChart',
    {!! json_encode($saleExpenses->pluck('date')) !!},
    {!! json_encode($saleExpenses->pluck('total')) !!},
    'مصارف فروش (AFN)',
    'rgba(54,162,235,0.6)'
);
</script>


@endsection
