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
                        <li class="breadcrumb-item active" aria-current="page">ویرایش مصرف آلومینیوم</li>
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

                        <form action="{{ route('aluminum_expenses.update', $expense->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row gy-4">

                                <!-- نوع مصرف -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">نوع مصرف</label>
                                    <select name="expense_type" class="form-control" required>
                                        <option value="">انتخاب نوع مصرف</option>
                                        <option value="کرایه موتر" {{ $expense->expense_type == 'کرایه موتر' ? 'selected' : '' }}>کرایه موتر</option>
                                        <option value="کارگر" {{ $expense->expense_type == 'کارگر' ? 'selected' : '' }}>کارگر</option>
                                        <option value="نان" {{ $expense->expense_type == 'نان' ? 'selected' : '' }}>نان</option>
                                        <option value="جوکی داری" {{ $expense->expense_type == 'جوکی داری' ? 'selected' : '' }}>جوکی داری</option>
                                        <option value="مالیه" {{ $expense->expense_type == 'مالیه' ? 'selected' : '' }}>مالیه</option>
                                        <option value="برق" {{ $expense->expense_type == 'برق' ? 'selected' : '' }}>برق</option>
                                        <option value="کرایه سرای" {{ $expense->expense_type == 'کرایه سرای' ? 'selected' : '' }}>کرایه سرای</option>
                                    </select>
                                    @error('expense_type')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- تاریخ -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">تاریخ</label>
                                    <input type="date" name="date" class="form-control" value="{{ old('date', $expense->date) }}" required>
                                    @error('date')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- قیمت -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">قیمت</label>
                                    <input type="text" name="price" id="price" class="form-control" value="{{ old('price', $expense->price) }}" required>
                                    @error('price')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- یادداشت -->
                                <div class="col-md-6 col-sm-12">
                                    <label class="form-label">یادداشت</label>
                                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $expense->notes) }}</textarea>
                                    @error('notes')
                                        <p class="text-danger">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- دکمه ثبت -->
                                <div class="col-12 text-center">
                                    <input type="submit" class="btn btn-success" value="بروزرسانی مصرف">
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
