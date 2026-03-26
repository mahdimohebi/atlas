
    @forelse($payments as $payment)
        <tr id="row-{{ $payment->id }}">
            <td>{{ $payment->id }}</td>
            <td>{{ $payment->transaction->id }}</td>
            <td>
                @if($payment->transaction->type == 'purchase')
                    {{ $payment->transaction->supplier->name ?? '' }}
                @else
                    {{ $payment->transaction->client->name ?? '' }}
                @endif
            </td>
            <td>
                @if($payment->currency === 'USD')
                {{ $payment->amount * $payment->exchange_rate }}
                @else
                {{ $payment->amount }}
                @endif
            </td>
            <td>
                @if ($payment->currency === 'USD')
                    {{ $payment->currency }} ({{ number_format($payment->exchange_rate) }})
                @else
                    {{ $payment->currency }}
                @endif

            </td>
            <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($payment->payment_date))->format('Y/m/d') }}</td>

        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">هیچ پرداختی ای ثبت نشده است</td>
        </tr>
    @endforelse

