<?php


Route::group(['middleware' => ['web', 'auth'], 'namespace' => 'SkylarkSoft\GoRMG\SecurityControl\Controllers'], function () {
    Route::get('vehicle-system', 'VehicleSystemController@index')->name('vehicle.index');

    //vehicle route
    Route::post('vehicle-post/{id?}', 'VehicleSystemController@vehicleStore')->name('vehicle.store');
    Route::get('vehicle-edit/{id}', 'VehicleSystemController@vehicleEdit')->name('vehicle.edit');
    Route::delete('vehicle-delete/{id}', 'VehicleSystemController@vehicleDelete')->name('vehicle.delete');


    //driver route
    Route::post('driver-post/{id?}', 'VehicleSystemController@driverStore')->name('driver.store');
    Route::get('driver-edit/{id}', 'VehicleSystemController@driverEdit')->name('driver.edit');
    Route::delete('driver-delete/{id}', 'VehicleSystemController@driverDelete')->name('driver.delete');

    //vehicle assign
    Route::get('vehicle-assign-system', 'VehicleAssignSystemController@index')->name('vehicle-assign-index');
    Route::post('vehicle-assign-post', 'VehicleAssignSystemController@store')->name('vehicle-assign.store');
    Route::get('vehicle-assign-edit/{id}', 'VehicleAssignSystemController@edit')->name('vehicle-assign.edit');
    Route::delete('vehicle-assign-delete/{id}', 'VehicleAssignSystemController@delete')->name('vehicle-assign.delete');
    Route::get('vehicle-assign-status/{id}', 'VehicleAssignSystemController@statusUpdate')->name('vehicle-assign.status');

    //third party vehicle
    Route::get('third-party-vehicle', 'ThirdPartyVehicleController@index')->name('third.vehicle.index');
    Route::post('third-party-vehicle-post/{id?}', 'ThirdPartyVehicleController@store')->name('third.vehicle.store');
    Route::get('third-party-vehicle-edit/{id}', 'ThirdPartyVehicleController@edit')->name('third.vehicle.edit');
    Route::delete('third-party-vehicle-delete/{id}', 'ThirdPartyVehicleController@delete')->name('third.vehicle.delete');
    Route::get('third-party-vehicle-status/{id}', 'ThirdPartyVehicleController@statusUpdate')->name('third.vehicle.status');

    //visitor tracking
    Route::get('visitor-system', 'VisitorController@index')->name('visitor.index');
    Route::post('visitor-post/{id?}', 'VisitorController@store')->name('visitor.store');
    Route::get('visitor-edit/{id}', 'VisitorController@edit')->name('visitor.edit');
    Route::delete('visitor-delete/{id}', 'VisitorController@delete')->name('visitor.delete');
    Route::get('visitor-show/{id}', 'VisitorController@show')->name('visitor.show');
    Route::get('qrScan', 'VisitorController@scan');
    Route::get('visitor-status/{id}', 'VisitorController@statusUpdate')->name('visitor.status.update');

    //employee_tracking
    Route::get('employee-system', 'EmployeeController@index')->name('employee.index');
    Route::post('employee-post/{id?}', 'EmployeeController@store')->name('employee.store');
    Route::get('employee-edit/{id}', 'EmployeeController@edit')->name('employee.edit');
    Route::delete('employee-delete/{id}', 'EmployeeController@delete')->name('employee.delete');
});
