@extends('backend.layouts.master')
@section('content')

<div class="main-content app-content">
    <div class="container-fluid">

        <!-- Page Header -->
        <div class="my-4 page-header-breadcrumb d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div>
                <nav>
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="javascript:void(0);">صفحات</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ثبت جنس جدید</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card custom-card">
                    <div class="card-body">

                        <!-- نمایش خطاهای کلی -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('pot_types.store') }}" method="POST">
                            @csrf
                            <div class="row gy-4">

                                <!-- نوعیت جنس -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نوعیت جنس</label>
                                    <input type="text" name="name" id="pot_type_name" class="form-control" >
                                    @error('name')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- شماره جنس -->
                                <div class="col-md-6 col-sm-12" id="pot_number_container" style="display:none;">
                                    <label class="form-label">شماره جنس</label>
                                    <select name="pot_numbers[0][pot_number]" id="pot_number_select" class="form-control">
                                        <option value="">-- شماره جنس را انتخاب کنید --</option>
                                        <option value="1">شماره 1</option>
                                        <option value="2">شماره 2</option>
                                        <option value="3">شماره 3</option>
                                    </select>
                                    @error('pot_numbers.0.pot_number')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- زیرنوع‌ها -->
                                <div class="col-md-6 col-sm-12" id="subtype_container" style="display:none;">
                                    <label class="form-label">زیرنوع‌ها</label>

                                    <select name="pot_numbers[0][subtypes][]" class="form-control mb-2">
                                        <option value="">-- زیرنوع را انتخاب کنید --</option>
                                        <option value="گوتک">گوتک</option>
                                        <option value="سرپوش">سرپوش</option>
                                        <option value="دیگ">دیگ</option>
                                    </select>

                                    <select name="pot_numbers[0][subtypes][]" class="form-control mb-2">
                                        <option value="">-- زیرنوع را انتخاب کنید --</option>
                                        <option value="گوتک">گوتک</option>
                                        <option value="سرپوش">سرپوش</option>
                                        <option value="دیگ">دیگ</option>
                                    </select>

                                    <select name="pot_numbers[0][subtypes][]" class="form-control mb-2">
                                        <option value="">-- زیرنوع را انتخاب کنید --</option>
                                        <option value="گوتک">گوتک</option>
                                        <option value="سرپوش">سرپوش</option>
                                        <option value="دیگ">دیگ</option>
                                    </select>

                                    @error('pot_numbers.0.subtypes.0')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ثبت جنس">
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){

    // نمایش شماره جنس وقتی نوعیت جنس پر شد
    $('#pot_type_name').on('input', function(){
        if($(this).val().length > 0){
            $('#pot_number_container').show();
        } else {
            $('#pot_number_container').hide();
            $('#subtype_container').hide();
        }
    });

    // نمایش زیرنوع‌ها وقتی شماره جنس انتخاب شد
    $('#pot_number_select').on('change', function(){
        if($(this).val() !== ''){
            $('#subtype_container').show();
        } else {
            $('#subtype_container').hide();
        }
    });

});
</script>

@endsection
