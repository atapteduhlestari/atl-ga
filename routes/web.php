<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SBUController;
use App\Http\Controllers\SDBController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\CycleController;
use App\Http\Controllers\TrnSDBController;
use App\Http\Controllers\RenewalController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AppraisalController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AssetChildController;
use App\Http\Controllers\AssetGroupController;
use App\Http\Controllers\DocumentGroupController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\TrnRenewalController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\TrnMaintenanceController;
use App\Http\Controllers\UserController;

//Auth Login
Auth::routes([
    'register' => false,
    'reset' => false,
    'verify' => false,
]);

Route::middleware(['auth', 'reminder'])->group(function () {
    Route::get('image/uploads/images/assets/{filename}', [DashboardController::class, 'displayImage'])->name('image.displayImage');
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/report', [DashboardController::class, 'report']);
    Route::get('/group', [DashboardController::class, 'group']);
    Route::get('/change-password', [DashboardController::class, 'changePassword']);
    Route::post('/update-password', [DashboardController::class, 'updatePassword']);

    Route::get('/report-loan-detail', [LoanController::class, 'detailView']);
    Route::get('/loan-detail-export', [LoanController::class, 'reportDetail']);

    Route::get('/report-maintenance-detail', [TrnMaintenanceController::class, 'detailView']);
    Route::get('/trn-maintenance-detail-export', [TrnMaintenanceController::class, 'reportDetail']);
    Route::get('/trn-maintenance-plan-export', [TrnMaintenanceController::class, 'reportPlan']);
    Route::get('/report-maintenance-summary', [TrnMaintenanceController::class, 'summaryView']);
    Route::get('/trn-maintenance-summary-export', [TrnMaintenanceController::class, 'reportSummary']);

    Route::get('/report-renewal-detail', [TrnRenewalController::class, 'detailView']);
    Route::get('/trn-renewal-detail-export', [TrnRenewalController::class, 'reportDetail']);
    Route::get('/trn-renewal-plan-export', [TrnRenewalController::class, 'reportPlan']);
    Route::get('/report-renewal-summary', [TrnRenewalController::class, 'summaryView']);
    Route::get('/trn-renewal-summary-export', [TrnRenewalController::class, 'reportSummary']);


    Route::get('/report-asset-detail', [AssetController::class, 'detailView']);
    Route::get('/asset-detail-export', [AssetController::class, 'reportDetail']);
    Route::get('/report-asset-summary', [AssetController::class, 'summaryView']);
    Route::get('/asset-summary-export', [AssetController::class, 'reportSummary']);

    Route::get('/form', [DashboardController::class, 'formISO']);
    Route::post('/form', [DashboardController::class, 'createForm']);
    Route::get('/form/{param}', [DashboardController::class, 'downloadFormISO']);
    Route::delete('/form/{param}', [DashboardController::class, 'deleteForm']);

    Route::get('/asset-group/{assetGroup}', [AssetGroupController::class, 'show']);

    Route::get('/asset-parent/search/{param}', [AssetController::class, 'search'])
        ->name('assetSearch');


    Route::resource('/asset-parent', AssetController::class)
        ->parameters([
            'asset-parent' => 'asset',
        ])->except(['create']);

    Route::get('/asset-parent/export/{asset}', [AssetController::class, 'exportDetail']);

    Route::get('/asset-parent/docs/{asset}', [AssetController::class, 'documents'])
        ->name('assetDocuments');

    Route::post('/asset-parent/docs/add/{asset}', [AssetController::class, 'addDocuments'])
        ->name('assetAddDocuments');

    Route::get('/asset-parent/docs/edit/{asset}/{id}', [AssetController::class, 'editDocuments'])
        ->name('asseteditDocuments');

    Route::put('/asset-parent/docs/update/{asset}/{id}', [AssetController::class, 'updateDocuments'])
        ->name('assetupdateDocuments');

    Route::delete('/asset-parent/docs/delete/{asset}/{id}', [AssetController::class, 'deleteDocuments'])
        ->name('assetDeleteDocuments');

    Route::get('/asset-export/{param}', [AssetController::class, 'export'])->name('asset-export');

    // Route::get('/export/asset', [AssetController::class, 'exportView']);

    Route::get('/api/getData', [AssetController::class, 'getData'])->name('getData.asset');

    Route::resource('/asset-child', AssetChildController::class)->except(['create']);
    Route::get('/asset-child/download/{assetChild}', [AssetChildController::class, 'download']);

    Route::resource('/appraisal', AppraisalController::class);

    Route::resource('/trn-renewal', TrnRenewalController::class);
    Route::post('/trn-renewal/search', [TrnRenewalController::class, 'search']);
    Route::put('/trn-renewal/update-status/{trnRenewal}', [TrnRenewalController::class, 'updateStatus']);
    Route::put('/trn-renewal/update-status-plan/{trnRenewal}', [TrnRenewalController::class, 'updateStatusPlan']);
    Route::get('/trn-renewal/download/{trnRenewal}', [TrnRenewalController::class, 'download']);
    Route::get('/trn-renewal-export', [TrnRenewalController::class, 'export'])->name('renewal-export');

    Route::resource('/trn-maintenance', TrnMaintenanceController::class);
    Route::post('/trn-maintenance/search', [TrnMaintenanceController::class, 'search']);
    Route::put('/trn-maintenance/update-status/{trnMaintenance}', [TrnMaintenanceController::class, 'updateStatus']);
    Route::put('/trn-maintenance/update-status-plan/{trnMaintenance}', [TrnMaintenanceController::class, 'updateStatusPlan']);
    Route::get('/trn-maintenance/download/{trnMaintenance}', [TrnMaintenanceController::class, 'download']);
    Route::get('/trn-maintenance-export', [TrnMaintenanceController::class, 'export'])->name('maintenance-export');
    Route::get('/trn-maintenance/detail-report', [TrnMaintenanceController::class, 'detailReport'])->name('maintenance-detail-report');

    Route::resource('/trn-sdb', TrnSDBController::class)->parameters([
        'trn-sdb' => 'trnSDB',
    ]);
    Route::post('/trn-sdb/search', [TrnSDBController::class, 'search']);
    Route::get('/trn-sdb/asset/{id}', [TrnSDBController::class, 'formAsset']);
    Route::post('/trn-sdb/asset/store', [TrnSDBController::class, 'storeAsset']);
    Route::delete('/trn-sdb/asset/{id}', [TrnSDBController::class, 'deleteAsset']);
    Route::get('/trn-sdb/doc/{id}', [TrnSDBController::class, 'formDoc']);
    Route::post('/trn-sdb/doc/store', [TrnSDBController::class, 'storeDoc']);
    Route::delete('/trn-sdb/doc/{id}', [TrnSDBController::class, 'deleteDoc']);

    Route::get('/full-calendar', [DashboardController::class, 'fullCalendar']);
    Route::get('/timeline', [DashboardController::class, 'timeline']);
});

