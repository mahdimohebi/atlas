
    @forelse($employees as $emp)
        <tr id="row-{{ $emp->id }}">
            <td>
                @if($emp->contract_type == 'ejaraei')
                <a href="{{route('employee.show',$emp->id)}}" class="text-success">{{ $emp->name }}</a>
                @else
                    {{ $emp->name }}
                @endif
            </td>
            <td>{{ $emp->father_name }}</td>
            <td>{{ $emp->tazkira_no }}</td>
            <td>{{ $emp->phone }}</td>
            <td>{{ $emp->address }}</td>
            <td>{{ $emp->job_position }}</td>
            <td>
                @if($emp->contract_type == 'ejaraei')
                    اجاره ایی
                @elseif($emp->contract_type == 'rozmozd')
                    روزمزد
                @else
                    -
                @endif
            </td>
            <td>
                @if($emp->is_active)
                    <span class="badge bg-success">فعال</span>
                @else
                    <span class="badge bg-danger">غیر فعال</span>
                @endif
            </td>

<!-- پرداخت شده -->
<td>
    @if($emp->total_paid >= $emp->total_amount)
        <span class="text-success">تمام پرداختی‌ها انجام شده</span>
    @else
        <span class="text-success">{{ number_format($emp->total_paid) }} AFN</span>
    @endif
</td>

<!-- باقی مانده -->
<td>
    @if($emp->remaining > 0)
        <span class="text-danger">{{ number_format($emp->remaining) }} AFN</span>
    @else
        <span class="text-success">هیچ باقی‌مانده‌ای نیست</span>
    @endif
</td>

<!-- جمع کل -->
<td>
    <span class="text-primary">{{ number_format($emp->total_amount) }} AFN</span>
</td>


            <td>
                <a href="{{ route('salary.index', ['employee_id' => $emp->id]) }}" class="btn btn-sm btn-info">
                    معاشات
                </a>
            </td>

            <td>
                <a href="{{ route('employee.edit', $emp->id) }}" class="text-info"><i class="ti ti-pencil"></i></a> |
                <a href="#" data-id="{{ $emp->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a>
                @if($emp->contract_type == 'ejaraei')
                 |
                <a href="{{ route('contracts.createForEmployee', $emp->id) }}" class="text-warning">
                    <i class="bx bx-file"></i>
                </a>
                @endif

            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">هیچ کارمنده ای ثبت نشده است</td>
        </tr>
    @endforelse

