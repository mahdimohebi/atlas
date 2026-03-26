<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\backend\AluminumDashboardController;
use App\Http\Controllers\backend\AluminumExpenseController;
use App\Http\Controllers\backend\AluminumReportController;
use App\Http\Controllers\backend\AttendanceController;
use App\Http\Controllers\backend\ClientController;
use App\Http\Controllers\backend\ContractController;
use App\Http\Controllers\backend\CustomerController;
use App\Http\Controllers\backend\CustomerPaymentController;
use App\Http\Controllers\backend\DashboardController;
use App\Http\Controllers\backend\DesignController;
use App\Http\Controllers\backend\DesignpotController;
use App\Http\Controllers\backend\EmployeeController;
use App\Http\Controllers\backend\FactoryExpenseController;
use App\Http\Controllers\backend\GuaranteeController;
use App\Http\Controllers\backend\PaymentController;
use App\Http\Controllers\backend\PouringpotController;
use App\Http\Controllers\backend\SalaryController;
use App\Http\Controllers\backend\SaleController;
use App\Http\Controllers\backend\SupplierController;
use App\Http\Controllers\backend\TransactionController;
use App\Http\Controllers\backend\UserController;
use App\Http\Controllers\backend\FactoryPurchaseController;
use App\Http\Controllers\backend\FactoryPurchasePaymentController;
use App\Http\Controllers\backend\PotTypeController;
use Illuminate\Container\Attributes\Auth;

