@foreach($contracts as $contract)
<tr id="row-{{ $contract->id }}">
    <td>{{ $contract->employee->name }}</td>
    <td>اجاره‌ای</td>
    <td>{{ \Carbon\Carbon::parse($contract->start_date)->format('Y-m-d') }}</td>
    <td>{{ \Carbon\Carbon::parse($contract->end_date)->format('Y-m-d') }}</td>
    <td>
        @if($contract->guarantee_type == 'naqdi' && $contract->guarantee)
            <strong>نقدی:</strong> {{ number_format($contract->guarantee->amount) }} AFN
        @elseif($contract->guarantee_type == 'shakhs' && $contract->guarantee)
            <strong>شخصی:</strong> {{ $contract->guarantee->name ?? '-' }} <br>
            📞 {{ $contract->guarantee->phone ?? '-' }} <br>
            🏠 {{ $contract->guarantee->address ?? '-' }}
        @else
            ندارد
        @endif
    </td>
    <td>
        <button class="btn btn-sm btn-info toggle-details" data-id="{{ $contract->id }}">نمایش</button>
        <a href="{{ route('contract.edit',$contract->id) }}" class="btn btn-sm btn-warning">ویرایش</a>
        <button class="btn btn-sm btn-danger delete" data-id="{{ $contract->id }}">حذف</button>
    </td>
</tr>

<tr id="details-{{ $contract->id }}" class="contract-details" style="display: none;">
    <td colspan="6">
        <div class="p-3 bg-light rounded d-flex flex-wrap gap-3">

            <!-- ستون ۱: اطلاعات قرارداد -->
            <div class="flex-fill" style="min-width: 250px;">
                <h5>اطلاعات قرارداد</h5>
                <strong>مدت قرارداد:</strong> {{ number_format($contract->duration) }} ماه <br>
                @if($contract->pricing_type == 'per_kg')
                    <strong>قیمت فی کیلو:</strong> {{ $contract->price_per_kg }} AFN <br>
                @else
                    <strong>قیمت فی دانه:</strong> {{ $contract->price_per_item }} AFN <br>
                @endif
                
                @if($contract->contract_photo)
                    <strong>عکس قرارداد:</strong><br>
                    <a href="{{ asset($contract->guarantee->photo) }}" download>
                        <img src="{{ asset($contract->contract_photo) }}" alt="Person Photo" width="150" class="mt-2 mb-2">
                    </a>
                @endif
            </div>

            <!-- ستون ۲: اطلاعات ضمانت شخص -->
            @if($contract->guarantee_type == 'shakhs' && $contract->guarantee)
                <div class="flex-fill" style="min-width: 250px;">
                    <h5>اطلاعات ضمانت</h5>
                    <strong>نام شخص:</strong> {{ $contract->guarantee->name ?? '-' }} <br>
                    <strong>نام پدر:</strong> {{ $contract->guarantee->father_name ?? '-' }} <br>
                    <strong>تذکره:</strong> {{ $contract->guarantee->tazkira_no ?? '-' }} <br>
                    <strong>تلفون:</strong> {{ $contract->guarantee->phone ?? '-' }} <br>
                    <strong>آدرس:</strong> {{ $contract->guarantee->address ?? '-' }} <br>
                    @if($contract->guarantee->photo)
                        <strong>عکس تعهدنامه شخص:</strong><br>
                            <a href="{{ asset($contract->guarantee->photo) }}" download>
                                <img src="{{ asset($contract->guarantee->photo) }}" alt="Person Photo" width="150" class="mt-2 mb-2">
                            </a>
                    @endif
                </div>
            @endif

        </div>
    </td>
</tr>

@endforeach

<!-- JavaScript برای نمایش/پنهان کردن جزئیات -->

