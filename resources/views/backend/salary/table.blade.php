@forelse($salaries as $salary)
    <tr id="row-{{ $salary->id }}">
        <td>{{ $salary->employee->name }}</td>
        <td>
            @if($salary->status && $salary->status != '0')
                <span class="badge bg-success">پرداخت شده</span>
            @else
                <span class="badge bg-danger">پرداخت نشده</span>
            @endif
        </td>

        <td>{{ number_format($salary->amount) }} AFN</td>
        <td>{{ $salary->date }}</td>
        <td>{{ $salary->notes ?? '-' }}</td>
        <td>
            <a href="{{ route('salary.edit', $salary->id) }}" class="text-info">
                <i class="ti ti-pencil"></i>
            </a> |
            <a href="#" data-id="{{ $salary->id }}" class="delete text-danger">
                <i class="ti ti-trash"></i>
            </a>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="6" class="text-center">هیچ معاشی ثبت نشده است</td>
    </tr>
@endforelse
