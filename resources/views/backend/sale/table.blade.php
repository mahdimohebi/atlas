@forelse($sales as $sale)
<tr id="row-{{ $sale->id }}">
    <!-- شماره فاکتور -->
    <td>{{ $sale->invoice_number }}</td>

    <!-- مشتری -->
    <td>{{ $sale->customer->first_name ?? '' }} {{ $sale->customer->last_name ?? '' }}</td>

    <!-- تاریخ خرید -->
    <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($sale->sale_date))->format('Y/m/d') }}</td>

    <!-- تعداد اقلام -->
    <td>{{ $sale->items->count() }}</td>
    <!-- جمع کل -->
    <td class="text-green">{{ number_format($sale->items->sum('total_price')) }} AFN</td>

    <!-- پرداخت شده -->
    <td class="text-primary">{{ number_format($sale->payments->sum('amount')) }} AFN</td>

    <!-- باقیمانده -->
    <td >
        @if(number_format($sale->items->sum('total_price') - $sale->payments->sum('amount')) == 0)
        <span class="text-warning">
            تمام پرداختی ها تکمیل شده است.
        </span>
        @else
        <span class="text-warning">
            {{ number_format($sale->items->sum('total_price') - $sale->payments->sum('amount')) }} AFN
        </span>
        @endif
        
    </td>
        <!-- عملیات -->
     <td></td>
    <td>
        <a href="{{ route('customer_payment.index', ['sale_id' => $sale->id]) }}" class="btn btn-sm btn-info">
            پرداخت
        </a>
    </td>
    <td>
        <a href="{{ route('sale.invoice',  $sale->id) }}" class="btn btn-sm btn-info">
            فاکتور خرید ها
        </a>
    </td>

    <td>
            <a href="{{ route('sale.edit', $sale->id) }}" class="btn btn-sm btn-info">
                <i class="ti ti-pencil"></i>
            </a>
            <a href="#" data-id="{{ $sale->id }}" class="btn btn-sm btn-danger delete">
                <i class="ti ti-trash"></i>
            </a>
    </td>
</tr>
@empty
<tr>
    <td colspan="8" class="text-center text-muted">هیچ فروشی ثبت نشده است</td>
</tr>
@endforelse
