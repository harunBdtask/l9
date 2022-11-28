<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\Skillmatrix\Controllers\ProcessAssignToMachineController;
use SkylarkSoft\GoRMG\Skillmatrix\Controllers\SewingMachineController;
use SkylarkSoft\GoRMG\Skillmatrix\Controllers\SewingOperatorController;
use SkylarkSoft\GoRMG\Skillmatrix\Controllers\SewingProcessController;

const INDEX_ROUTE = '/';
const CREATE_ROUTE = '/create';
const EDIT_ROUTE = '/{id}/edit';
const SHOW_UPDATE_DELETE_ROUTE = '/{id}';

Route::group(['middleware' => ['web', 'auth', 'menu-auth']], function () {
    // Route for sewing machine
    Route::prefix('sewing-machines')->group(function () {
        Route::get(INDEX_ROUTE, [SewingMachineController::class, 'index']);
        Route::get(CREATE_ROUTE, [SewingMachineController::class, 'create']);
        Route::post(INDEX_ROUTE, [SewingMachineController::class, 'store']);
        Route::get(EDIT_ROUTE, [SewingMachineController::class, 'edit']);
        Route::put(SHOW_UPDATE_DELETE_ROUTE, [SewingMachineController::class, 'update']);
        Route::delete(SHOW_UPDATE_DELETE_ROUTE, [SewingMachineController::class, 'destroy']);
    });

    // Route for process
    Route::prefix('sewing-processes')->group(function () {
        Route::get(INDEX_ROUTE, [SewingProcessController::class, 'index']);
        Route::get(CREATE_ROUTE, [SewingProcessController::class, 'create']);
        Route::post(INDEX_ROUTE, [SewingProcessController::class, 'store']);
        Route::get('/{id}/details', [SewingProcessController::class, 'details']);
        Route::get(EDIT_ROUTE, [SewingProcessController::class, 'edit']);
        Route::put(SHOW_UPDATE_DELETE_ROUTE, [SewingProcessController::class, 'update']);
        Route::delete(SHOW_UPDATE_DELETE_ROUTE, [SewingProcessController::class, 'destroy']);
    });

    // Route for process assigned to machine
    Route::prefix('process-assign-to-machines')->group(function () {
        Route::get(INDEX_ROUTE, [ProcessAssignToMachineController::class, 'index']);
        Route::get(CREATE_ROUTE, [ProcessAssignToMachineController::class, 'create']);
        Route::post(INDEX_ROUTE, [ProcessAssignToMachineController::class, 'store']);
        Route::delete(SHOW_UPDATE_DELETE_ROUTE, [ProcessAssignToMachineController::class, 'destroy']);
    });

    // Route for sewing-operators
    Route::prefix('sewing-operators')->group(function () {
        Route::get(INDEX_ROUTE, [SewingOperatorController::class, 'index']);
        Route::get(CREATE_ROUTE, [SewingOperatorController::class, 'create']);
        Route::get(SHOW_UPDATE_DELETE_ROUTE, [SewingOperatorController::class, 'show']);
        Route::post(INDEX_ROUTE, [SewingOperatorController::class, 'store']);
        Route::get(EDIT_ROUTE, [SewingOperatorController::class, 'edit']);
        Route::put(SHOW_UPDATE_DELETE_ROUTE, [SewingOperatorController::class, 'update']);
        Route::delete(SHOW_UPDATE_DELETE_ROUTE, [SewingOperatorController::class, 'destroy']);
        Route::get('/add-skills/{sewingOperator}', [SewingOperatorController::class, 'operatorSkills']);
        Route::post('/add-skills', [SewingOperatorController::class, 'operatorSkillsPost']);
    });

    Route::get('/processes-by-machine-id/{sewingMachine}/{sewingOperatorId}', [SewingOperatorController::class, 'getProcessesByMachineId']);
    Route::get('/operator-skill-inventory', [SewingOperatorController::class, 'operatorSkillInventory']);
});
