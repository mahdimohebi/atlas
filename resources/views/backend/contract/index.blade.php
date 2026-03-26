@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                    <li class="breadcrumb-item active" aria-current="page">قرارداد ها</li>
                </ol>
            </nav>
        </div>
        <!-- Page Header Close -->

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <!-- دکمه بازگشت -->
                        <a href="{{ route('employee.index',['contract_type'=>'ejaraei']) }}" class="btn btn-primary mb-3">بازگشت</a>
                        
                        <!-- جدول -->
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped text-nowrap w-100">
                                <thead>
                                    <tr>
                                        <th>نام کارمند</th>
                                        <th>نوع قرار داد</th>
                                        <th>تاریخ شروع</th>
                                        <th>تاریخ ختم</th>
                                        <th>ضمانت</th>
                                        <th>عملیات</th>
                                    </tr>
                                </thead>
                                <tbody id="contract-data">
                                    @include('backend.contract.table')
                                </tbody>
                            </table>
                        </div><br>

                        <!-- pagination -->
                        <div id="pagination-links">
                            @include('backend.contract.pagination')
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<!-- Contract Modal -->
<div class="modal fade" id="contractModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">جزئیات قرارداد</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="modal-body-content">
        <!-- اطلاعات قرارداد اینجا از طریق AJAX قرار می‌گیرد -->
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
                url: '/contract/' + id,
                type: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function() {
                    $('#row-' + id).remove();
                    Swal.fire({
                        icon: 'success',
                        title: 'حذف شد!',
                        text: 'قرارداد با موفقیت حذف شد.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                },
                error: function() {
                    Swal.fire('خطا!', 'مشکلی رخ داده است.', 'error');
                }
            });
        }
    });





    $('.show-contract').on('click', function() {
        var contractId = $(this).data('id');

        $.ajax({
            url: '/contract/' + contractId, // route contract.show
            type: 'GET',
            success: function(data) {
                // اطلاعات داخل مودال
                $('#modal-body-content').html(data);
                $('#contractModal').modal('show'); // نمایش مودال
            },
            error: function() {
                alert('خطا در دریافت اطلاعات قرارداد!');
            }
        });
    });


});
</script>
<script>
    document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', () => {
            const id = button.getAttribute('data-id');
            const detailsRow = document.getElementById('details-' + id);
            detailsRow.style.display = detailsRow.style.display === 'none' ? 'table-row' : 'none';
        });
    });
</script>

@endsection
