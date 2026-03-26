@extends('backend.layouts.master')

@section('content')
<div class="main-content app-content">
    <div class="container-fluid">

        <!-- ================= HEADER ================= -->
        <div class="d-flex align-items-center justify-content-between my-3 page-header-breadcrumb flex-wrap gap-2">
            <div>
                <p class="fw-medium fs-18 mb-0">داشبورد کارخانه</p>
            </div>

            <div>
                <button class="btn btn-primary btn-wave" data-bs-toggle="dropdown">
                    فیلتر: {{ ucfirst($filter) }}
                    <i class="ri-arrow-down-s-fill ms-1"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('fa_dashboard.index',['filter'=>'daily']) }}">امروز</a></li>
                    <li><a class="dropdown-item" href="{{ route('fa_dashboard.index',['filter'=>'weekly']) }}">۷ روز گذشته</a></li>
                    <li><a class="dropdown-item" href="{{ route('fa_dashboard.index',['filter'=>'monthly']) }}">ماهانه</a></li>
                    <li><a class="dropdown-item" href="{{ route('fa_dashboard.index',['filter'=>'yearly']) }}">سالانه</a></li>
                </ul>
            </div>
        </div>

        <!-- ================= SUMMARY CARDS ================= -->
        <div class="row mb-3">
            <div class="col-xl-3 col-md-6">
                <div class="card custom-card text-center">
                    <div class="card-body">
                        <div class="fs-13">تعداد کارمندان</div>
                        <div class="fs-22 fw-semibold text-primary">{{ $employee_count }}</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card custom-card text-center">
                    <div class="card-body">
                        <div class="fs-13">تعداد مشتریان</div>
                        <div class="fs-22 fw-semibold text-info">{{ $customer_count }}</div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card custom-card text-center">
                    <div class="card-body">
                        <div class="fs-13">درآمد کل</div>
                        <div class="fs-22 fw-semibold text-success">
                            {{ number_format($customer_payment_sum->sum('total_amount')) }} افغانی
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card custom-card text-center">
                    <div class="card-body">
                        <div class="fs-13">سود خالص</div>
                        <div class="fs-22 fw-semibold text-danger">{{ number_format($net_profit) }} افغانی</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= CHARTS ================= -->
        <div class="row">

            <!-- Design Chart -->
            <div class="col-lg-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6>تولید دیزاین‌ها</h6>
                        <canvas id="designChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Pouring Chart -->
            <div class="col-lg-6">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6>تولید ریخت‌ها</h6>
                        <canvas id="pouringChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Sales Chart -->
            <div class="col-lg-6 mt-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6>فروشات (پرداخت مشتری)</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <p class="fs-12 fw-semibold text-success mb-0">جمع کل: {{ number_format($total_customer_payment) }} افغانی</p>
                            <p class="fs-12 fw-semibold text-danger mb-0">باقی‌مانده: {{ number_format($total_customer_payment_remain) }} افغانی</p>
                        </div>
                        <canvas id="salesChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Factory Expense Chart -->
            <div class="col-lg-6 mt-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6>مصارف کارخانه</h6>
                        <canvas id="expenseChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Factory Purchase Chart -->
            <div class="col-lg-6 mt-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6>خرید کارخانه</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <p class="fs-12 fw-semibold text-success mb-0">جمع کل: {{ number_format($total_factory_purchase) }} افغانی</p>
                            <p class="fs-12 fw-semibold text-danger mb-0">باقی‌مانده: {{ number_format($total_factoryPurchase_remain) }} افغانی</p>
                        </div>
                        <canvas id="purchaseChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Daily Salary Chart -->
            <div class="col-lg-6 mt-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6>معاشات روزمزد</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <p class="fs-12 fw-semibold text-success mb-0">جمع کل: {{ number_format($total_employee_rozmozd) }} افغانی</p>
                            <p class="fs-12 fw-semibold text-danger mb-0">باقی‌مانده: {{ number_format($total_employee_remain) }} افغانی</p>
                        </div>
                        <canvas id="dailySalaryChart" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- Contract Salary Chart -->
            <div class="col-lg-6 mt-3">
                <div class="card custom-card">
                    <div class="card-body">
                        <h6>معاشات اجاره‌ای</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <p class="fs-12 fw-semibold text-success mb-0">جمع کل: {{ number_format($ejaraei_summary['total']) }} افغانی</p>
                            <p class="fs-12 fw-semibold text-danger mb-0">باقی‌مانده: {{ number_format($ejaraei_summary['remain']) }} افغانی</p>
                        </div>
                        <canvas id="contractSalaryChart" height="120"></canvas>
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
function createBarChart(ctxId, labels, data, labelText, color) {
    const ctx = document.getElementById(ctxId).getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: { labels: labels, datasets: [{ label: labelText, data: data, backgroundColor: color, borderWidth: 1 }] },
        options: {
            responsive: true,
            plugins: { datalabels: { anchor: 'end', align: 'bottom', offset: 4, formatter: v => Math.round(v).toLocaleString(), font: { weight: 'bold', size: 12 } } },
            scales: { y: { beginAtZero: true, grace: '10%' } }
        },
        plugins: [ChartDataLabels]
    });
}

/* ---------- DESIGN ---------- */
createBarChart('designChart', @json($designpots->pluck('date')), @json($designpots->pluck('total')), 'تعداد دیزاین', 'rgba(54,162,235,0.6)');

/* ---------- POURING ---------- */
createBarChart('pouringChart', @json($pouringpot->pluck('date')), @json($pouringpot->pluck('total')), 'وزن کل ریخت‌ها', 'rgba(255,159,64,0.6)');

/* ---------- SALES ---------- */
createBarChart('salesChart', @json($customer_payment_sum->pluck('date')), @json($customer_payment_sum->pluck('total_amount')), 'پرداخت مشتریان', 'rgba(75,192,192,0.6)');

/* ---------- FACTORY EXPENSE ---------- */
createBarChart('expenseChart', @json($expenses->pluck('date')), @json($expenses->pluck('total')), 'مصارف کارخانه', 'rgba(255,99,132,0.6)');

/* ---------- FACTORY PURCHASE ---------- */
createBarChart('purchaseChart', @json($factoryPurchase->pluck('date')), @json($factoryPurchase->pluck('total')), 'پرداخت کارخانه', 'rgba(255,159,64,0.6)');

/* ---------- DAILY SALARY ---------- */
createBarChart('dailySalaryChart', @json($employee_rozmozd->pluck('date')), @json($employee_rozmozd->pluck('total_salary')), 'حقوق روزمزد', 'rgba(54,162,235,0.6)');

createBarChart('contractSalaryChart', @json($employee_ejaraei->pluck('date')), @json($employee_ejaraei->pluck('total_salary')), 'حقوق اجاره‌ای', 'rgba(75,192,192,0.6)');

</script>
@endsection
