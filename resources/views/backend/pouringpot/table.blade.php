
@forelse($pots as $pot)
    <tr id="row-{{ $pot->id }}">
        <td>{{ $pot->employee->name }} - {{ $pot->employee->father_name }}</td>
        <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($pot->date))->format('Y/m/d') }}</td>
        <td>{{ $pot->pot_type }}</td>
        <td>{{ $pot->pot_number }}</td>
        <td>{{ $pot->pot_sub_type }}</td>
        <td>{{ $pot->weight_per_pot }} Kgr</td>
        <td>{{ $pot->quantity }}</td>
        <td>{{ number_format($pot->price_per_pot) }} AFN</td>
        <td>{{ number_format($pot->total_weight) }} Kgr</td>
        <td>{{ number_format($pot->total_price) }} AFN</td>
        <td>{{ $pot->note }}</td>
        <td>
            <a href="{{ route('pouring_pot.edit', $pot->id) }}" class="text-info"><i class="ti ti-pencil"></i></a> |
            <a href="#" data-id="{{ $pot->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="13" class="text-center">هیچ داده‌ای برای ریختگر ثبت نشده است</td>
    </tr>
@endforelse

