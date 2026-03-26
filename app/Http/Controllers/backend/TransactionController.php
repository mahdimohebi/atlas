<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Transaction::query();

        if ($request->has('type')) {
            $query->where('type', $request->type); // 'purchase' یا 'sale'
        }

        $transactions = $query->orderBy('transaction_date', 'desc')->simplepaginate(10);
        return view('backend.transaction.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Transaction $transaction)
    {
        $payments = $transaction->payments()->orderBy('payment_date', 'desc')->get();
        return view('backend.transaction.show', compact('transaction', 'payments'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = Transaction::findOrFail($id);

        // اگر تراکنش از نوع خرید باشد، فروشنده را می‌گیریم
        if($transaction->type == 'purchase') {
            $supplier = $transaction->supplier; // رابطه Supplier در مدل Transaction باید تعریف شده باشد
            $client = null;
            $type = 'purchase';
        } else {
            $client = $transaction->client; // رابطه Client در مدل Transaction باید تعریف شده باشد
            $supplier = null;
            $type = 'sale';
        }

        return view('backend.transaction.edit', compact('transaction', 'supplier', 'client', 'type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
{
    $transaction = Transaction::findOrFail($id);

    // تبدیل اعداد فارسی به انگلیسی
    $request->merge([
        'quantity'       => persianToEnglishNumber($request->quantity),
        'price_per_unit' => persianToEnglishNumber($request->price_per_unit),
        'exchange_rate'  => persianToEnglishNumber($request->exchange_rate),
    ]);

    // اعتبارسنجی
    $request->validate([
        'category'         => 'required|in:soft,hard',
        'quantity'         => 'required|numeric|min:0.01',
        'price_per_unit'   => 'required|numeric|min:0.01',
        'transaction_date' => 'required|date',
        'currency'         => 'required|in:AFN,USD',
        'exchange_rate'    => 'required_if:currency,USD|numeric|min:1',
    ], [
        'category.required'          => 'وارد کردن دسته‌بندی الزامی است.',
        'quantity.required'          => 'وارد کردن مقدار الزامی است.',
        'price_per_unit.required'    => 'وارد کردن قیمت واحد الزامی است.',
        'transaction_date.required'  => 'وارد کردن تاریخ تراکنش الزامی است.',
        'currency.required'          => 'وارد کردن واحد پول الزامی است.',
        'exchange_rate.required_if'  => 'وارد کردن نرخ ارز روز الزامی است.',
    ]);

    $quantity = (float) $request->quantity;
    $price    = (float) $request->price_per_unit;
    $rate     = (float) $request->exchange_rate;

    // محاسبه جمع کل
    $totalPrice = $quantity * $price;

    // اگر USD باشد، تبدیل به AFN
    if ($request->currency === 'USD') {
        $totalPrice = $totalPrice * $rate;
    }

    // بروزرسانی تراکنش
    $transaction->category         = $request->category === 'نرم' ? 'soft' : 'hard';
    $transaction->quantity         = $quantity;
    $transaction->price_per_unit   = $price;
    $transaction->total_price      = $totalPrice; // همیشه AFN
    $transaction->transaction_date = $request->transaction_date;
    $transaction->currency         = $request->currency;
    $transaction->exchange_rate    = $request->currency === 'USD' ? $rate : null;
    $transaction->description      = $request->description;

    $transaction->save();

    return redirect()
        ->route('transaction.index', ['type' => $transaction->type])
        ->with('success', 'تراکنش با موفقیت بروزرسانی شد.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transaction $transaction)
    {
        $type = $transaction->type; // purchase یا sale
        $transaction->delete();

        return response()->json([
            'success' => true,
            'message' => 'تراکنش با موفقیت حذف شد.',
            'redirect' => route('transaction.index', ['type' => $type])
        ]);
    }

// ==================================================================================================
    // فرم ثبت تراکنش برای فروشنده
    public function createForSupplier($supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $type = 'purchase'; // نوع تراکنش خرید
        // خلاصه اطلاعات
        $summary = [
            'soft_total_purchase' => Transaction::where('category', 'soft')->where('type', 'purchase')->sum('quantity'),
            'hard_total_purchase' => Transaction::where('category', 'hard')->where('type', 'purchase')->sum('quantity'),
            'soft_total_sale' => Transaction::where('category', 'soft')->where('type', 'sale')->sum('quantity'),
            'hard_total_sale' => Transaction::where('category', 'hard')->where('type', 'sale')->sum('quantity'),

            'remaining_hard' =>Transaction::where('category', 'hard')->where('type', 'purchase')->sum('quantity')-Transaction::where('category', 'hard')->where('type', 'sale')->sum('quantity'),
            'remaining_soft'=>Transaction::where('category', 'soft')->where('type', 'purchase')->sum('quantity')-Transaction::where('category', 'soft')->where('type', 'sale')->sum('quantity'),
        ];
        return view('backend.transaction.create', compact('supplier', 'type','summary'));
        
    }
public function storeForSupplier(Request $request, $supplier_id)
{
    // تبدیل اعداد فارسی به انگلیسی
    $request->merge([
        'quantity'        => persianToEnglishNumber($request->quantity),
        'price_per_unit'  => persianToEnglishNumber($request->price_per_unit),
        'exchange_rate'   => persianToEnglishNumber($request->exchange_rate),
    ]);

    // اعتبارسنجی
    $request->validate([
        'category'          => 'required|in:نرم,سخت',
        'quantity'          => 'required|numeric|min:0.01',
        'price_per_unit'    => 'required|numeric|min:0.01',
        'transaction_date'  => 'required|date',
        'currency'          => 'required|in:AFN,USD',
        'exchange_rate'     => 'required_if:currency,USD|numeric|min:1',
    ], [
        'category.required'         => 'وارد کردن دسته‌بندی الزامی است.',
        'quantity.required'         => 'وارد کردن مقدار الزامی است.',
        'price_per_unit.required'   => 'وارد کردن قیمت واحد الزامی است.',
        'transaction_date.required' => 'وارد کردن تاریخ تراکنش الزامی است.',
        'currency.required'         => 'وارد کردن واحد پول الزامی است.',
        'exchange_rate.required_if' => 'وارد کردن نرخ ارز روز الزامی است.',
    ]);

    // مقادیر عددی
    $quantity = (float) $request->quantity;
    $price    = (float) $request->price_per_unit;
    $rate     = (float) $request->exchange_rate;

    // محاسبه جمع کل
    $totalPrice = $quantity * $price;

    // اگر USD باشد، تبدیل به AFN
    if ($request->currency === 'USD') {
        $totalPrice = $totalPrice * $rate;
    }

    // ثبت تراکنش
    Transaction::create([
        'supplier_id'      => $supplier_id,
        'type'             => 'purchase',
        'category'         => $request->category === 'نرم' ? 'soft' : 'hard',
        'quantity'         => $quantity,
        'price_per_unit'   => $price,
        'total_price'      => $totalPrice, // همیشه AFN
        'currency'         => $request->currency,
        'exchange_rate'    => $request->currency === 'USD' ? $rate : null,
        'transaction_date' => $request->transaction_date,
        'description'      => $request->description,
    ]);

    return redirect()
        ->route('transaction.index', ['type' => 'purchase'])
        ->with('success', 'خرید با موفقیت ثبت شد.');
}


// =======================================================================================================

    // فرم ثبت تراکنش برای فروشنده
    public function createForClient($clientId)
    {
        $client = Client::findOrFail($clientId);
        $type = 'sale'; // نوع تراکنش خرید
         // خلاصه اطلاعات
        $summary = [
            'soft_total_purchase' => Transaction::where('category', 'soft')->where('type', 'purchase')->sum('quantity'),
            'hard_total_purchase' => Transaction::where('category', 'hard')->where('type', 'purchase')->sum('quantity'),
            'soft_total_sale' => Transaction::where('category', 'soft')->where('type', 'sale')->sum('quantity'),
            'hard_total_sale' => Transaction::where('category', 'hard')->where('type', 'sale')->sum('quantity'),

            'remaining_hard' =>Transaction::where('category', 'hard')->where('type', 'purchase')->sum('quantity')-Transaction::where('category', 'hard')->where('type', 'sale')->sum('quantity'),
            'remaining_soft'=>Transaction::where('category', 'soft')->where('type', 'purchase')->sum('quantity')-Transaction::where('category', 'soft')->where('type', 'sale')->sum('quantity'),
        ];
        return view('backend.transaction.create', compact('client', 'type','summary')); 
        
    }
// -----------------------------------------------------------------------------------------

public function storeForClient(Request $request, $client_id)
{
    $request->merge([
        'quantity'       => persianToEnglishNumber($request->quantity),
        'price_per_unit' => persianToEnglishNumber($request->price_per_unit),
        'exchange_rate'  => persianToEnglishNumber($request->exchange_rate),
    ]);

    $request->validate([
        'category'          => 'required|in:نرم,سخت',
        'quantity'          => 'required|numeric|min:0.01',
        'price_per_unit'    => 'required|numeric|min:0.01',
        'transaction_date'  => 'required|date',
        'currency'          => 'required|in:AFN,USD',
        'exchange_rate'     => 'required_if:currency,USD|numeric|min:1',
    ], [
        'category.required'         => 'وارد کردن دسته‌بندی الزامی است.',
        'category.in'               => 'دسته‌بندی وارد شده معتبر نیست.',
        'quantity.required'         => 'وارد کردن مقدار الزامی است.',
        'price_per_unit.required'   => 'وارد کردن قیمت واحد الزامی است.',
        'transaction_date.required' => 'وارد کردن تاریخ تراکنش الزامی است.',
        'currency.required'         => 'وارد کردن واحد پول الزامی است.',
        'exchange_rate.required_if' => 'وارد کردن نرخ ارز روز الزامی است.',
    ]);

    $quantity = (float) $request->quantity;
    $price    = (float) $request->price_per_unit;
    $rate     = (float) $request->exchange_rate;

    $category = $request->category === 'نرم' ? 'soft' : 'hard';

    // بررسی موجودی باقی مانده
    $remaining = $category === 'soft'
        ? Transaction::where('category', 'soft')->where('type', 'purchase')->sum('quantity') 
          - Transaction::where('category', 'soft')->where('type', 'sale')->sum('quantity')
        : Transaction::where('category', 'hard')->where('type', 'purchase')->sum('quantity') 
          - Transaction::where('category', 'hard')->where('type', 'sale')->sum('quantity');

    if ($quantity > $remaining) {
        return redirect()->back()->withErrors([
            'quantity' => "مقدار وارد شده بیشتر از موجودی باقی‌مانده است. موجودی باقی‌مانده: $remaining کیلوگرم"
        ])->withInput();
    }

    $totalPrice = $quantity * $price;
    if ($request->currency === 'USD') {
        $totalPrice *= $rate;
    }

    Transaction::create([
        'client_id'       => $client_id,
        'type'            => 'sale',
        'category'        => $category,
        'quantity'        => $quantity,
        'price_per_unit'  => $price,
        'total_price'     => $totalPrice,
        'currency'        => $request->currency,
        'exchange_rate'   => $request->currency === 'USD' ? $rate : null,
        'transaction_date'=> $request->transaction_date,
        'description'     => $request->description,
    ]);

    return redirect()->route('transaction.index', ['type' => 'sale'])
                     ->with('success', 'فروش با موفقیت ثبت شد.');
}




// ======================== live search ==============================
public function search(Request $request)
{
    $search = $request->get('query');
    $type   = $request->get('type'); // purchase | sale

    $transactions = Transaction::query()

        // فیلتر نوع تراکنش
        ->when($type, function ($q) use ($type) {
            $q->where('type', $type);
        })

        // فیلتر سرچ (گروه‌بندی صحیح OR ها)
        ->when($search, function ($q) use ($search) {
            $q->where(function ($qq) use ($search) {
                $qq->where('id', 'like', "%{$search}%")
                   ->orWhereHas('supplier', function ($q2) use ($search) {
                       $q2->where('name', 'like', "%{$search}%");
                   })
                   ->orWhereHas('client', function ($q2) use ($search) {
                       $q2->where('name', 'like', "%{$search}%");
                   });
            });
        })

        // مرتب‌سازی بر اساس تاریخ تراکنش
        ->orderBy('transaction_date', 'desc')

        ->paginate(10);

    if ($request->ajax()) {
        return response()->json([
            'table' => view('backend.transaction.table', compact('transactions'))->render(),
            'pagination' => view('backend.transaction.pagination', compact('transactions'))->render(),
        ]);
    }

    return view('backend.transaction.index', compact('transactions'));
}






    
}
