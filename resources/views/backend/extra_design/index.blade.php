@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">انواع دیزاین</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">
                        <form action="{{ route('designs.store') }}" method="POST">
                            @csrf
                            <div class="row gy-4">

                                <!-- نوعیت جنس -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label"> دیزاین</label>
                                    <input type="text" name="name" id="name" class="form-control" >
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <br>
                                    <input type="submit" class="btn btn-success" value="ثبت دیزاین">
                                </div>
                            </div>
                        </form>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>نوعیت دیزاین</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="design-data">
                                @foreach($designs as $design)
                                <tr id="row-{{ $design->id }}">
                                    <td>{{ $design->name }}</td>
                                    <td>
                                        <a href="#" data-id="{{ $design->id }}" class="delete text-danger"><i class="ti ti-trash"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>

                                <!-- Pagination -->
                                <div id="pagination-links">
                                    {{ $designs->links() }}
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
                url: '/designs/' + id,
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
