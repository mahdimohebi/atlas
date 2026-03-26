
    @forelse($suppliers as $sup)
        <tr id="row-{{ $sup->id }}">
            <td>{{ $sup->name }}</td>
            <td>{{ $sup->f_name }}</td>
            <td>{{ $sup->address }}</td>
            <td>{{ $sup->phone }}</td>
            <td>
                <a href="{{ route('supplier.edit', $sup->id) }}" class="text-info"><i class="ti ti-pencil"></i></a> |
                <a href="#" data-id="{{ $sup->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a> |
                <a href="{{ route('transactions.createForSupplier', $sup->id) }}" class="text-warning"><i class="ri-shopping-cart-2-line"></i></a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">هیچ فروشنده‌ای ثبت نشده است</td>
        </tr>
    @endforelse

