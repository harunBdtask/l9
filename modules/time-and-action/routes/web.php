<?php

use SkylarkSoft\GoRMG\TimeAndAction\Controllers\TimeAndActionController;
use SkylarkSoft\GoRMG\TimeAndAction\Controllers\TNAProgressReportController;
use SkylarkSoft\GoRMG\TimeAndAction\Controllers\TNAReportController;
use SkylarkSoft\GoRMG\TimeAndAction\Controllers\TnaTaskEntryController;
use SkylarkSoft\GoRMG\TimeAndAction\Controllers\TNATemplateController;
use SkylarkSoft\GoRMG\TimeAndAction\Controllers\UserWiseTaskPermission;

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\TimeAndAction\Controllers'], function () {
    Route::group(['prefix' => 'tna-task-entry'], function () {
        Route::get('/{any?}', [TimeAndActionController::class, 'index'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'tna-template-creation'], function () {
        Route::get('/{any?}', [TimeAndActionController::class, 'index'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'tna-reports'], function () {
        Route::get('/{any?}', [TimeAndActionController::class, 'index'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'tna-progress-report'], function () {
        Route::get('/{any?}', [TimeAndActionController::class, 'index'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'user-wise-task-edit-permission'], function () {
        Route::get('/{any?}', [TimeAndActionController::class, 'index'])
            ->where('any', '.*');
    });

    Route::group(['prefix' => 'time-and-action'], function () {
        Route::get('progress-report-download', [TNAProgressReportController::class, 'pdf']);
    });

    Route::group(['prefix' => 'api/v1/time-and-action'], function () {
        Route::post('/group-add', [TnaTaskEntryController::class, 'groupAdd']);
        Route::get('/get-group/{id}', [TnaTaskEntryController::class, 'getGroup']);
        Route::get('/group-list', [TnaTaskEntryController::class, 'groupList']);
        Route::get('/task-list', [TnaTaskEntryController::class, 'taskList']);
        Route::get('/get-task/{id}', [TnaTaskEntryController::class, 'getTask']);
        Route::delete('/delete-task/{id}', [TnaTaskEntryController::class, 'deleteTask']);
        Route::post('/task', [TnaTaskEntryController::class, 'createTask']);
        Route::put('/update-task/{id}', [TnaTaskEntryController::class, 'updateTask']);
        Route::get('/get-group-with-tasks', [TnaTaskEntryController::class, 'groupWithTask']);
        Route::get('/get-lead-time', [TnaTaskEntryController::class, 'getLeadTime']);
        Route::post('/task-sort', [TnaTaskEntryController::class, 'taskSort']);

        Route::get('/search-report-data', [TNAReportController::class, 'search']);
        Route::post('/get-search-related-data', [TNAReportController::class, 'getSearchRelatedData']);
        Route::get('/get-order', [TNAReportController::class, 'getOrder']);
        Route::put('/update-report-task/{id}', [TNAReportController::class, 'updateTask']);
        Route::get('/tna-report-excel', [TNAReportController::class, 'reportExcel']);

        Route::group(['prefix' => 'templates'], function () {
            Route::get('', [TNATemplateController::class, 'index']);
            Route::get('template-copy', [TNATemplateController::class, 'templateCopy']);
            Route::get('/{id}', [TNATemplateController::class, 'edit']);
            Route::post('', [TNATemplateController::class, 'store']);
            Route::delete('/{template}', [TNATemplateController::class, 'destroy']);
            Route::post('{template}/details', [TNATemplateController::class, 'storeDetails']);
            Route::delete('/delete-template-details/{templateDetail}', [TNATemplateController::class, 'destroyDetails']);
        });

        Route::get('tna-progress-report', [TNAProgressReportController::class, 'report']);
        Route::get('tna-progress-report-sort', [TNAProgressReportController::class, 'sortProgressReportTaskSequence']);
        Route::post('process-tna-progress-report', [TNAProgressReportController::class, 'process']);

        Route::group(['prefix' => 'user-wise-task-permission'], function () {
            Route::get('', [UserWiseTaskPermission::class, 'index']);
            Route::get('/buyer-tasks/{buyerId}', [UserWiseTaskPermission::class, 'buyerWiseTask']);
            Route::put('/buyer-tasks/{buyerId}', [UserWiseTaskPermission::class, 'update']);
        });
    });

    Route::group(['prefix' => 'tna-report-dispatch'], function () {
        Route::get('/', [TNAReportController::class, 'reportDispatch']);
    });
});

Route::get('test-tna', function () {
   return public_path('storage/orders/image/1633165196.jpeg');
});
