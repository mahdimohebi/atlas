@forelse($designs as $design)
    <tr id="row-{{ $design->id }}">
        <td>{{ $design->employee->name }} - {{ $design->employee->father_name }}</td>
        <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($design->date))->format('Y/m/d') }}</td>
        <td>{{ $design->date }}</td>
        <td>{{ $design->pot_type }}</td>
        <td>{{ $design->pot_number ?? '-' }}</td>
        <td>{{ $design->design_type }}</td>
        <td>{{ $design->quantity }}</td>
        <td>{{ number_format($design->price_per_pot) }} AFN</td>
        <td>{{ number_format($design->total_price) }} AFN</td>
        <td>{{ $design->note ?? '-' }}</td>
        <td>
            <a href="{{ route('design_pot.edit', $design->id) }}" class="text-info"><i class="ti ti-pencil"></i></a> |
            <a href="#" data-id="{{ $design->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center">هیچ دیزاینی ثبت نشده است</td>
    </tr>
@endforelse
