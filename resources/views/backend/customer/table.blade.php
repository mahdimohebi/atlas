@forelse($customers as $customer)
<tr id="row-{{ $customer->id }}">
    <td>
        <a href="{{ route('customer.show', $customer->id) }}" class="text-success">{{ $customer->first_name }}</a>
    </td>
    <td>{{ $customer->last_name }}</td>
    <td>{{ $customer->address }}</td>
    <td>{{ $customer->phone }}</td>

    <!-- تعداد فروش‌ها -->
    <td>
        {{ $customer->sales->count() }}
    </td>

    <!-- مجموع پرداخت‌ها -->
    <td>
        <span class="text-success">{{ number_format($customer->payments->sum('amount')) }} AFN</span>
    </td>

    <!-- باقی‌مانده -->
    <td>
        @php
            $totalSales = $customer->sales->sum(function($sale) {
                return $sale->items->sum('total_price');
            });
            $totalPaid = $customer->payments->sum('amount');
            $remaining = $totalSales - $totalPaid;
        @endphp
        @if($remaining > 0)
            <span class="text-danger">{{ number_format($remaining) }} AFN</span>
        @else
            <span class="text-success">هیچ باقی‌مانده‌ای نیست</span>
        @endif
    </td>

    <!-- جمع کل -->
    <td>
        <span class="text-primary">{{ number_format($totalSales) }} AFN</span>
    </td>

    <!-- عملیات -->
    <td>
        <a href="{{ route('customer.edit', $customer->id) }}" class="text-info"><i class="ti ti-pencil"></i></a> |
        <a href="#" data-id="{{ $customer->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a> |
        <a href="{{ route('sale.create', $customer->id) }}" class="text-warning">
            <i class="bx bx-cart"></i>
        </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="9" class="text-center">هیچ مشتری‌ای ثبت نشده است</td>
</tr>
@endforelse
