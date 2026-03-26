
    @forelse($clients as $client)
        <tr id="row-{{ $client->id }}">
            <td>{{ $client->name }}</td>
            <td>{{ $client->f_name }}</td>
            <td>{{ $client->address }}</td>
            <td>{{ $client->phone }}</td>
            <td>
                <a href="{{ route('client.edit', $client->id) }}" class="text-info"><i class="ti ti-pencil"></i></a> |
                <a href="#" data-id="{{ $client->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a> |
                <a href="{{ route('transactions.createForClient', $client->id) }}" class="text-success"><i class="ri-shopping-cart-2-fill"></i></a>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="5" class="text-center">هیچ مشتری ای ثبت نشده است</td>
        </tr>
    @endforelse

