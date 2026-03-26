@forelse($transactions as $transaction)
<tr id="row-{{ $transaction->id }}">
    <td>{{ $transaction->id }}</td>
    
    <!-- نوع تراکنش -->
    <td>
        @if($transaction->type == 'purchase')
            خرید
        @elseif($transaction->type == 'sale')
            فروش
        @else
            -
        @endif
    </td>

    <!-- مشتری / فروشنده -->
    <td>
        @if($transaction->type == 'purchase')
            {{ $transaction->supplier->name ?? '-' }}
        @else
            {{ $transaction->client->name ?? '-' }}
        @endif
    </td>

    <!-- دسته‌بندی -->
    <td>
        @if($transaction->category == 'soft')
            نرم
        @elseif($transaction->category == 'hard')
            سخت
        @else
            -
        @endif
    </td>
    <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($transaction->transaction_date))->format('Y/m/d') }}</td>
    <td>{{ number_format($transaction->quantity) }} Kg</td>
    <td>{{ number_format($transaction->price_per_unit) }} {{ $transaction->currency }}</td>
    <td>{{ number_format($transaction->exchange_rate,2) }}</td>
    <!-- جمع کل -->
    <td class="text-success">
        @if($transaction->currency === 'USD')
            {{ number_format($transaction->price_per_unit * $transaction->quantity) }} USD
            ({{ number_format($transaction->total_price) }} افغانی)
        @else
            {{ number_format($transaction->total_price) }} AFN
        @endif
    </td>

    <!-- پرداخت شده -->
    @php
        $totalPaidAFN = $transaction->payments->sum(function($payment) {
            if($payment->currency === 'USD') {
                return $payment->amount * $payment->exchange_rate;
            }
            return $payment->amount;
        });
    @endphp

    <!-- پرداخت شده -->
    <td class="text-warning">
        {{ number_format($totalPaidAFN) }} AFN
    </td>


    <!-- باقی‌مانده -->
    @php
        $remaining = $transaction->total_price - $transaction->payments()->sum('amount');
    @endphp
    <td>
        @if($remaining <= 0)
            <span class="text-success">پرداخت تکمیل شده</span>
        @else
            <span class="text-danger">{{ number_format($remaining) }} AFN</span>
        @endif
    </td>

  
    
    <td>{{ $transaction->description }}</td>

    <!-- عملیات -->
    <td class="text-center">
        <a href="{{ route('transaction.edit', $transaction->id) }}" class="text-info" title="ویرایش"><i class="ti ti-pencil"></i></a> |
        <a href="#" data-id="{{ $transaction->id }}" class="delete-transaction text-danger mx-2" title="حذف"><i class="ti ti-trash"></i></a> |
        <a href="{{ route('transaction.show', $transaction->id) }}" class="text-success mx-2" title="پرداخت"><i class="ri-bank-card-fill"></i></a>
    </td>
</tr>
@empty
<tr>
    <td colspan="13" class="text-center">هیچ تراکنشی ثبت نشده است</td>
</tr>
@endforelse