Route::group(['middleware' => ['auth', 'superadmin', 'reminder']], function () {

    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user', [UserController::class, 'store']);
    Route::get('/user/{user:username}/edit', [UserController::class, 'edit']);
    Route::put('/user/{user}', [UserController::class, 'update']);
    Route::delete('/user/{user}', [UserController::class, 'destroy']);

    Route::resource('/asset-group', AssetGroupController::class)
        ->except(['create']);

    Route::resource('/document-group', DocumentGroupController::class)
        ->except(['create']);

    Route::resource('/maintenance', MaintenanceController::class)->except(['create']);
    Route::resource('/renewal', RenewalController::class)->except(['create']);
    Route::resource('/sdb', SDBController::class)->except(['create']);
    Route::post('/sdb/remove-document/{id}', [SDBController::class, 'removeDocs']);

    Route::resource('/sbu', SBUController::class)->except(['create']);
    Route::resource('/employee', EmployeeController::class)->except(['create']);
    Route::resource('/cycle', CycleController::class)->except(['create']);
    Route::resource('/loan', LoanController::class)->except(['create']);
});

// Route::get('/get-api', function () {
//     $response = Http::get('http://192.168.1.11:8101/api/tes-api');
//     $json = $response->json();

//     $data = collect($json)->where('id', 1)->first();
//     return $data;
// });