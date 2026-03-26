@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">انواع جنس</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <!-- دکمه ثبت مشتری جدید -->
                        <a href="{{ route('pot_types.create') }}" class="btn btn-primary mb-3">ثبت جنس</a>
                        <input type="text" id="search" class="form-control mb-3" placeholder="جستجو در انواع جنس...">
                        
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>نوعیت جنس</th>
                                        <th>شماره جنس </th>
                                        <th>زیر نوع</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
  <tbody id="pot-data">
    @forelse($potTypes as $pot)
        @if($pot->potNumbers->isEmpty())
            <tr id="row-{{ $pot->id }}">
                <td>{{ $pot->name }}</td>
                <td>—</td>
                <td>—</td>
                <td>
                    <a href="#" data-id="{{ $pot->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a>
                </td>
            </tr>
        @else
            @foreach($pot->potNumbers as $number)
            <tr id="row-{{ $number->id }}">
                <td>{{ $pot->name }}</td>
                <td>{{ $number->pot_number ?? '—' }}</td>
                <td>
                    @if($number->potSubtypes->isEmpty())
                        —
                    @else
                        @foreach($number->potSubtypes as $subtype)
                            {{ $subtype->name }}<br>
                        @endforeach
                    @endif
                </td>
                <td>
                    <a href="#" data-id="{{ $number->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a>
                </td>
            </tr>
            @endforeach
        @endif
    @empty
        <tr>
            <td colspan="4" class="text-center">هیچ جنس ثبت نشده است</td>
        </tr>
    @endforelse
</tbody>

<!-- Pagination -->
<div id="pagination-links">
    {{ $potTypes->links() }}
</div>






                                </tbody>
                            </table>
                        </div><br>
                      

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
$(document).ready(function() {

    // سرچ ساده
    $('#search').on('keyup', function(){
        let value = $(this).val().toLowerCase();
        $('#pot-data tr').filter(function(){
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
});
</script>


<script>
$(document).on('click', '.delete', function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    Swal.fire({
        title: "آیا مطمئن هستید؟",
        text: "این عملیات قابل بازگشت نیست!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "بله، حذف شود!",
        cancelButtonText: "لغو"
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/pot_types/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(response) {
                    $('#row-' + id).remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'حذف شد!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    Swal.fire('خطا!', xhr.responseJSON?.message || 'مشکلی رخ داده است.', 'error');
                }
            });
        }
    });
});

</script>

@endsection
