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
                        <li class="breadcrumb-item active" aria-current="page">ثبت مصرف جدید کارخانه</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!-- Page Header Close -->

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

                        <form action="{{ route('factory_expenses.store') }}" method="POST">
                            @csrf
                            <div class="row gy-4">

                                <!-- نوع مصرف -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نوع مصرف</label>
                                    <select name="expense_type" class="form-control" required>
                                        <option value="">انتخاب نوع مصرف</option>
                                        <option value="کرایه موتر">کرایه موتر</option>
                                        <option value="کارگر">کارگر</option>
                                        <option value="برق">برق</option>
                                        <option value="مالیه">مالیه</option>
                                        <option value="نان">نان</option>
                                        <option value="کرایه سرای">کرایه سرای</option>
                                        <option value="جوکی داری">جوکی داری</option>
                                    </select>
                                    @error('expense_type')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تاریخ -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ</label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date') }}" required>
                                    @error('date')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- قیمت -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت</label>
                                    <input type="text" name="price" id="price" class="form-control" value="{{ old('price') }}" required>
                                    @error('price')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- یادداشت -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">یادداشت</label>
                                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                                    @error('notes')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- دکمه ثبت -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="ثبت مصرف">
                                </div>

                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection

@section('script')
<script>
document.getElementById('price').addEventListener('input', function() {
    let persianNumbers = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
    let englishNumbers = ['0','1','2','3','4','5','6','7','8','9'];
    let value = this.value;
    for(let i=0; i<persianNumbers.length; i++){
        value = value.replace(new RegExp(persianNumbers[i], 'g'), englishNumbers[i]);
    }
    this.value = value;
});
</script>
@endsection
