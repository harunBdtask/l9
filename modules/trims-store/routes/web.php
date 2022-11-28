<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\API\BookingWiseTrimsDetailsController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\API\StyleWiseGmtItemsController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\API\TrimsBookingNosApiController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\API\TrimsStoreReceiveChallanNosApiController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreIssue\TrimsStoreIssueController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreIssue\TrimsStoreIssueDetailsController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreIssueReturn\TrimsStoreIssueReturnController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceive\TrimsStoreReceiveController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceive\TrimsStoreReceiveDetailsController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceive\TrimsStoreReceiveUniqueIdController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceiveReturn\ItemWiseReceiveDetailsController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturnController;
use SkylarkSoft\GoRMG\TrimsStore\Controllers\V3\TrimsStoreReceiveReturn\TrimsStoreReceiveReturnDetailsController;

Route::group([
    'middleware' => ['web', 'auth', 'menu-auth'],
    'prefix' => 'trims-store',
], function () {
    Route::group(['prefix' => 'receive'], function () {
        Route::get('', [TrimsStoreReceiveController::class, 'index']);
        Route::post('', [TrimsStoreReceiveController::class, 'store']);
        Route::get('{receive}/edit', [TrimsStoreReceiveController::class, 'edit']);
        Route::get('{receive}/view', [TrimsStoreReceiveController::class, 'view']);
        Route::get('{receive}/pdf', [TrimsStoreReceiveController::class, 'pdf']);
        Route::put('{receive}', [TrimsStoreReceiveController::class, 'update']);
        Route::delete('{receive}', [TrimsStoreReceiveController::class, 'destroy']);
        Route::get('fetch-unique-id', TrimsStoreReceiveUniqueIdController::class);

        Route::group(['prefix' => 'details'], function () {
            Route::get('{receive}', [TrimsStoreReceiveDetailsController::class, 'getDetails']);
            Route::post('{receive}', [TrimsStoreReceiveDetailsController::class, 'store']);
            Route::post('{receive}/store-breakdown', [TrimsStoreReceiveDetailsController::class, 'storeBookingBreakdown']);
            Route::put('{detail}', [TrimsStoreReceiveDetailsController::class, 'update']);
            Route::delete('{detail}', [TrimsStoreReceiveDetailsController::class, 'destroy']);
        });

        Route::get('{any?}', [TrimsStoreReceiveController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'receive-return'], function () {
        Route::get('', [TrimsStoreReceiveReturnController::class, 'index']);
        Route::post('', [TrimsStoreReceiveReturnController::class, 'store']);
        Route::get('{receiveReturn}/edit', [TrimsStoreReceiveReturnController::class, 'edit']);
        Route::get('{receiveReturn}/view', [TrimsStoreReceiveReturnController::class, 'view']);
        Route::get('{receiveReturn}/pdf', [TrimsStoreReceiveReturnController::class, 'pdf']);
        Route::put('{receiveReturn}', [TrimsStoreReceiveReturnController::class, 'update']);
        Route::delete('{receiveReturn}', [TrimsStoreReceiveReturnController::class, 'destroy']);

        Route::group(['prefix' => 'details'], function () {
            Route::get('fetch-item-wise-receive-details', ItemWiseReceiveDetailsController::class);
            Route::get('{receiveReturn}', [TrimsStoreReceiveReturnDetailsController::class, 'getDetails']);
            Route::post('{receiveReturn}', [TrimsStoreReceiveReturnDetailsController::class, 'store']);
            Route::put('{detail}', [TrimsStoreReceiveReturnDetailsController::class, 'update']);
            Route::delete('{detail}', [TrimsStoreReceiveReturnDetailsController::class, 'destroy']);
        });

        Route::get('{any?}', [TrimsStoreReceiveReturnController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'issues'], function () {
        Route::get('', [TrimsStoreIssueController::class, 'index']);
        Route::post('', [TrimsStoreIssueController::class, 'store']);
        Route::get('{issue}/edit', [TrimsStoreIssueController::class, 'edit']);
        Route::get('{issue}/view', [TrimsStoreIssueController::class, 'view']);
        Route::get('{issue}/pdf', [TrimsStoreIssueController::class, 'pdf']);
        Route::put('{issue}', [TrimsStoreIssueController::class, 'update']);
        Route::delete('{issue}', [TrimsStoreIssueController::class, 'destroy']);

        Route::group(['prefix' => 'details'], function () {
            Route::get('{issue}', [TrimsStoreIssueDetailsController::class, 'getDetails']);
            Route::post('{issue}', [TrimsStoreIssueDetailsController::class, 'store']);
            Route::put('{detail}', [TrimsStoreIssueDetailsController::class, 'update']);
            Route::delete('{detail}', [TrimsStoreIssueDetailsController::class, 'destroy']);
        });

        Route::get('{any?}', [TrimsStoreIssueController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'issue-return'], function () {
        Route::get('', [TrimsStoreIssueReturnController::class, 'index']);
        Route::post('', [TrimsStoreIssueReturnController::class, 'store']);
        Route::get('{issueReturn}/edit', [TrimsStoreIssueReturnController::class, 'edit']);
        Route::put('{issueReturn}', [TrimsStoreIssueReturnController::class, 'update']);
        Route::delete('{issueReturn}', [TrimsStoreIssueReturnController::class, 'destroy']);

        Route::group(['prefix' => 'details'], function () {
            // TODO
        });

        Route::get('{any?}', [TrimsStoreIssueReturnController::class, 'create'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'common-api'], function () {
        Route::get('style-wise-gmt-items/{order}', StyleWiseGmtItemsController::class);
        Route::get('fetch-receive-challan-nos', TrimsStoreReceiveChallanNosApiController::class);
        Route::get('fetch-style-wise-booking-nos', TrimsBookingNosApiController::class);
        Route::get('fetch-booking-wise-trims-details/{trimsBooking}', BookingWiseTrimsDetailsController::class);
    });
});