Route::middleware(['auth'])->group(function () {


Route::middleware(['auth', 'trade_al'])->group(function () {

    Route::resource('supplier', SupplierController::class);
    Route::resource('client', ClientController::class);
    Route::resource('payment', PaymentController::class);
    Route::resource('aluminum_expenses', AluminumExpenseController::class);
    Route::resource('transaction', TransactionController::class);

    Route::get('/transactions/create-for-supplier/{supplierId}', [TransactionController::class, 'createForSupplier'])
        ->name('transactions.createForSupplier');
    Route::post('/transactions/store-for-supplier/{supplierId}', [TransactionController::class, 'storeForSupplier'])
        ->name('transactions.storeForSupplier');




    Route::get('/transactions/{transaction}/payment', [TransactionController::class, 'show'])
        ->name('transaction.payment');

    Route::get('/transactions/{transaction}/payment', [PaymentController::class, 'create_payment'])
        ->name('transaction.payment');

    // ارسال پرداخت (store) به تراکنش
    Route::post('/transactions/{transaction}/payment', [PaymentController::class, 'store_payment'])
        ->name('transaction.paymentstore');


    Route::get('/transactions/create-for-client/{clientId}', [TransactionController::class, 'createForClient'])
        ->name('transactions.createForClient');
    Route::post('/transactions/store-for-client/{clientId}', [TransactionController::class, 'storeForClient'])
        ->name('transactions.storeForClient');

    // ============= live search ================================
    Route::get('suppliers/search', [SupplierController::class, 'search'])->name('supplier.search');
    Route::get('/transactions/search', [TransactionController::class, 'search'])
    ->name('transaction.search');
    Route::get('clients/search', [ClientController::class, 'search'])->name('client.search');
    Route::get('payments/search', [PaymentController::class, 'search'])->name('payment.search');
    Route::get('aluminum-expenses/search', [AluminumExpenseController::class, 'search'])->name('aluminum_expenses.search');

    Route::get('aluminum_dashboard',[AluminumDashboardController::class, 'index'])->name('al_dashboard.index');

});


Route::middleware(['auth', 'factory'])->group(function () {

    Route::resource('employee', EmployeeController::class);
    Route::resource('customer', CustomerController::class);
    Route::resource('salary', SalaryController::class);
    Route::resource('contract', ContractController::class);
    Route::resource('attendance', AttendanceController::class);
    Route::resource('customer_payment', CustomerPaymentController::class);
    Route::resource('design_pot', DesignpotController::class);
    Route::resource('pouring_pot', PouringpotController::class);
    Route::resource('sales', SaleController::class);
    Route::resource('user', UserController::class);
    Route::resource('factory_expenses', FactoryExpenseController::class);
    Route::resource('pot_types',PotTypeController::class);
    Route::resource('designs', DesignController::class);

    Route::get('/attendance/{employee}', [AttendanceController::class, 'show'])
    ->name('attendance.show');

    // ============= report ========================================

    Route::get('/al_report',[AluminumReportController::class, 'al_report'])->name('al.report');
    Route::get('/pouring_report',[AluminumReportController::class, 'pouring_report'])->name('pouring.report');
    Route::get('/design_report',[AluminumReportController::class, 'design_report'])->name('design.report');
    Route::get('/sale_report',[AluminumReportController::class, 'sale_report'])->name('sale.report');

// =============end of report ==================================
    
    Route::get('/contracts/create-for-employee/{employee}', [ContractController::class, 'createForEmployee'])
        ->name('contracts.createForEmployee');

    Route::get('/salaries/create/{employee}', [SalaryController::class, 'create_salary'])
        ->name('salaries.create');

    Route::get('/attendance_report',[AttendanceController::class, 'report'])->name('attendance.report');

    Route::get('sale/create/{id}', [SaleController::class, 'create'])->name('sale.create');
    Route::post('sale', [SaleController::class, 'store'])->name('sale.store');
    Route::get('sale/{sale}/edit', [SaleController::class, 'edit'])->name('sale.edit');
    Route::put('sale/{sale}', [SaleController::class, 'update'])->name('sale.update');
    Route::delete('sale/{sale}', [SaleController::class, 'destroy'])->name('sale.destroy');
    Route::get('sale/{sale}/invoice', [SaleController::class, 'invoice'])->name('sale.invoice');
    // Route::get('customer/{sale}/payments', [CustomerPaymentController::class, 'index'])
    //     ->name('customer_payment.index');

    Route::get('customer-payments/{sale_id}', [CustomerPaymentController::class, 'index'])
        ->name('customer_payment.index');

    Route::get('customer-payments/{sale_id}/create', [CustomerPaymentController::class, 'create'])
        ->name('customer_payment.create');

    Route::get('customer-payments/{id}/edit', [CustomerPaymentController::class, 'edit'])
        ->name('customer_payment.edit');
        
    Route::put('customer-payments/{id}', [CustomerPaymentController::class, 'update'])
        ->name('customer_payment.update');

    Route::delete('customer-payments/{id}', [CustomerPaymentController::class, 'destroy'])
        ->name('customer_payment.destroy');
    //  ============== factory purchase ========================================================


    // صفحه لیست خریدها
    Route::get('/factory-purchases', [FactoryPurchaseController::class, 'index'])
        ->name('factory-purchases.index');

    // فرم ثبت خرید
    Route::get('/factory-purchases/create', [FactoryPurchaseController::class, 'create'])
        ->name('factory-purchases.create');

    // ذخیره خرید جدید
    Route::post('/factory-purchases', [FactoryPurchaseController::class, 'store'])
        ->name('factory-purchases.store');

    // فرم ویرایش خرید
    Route::get('/factory-purchases/{purchase}/edit', [FactoryPurchaseController::class, 'edit'])
        ->name('factory-purchases.edit');

    // بروزرسانی خرید
    Route::put('/factory-purchases/{purchase}', [FactoryPurchaseController::class, 'update'])
        ->name('factory-purchases.update');

    // حذف خرید
    Route::delete('/factory-purchases/{purchase}', [FactoryPurchaseController::class, 'destroy'])
        ->name('factory-purchases.destroy');


    // صفحه اختصاصی پرداخت‌ها
    Route::get('/factory-purchase/{id}/payments', [FactoryPurchasePaymentController::class,'paymentsPage'])
        ->name('factory-purchase.payments');

    // دریافت پرداخت‌ها (JSON) برای Ajax
    Route::get('/factory-purchase/{id}/payments/list', [FactoryPurchasePaymentController::class,'getPayments'])
        ->name('factory-purchase.payments.list');

    // ثبت پرداخت جدید
    Route::post('/factory-purchase/payments', [FactoryPurchasePaymentController::class,'storePayment'])
        ->name('factory-purchase.payments.store');

    // ویرایش پرداخت
    Route::put('/factory-purchase/payments/{id}', [FactoryPurchasePaymentController::class,'updatePayment'])
        ->name('factory-purchase.payments.update');

    // حذف پرداخت
    Route::delete('/factory-purchase/payments/{id}', [FactoryPurchasePaymentController::class,'deletePayment'])
        ->name('factory-purchase.payments.delete');

    //  live search ===========================================================================================
    Route::get('employees/search', [EmployeeController::class, 'search'])->name('employee.search');
    Route::get('attendances/search', [AttendanceController::class, 'search'])->name('attendance.search');
    Route::get('customers/search', [CustomerController::class, 'search'])->name('customer.search');
    Route::get('sales/search', [SaleController::class, 'search'])->name('sales.search');
    Route::get('factory_expenses/search', [FactoryExpenseController::class, 'search'])->name('factory_expenses.search');
    Route::get('/pouring_pot/search', [PouringpotController::class, 'search'])->name('pouring_pot.search');
    Route::get('pot_types/search', [PotTypeController::class, 'search'])->name('pot_types.search');


    // ======================================================================================
    

    Route::get('pouringpots/search', [PouringpotController::class, 'search'])->name('pouringpot.search'); 
    Route::get('designpots/search', [DesignpotController::class, 'search'])->name('designpots.search');

    Route::resource('guarantee', GuaranteeController::class);
    Route::get('guarantees/search', [GuaranteeController::class, 'search'])->name('guarantees.search');

    // dashboard =====================================================
    Route::get('factory_dashboard',[DashboardController::class, 'index'])->name('fa_dashboard.index');


});



});
  
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.submit');

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/', function () {
    return redirect()->route('login');
});





