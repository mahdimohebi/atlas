@forelse($users as $user)
<tr id="row-{{ $user->id }}">
    <!-- تصویر -->
    <td>
        @if($user->image)
            <img src="{{ asset('storage/'.$user->image) }}" alt="تصویر کاربر" style="width:50px; height:50px; object-fit:cover; border-radius:50%;">
        @else
            -
        @endif
    </td>

    <td>{{ $user->name }}</td>
    <td>{{ $user->last_name }}</td>
    <td>{{ $user->email }}</td>

    <!-- بخش کاربر -->
    <td>{{ $user->section ?? '-' }}</td>

    <!-- نقش کاربر -->
    <td>
        @if($user->is_admin == 1)
            Admin
        @else
            Author
        @endif
    </td>

    <!-- عملیات -->
    <td>
        <a href="{{ route('user.edit', $user->id) }}" class="text-info"><i class="ti ti-pencil"></i></a> |
        <a href="#" data-id="{{ $user->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a>
    </td>
</tr>
@empty
<tr>
    <td colspan="7" class="text-center">هیچ کاربری ثبت نشده است</td>
</tr>
@endforelse
