<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\PotNumber;
use App\Models\PotType;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PotTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $potTypes = PotType::with('potNumbers.potSubtypes')->simplepaginate(20);
        return view('backend.pot.index',compact('potTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.pot.create');
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'pot_numbers.*.pot_number' => 'nullable|string|max:100',
        'pot_numbers.*.subtypes.*' => 'nullable|string|max:255',
    ]);

    DB::transaction(function () use ($request) {

        // بررسی وجود PotType با همین نام
        $potType = PotType::firstOrCreate(
            ['name' => $request->name]
        );

        // اگر شماره‌ها وجود دارد
        if ($request->has('pot_numbers')) {
            foreach ($request->pot_numbers as $potNumberData) {

                // اگر شماره جنس خالی بود رد شود
                if (!empty($potNumberData['pot_number'])) {

                    // ایجاد PotNumber فقط اگر شماره هنوز برای این PotType موجود نیست
                    $potNumber = $potType->potNumbers()->firstOrCreate(
                        ['pot_number' => $potNumberData['pot_number']]
                    );

                    // زیرنوع‌ها
                    if (!empty($potNumberData['subtypes'])) {
                        foreach ($potNumberData['subtypes'] as $subtypeName) {
                            if (!empty($subtypeName)) {

                                // ایجاد زیرنوع فقط اگر هنوز برای این شماره وجود ندارد
                                $potNumber->potSubtypes()->firstOrCreate(
                                    ['name' => $subtypeName]
                                );

                            }
                        }
                    }
                }
            }
        }
    });

    return redirect()->route('pot_types.index')
                     ->with('success', 'ثبت جنس با موفقیت انجام شد.');
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $potType = PotType::with(['potNumbers.potSubtypes'])->findOrFail($id);

        return view('backend.pot.edit', compact('potType'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
    

        

    }




    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    // ابتدا بررسی می‌کنیم آیا ID مربوط به شماره است یا نوعیت جنس
    $potNumber = PotNumber::find($id);

    if ($potNumber) {
        // حذف شماره و زیرنوع‌ها
        $potType = $potNumber->potType;
        $potNumber->potSubtypes()->delete();
        $potNumber->delete();

        // اگر بعد از حذف، نوعیت جنس شماره نداشت → خود نوعیت جنس هم حذف شود
        if ($potType->potNumbers()->count() === 0) {
            $potType->delete();
            return response()->json([
                'success' => true,
                'message' => 'شماره حذف شد و نوعیت جنس هم حذف شد چون دیگر شماره‌ای نداشت.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'شماره و زیرنوع‌های آن حذف شد، نوعیت جنس باقی ماند.'
        ]);

    } else {
        // اگر شماره پیدا نشد → ID مربوط به PotType بدون شماره است
        $potType = PotType::findOrFail($id);
        if ($potType->potNumbers->isEmpty()) {
            $potType->delete();
            return response()->json([
                'success' => true,
                'message' => 'نوعیت جنس حذف شد.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'نوعیت جنس دارای شماره است، ابتدا شماره‌ها را حذف کنید.'
        ], 400);
    }
}
public function search(Request $request)
{
    $query = $request->get('query');

    // جستجو بر اساس نام نوعیت جنس
    $potTypes = PotType::where('name', 'like', "%{$query}%")
        ->with(['potNumbers.potSubtypes'])
        ->paginate(10);

    if ($request->ajax()) {
        // فقط tbody جدول را رندر کن
        $table = view('backend.pot_types.index', compact('potTypes'))->render();

        return response()->json([
            'table' => $table
        ]);
    }

    // اگر ajax نبود، صفحه کامل را برگردان
    return view('backend.pot_types.index', compact('potTypes'));
}






}
