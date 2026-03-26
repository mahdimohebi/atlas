@foreach($expenses as $expense)
<tr id="row-{{ $expense->id }}">
    <td>{{ $expense->expense_type }}</td>
    <td>{{ \Carbon\Carbon::parse($expense->date)->format('Y-m-d') }}</td>
    <td>{{ number_format($expense->price) }}</td>
    <td>{{ $expense->notes }}</td>
    <td>
        <a href="{{ route('factory_expenses.edit', $expense->id) }}" class="btn btn-sm btn-warning">ویرایش</a>
        <button class="btn btn-sm btn-danger delete" data-id="{{ $expense->id }}">حذف</button>
    </td>
</tr>
@endforeach

@if($expenses->count() == 0)
<tr>
    <td colspan="5" class="text-center">هیچ رکوردی یافت نشد.</td>
</tr>
@endif
