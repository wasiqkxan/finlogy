
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\BankSyncController;
use App\Http\Controllers\AccountShareController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('transactions', TransactionController::class);
    Route::apiResource('budgets', BudgetController::class);
    Route::apiResource('bills', BillController::class);
    
    Route::post('bills/{bill}/pay', [BillController::class, 'pay']);

    Route::prefix('reports')->group(function () {
        Route::get('spending-by-category', [ReportController::class, 'spendingByCategory']);
        Route::get('cash-flow', [ReportController::class, 'cashFlow']);
        Route::post('generate-pdf', [ReportController::class, 'generatePdfReport']);
    });

    Route::prefix('transactions/{transaction}')->group(function () {
        Route::post('attachments', [AttachmentController::class, 'store']);
        Route::get('attachments/{attachment}', [AttachmentController::class, 'show']);
        Route::delete('attachments/{attachment}', [AttachmentController::class, 'destroy']);
    });

    Route::post('bank-sync', [BankSyncController::class, 'store']);
    Route::post('bank-sync/sync', [BankSyncController::class, 'sync']);

    Route::post('accounts/{account}/share', [AccountShareController::class, 'store']);
    Route::delete('accounts/{account}/shares/{user}', [AccountShareController::class, 'destroy']);
});
