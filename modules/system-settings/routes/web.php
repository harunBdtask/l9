<?php

use Illuminate\Support\Facades\Route;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\MenuController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\PartController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\RoleController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\SizeController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\TypeController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\AuditController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\BuyerController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\ColorController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\ModuleController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\SeasonController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\FactoryController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\MailGroupController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\UserAuditController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\DepartmentController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\PermissionController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\ArchiveFileController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\CompanyInfoController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\MailSettingController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\UserManagerController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\HiddenFieldsController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\ItemSubgroupController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\DyeingCompanyController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\MailSignatureController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\CareLabelTypesController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\SampleLineController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\SampleFloorController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\FinishingFloorController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\FinishingTableController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\GroupWiseFieldController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\ApplicationMenuController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\BondedWarehouseController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\CareInstructionController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\ReportSignatureController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\AssignPermissionController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\NotificationGroupController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\MailConfigurationsController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\HeadingLocalizationController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\ManagementDashboardController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\NotificationSettingController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\PageWiseViewPermissionController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\GarmentsProductionEntryController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\UserWiseBuyerPermissionController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\Inventory\ServiceCompanyController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\TrimsSensitivityVariableController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\Inventory\YarnStoreVariableSettingsController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\Commercial\CommercialMailingSettingsController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\Accounting\AccountingVariableSettingsController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\Inventory\FabricStoreVariableSettingsController;
use SkylarkSoft\GoRMG\SystemSettings\Controllers\Inventory\DyesChemicalStoreVariableSettingsController;

// use SkylarkSoft\GoRMG\SystemSettings\Controllers\BondedWarehouseController;

Route::group(['middleware' => ['web'], 'namespace' => 'SkylarkSoft\GoRMG\SystemSettings\Controllers'], function () {
    Route::get('/login', 'AuthenticateController@login')->name('login');
    Route::post('/post-login', 'AuthenticateController@postLogin');
    Route::get('/logout', 'AuthenticateController@logout');
    Route::get('/get-session', function () {
        return session()->all();
    });
    Route::get('/get-submodules/{module_id}', 'MenuController@getSubmodules');
});

Route::group(['middleware' => ['web', 'auth', 'menu-auth'], 'namespace' => 'SkylarkSoft\GoRMG\SystemSettings\Controllers'], function () {

    //Route for get floor and line api

    Route::get('/access-denied', 'MenuController@accessDenied');
    Route::get('/get-floors', 'FloorController@getFloors');
    Route::get('/get-lines/{id}', 'FloorController@getLines');
    Route::get('/get-lines-for-dropdown/{id}', 'FloorController@getLinesForDropdown');

    // Routes for buyer
    Route::group(['prefix' => 'buyers'], function () {
        Route::get('/', [BuyerController::class, 'index']);
        Route::get('/create', [BuyerController::class, 'create']);
        Route::post('', [BuyerController::class, 'store']);
        Route::get('/{id}/edit', [BuyerController::class, 'edit']);
        Route::put('/{id}', [BuyerController::class, 'update']);
        Route::get('/get-dyeing-process-price-by-id/{id}', [BuyerController::class, 'getDyeingProcessPriceById']);
        //    Route::delete('/{buyer}', [BuyerController::class, 'destroy']);
    });
    Route::get('search-buyer', [BuyerController::class, 'searchBuyer']);
    Route::get('/get-buyers/{factory_id}', [BuyerController::class, 'getBuyers']);

    // Routes for size
    Route::group(['prefix' => 'sizes'], function () {
        Route::get('/', [SizeController::class, 'index']);
        Route::post('', [SizeController::class, 'store']);
        Route::post('/save', [SizeController::class, 'save']);
        Route::put('/{id}', [SizeController::class, 'update']);
        Route::delete('/{id}', [SizeController::class, 'destroy']);
        Route::get('/{id}', [SizeController::class, 'show']);
    });
    Route::get('/sizes-search', [SizeController::class, 'searchSizes']);

    // Routes for color
    Route::group(['prefix' => 'colors'], function () {
        Route::get('/', [ColorController::class, 'index']);
        Route::get('/pdf', [ColorController::class, 'pdfDownload']);
        Route::get('/{name}', [ColorController::class, 'show']);
        Route::post('/', [ColorController::class, 'store']);
        Route::put('/{id}', [ColorController::class, 'update']);
        Route::delete('/{id}', [ColorController::class, 'destroy']);
    });
    // Routes for type
    Route::group(['prefix' => 'types'], function () {
        Route::get('/', [TypeController::class, 'index']);
        Route::get('/create', [TypeController::class, 'create']);
        Route::post('/', [TypeController::class, 'store']);
        Route::get('/{id}/edit', [TypeController::class, 'edit']);
        Route::put('/{id}', [TypeController::class, 'update']);
        Route::delete('/{id}', [TypeController::class, 'destroy']);
    });
    Route::get('search-types', [TypeController::class, 'searchTypes']);

    // Routes for part
    Route::group(['prefix' => 'parts'], function () {
        Route::get('/', [PartController::class, 'index']);
        Route::get('/create', [PartController::class, 'create']);
        Route::post('/', [PartController::class, 'store']);
        Route::get('/{id}/edit', [PartController::class, 'edit']);
        Route::put('/{id}', [PartController::class, 'update']);
        Route::delete('/{id}', [PartController::class, 'destroy']);
    });
    Route::get('search-parts', [PartController::class, 'searchParts']);

    // Routes for factory
    Route::group(['prefix' => 'factories'], function () {
        Route::get('/', [FactoryController::class, 'index']);
        Route::get('/create', [FactoryController::class, 'create']);
        Route::post('/', [FactoryController::class, 'store']);
        Route::get('/{id}/edit', [FactoryController::class, 'edit']);
        Route::put('/{id}', [FactoryController::class, 'update']);
        // Route::get('/{id}/delete', [FactoryController::class, 'destroy']);
        Route::get('/select2-search', [FactoryController::class, 'selectAndSearch']);
    });

    // Routes for company
    Route::group(['prefix' => 'companies'], function () {
        Route::get('/', [CompanyInfoController::class, 'index']);
        Route::post('/', [CompanyInfoController::class, 'store']);
        Route::get('/{id}/edit', [CompanyInfoController::class, 'edit']);
        Route::put('/{id}', [CompanyInfoController::class, 'store']);
        Route::delete('/{id}', [CompanyInfoController::class, 'destroy']);
    });

    // Routes for user
    Route::group(['prefix' => 'users'], function () {
        Route::get('/', [UserManagerController::class, 'index']);
        Route::get('/create', [UserManagerController::class, 'create']);
        Route::post('/', [UserManagerController::class, 'store']);
        Route::get('/{id}/edit', [UserManagerController::class, 'edit']);
        Route::put('/{id}', [UserManagerController::class, 'update']);
        Route::delete('/{id}', [UserManagerController::class, 'destroy']);
        Route::get('/select-search', [UserManagerController::class, 'selectSearch']);
    });
    Route::get('/get-users/{factory_id}', [UserManagerController::class, 'getUsers']);
    Route::get('search-users', [UserManagerController::class, 'searchUsers']);

    // Routes for department
    Route::group(['prefix' => 'departments'], function () {
        Route::get('/', [DepartmentController::class, 'index']);
        Route::post('/', [DepartmentController::class, 'store']);
        Route::get('/{id}/edit', [DepartmentController::class, 'edit']);
        Route::put('/{id}', [DepartmentController::class, 'update']);
        Route::delete('/{id}', [DepartmentController::class, 'destroy']);
    });
    Route::get('/department-search', [DepartmentController::class, 'search']);

    // Routes for role
    Route::group(['prefix' => 'roles'], function () {
        Route::get('/', [RoleController::class, 'index']);
        Route::post('/', [RoleController::class, 'store']);
        Route::get('/{id}/edit', [RoleController::class, 'edit']);
        Route::put('/{id}', [RoleController::class, 'update']);
        Route::delete('/{id}', [RoleController::class, 'destroy']);
    });

    // Routes for permission
    Route::group(['prefix' => 'permissions'], function () {
        Route::get('/', [PermissionController::class, 'index']);
        Route::post('/', [PermissionController::class, 'store']);
        Route::get('/{id}/edit', [PermissionController::class, 'edit']);
        Route::put('/{id}', [PermissionController::class, 'update']);
        Route::delete('/{id}', [PermissionController::class, 'destroy']);
    });
    Route::get('/get-all-permission', [PermissionController::class, 'getAllPermissionsByMenu']);

    // Routes for modules
    Route::group(['prefix' => 'modules-data'], function () {
        Route::get('/', [ModuleController::class, 'index']);
        Route::post('/', [ModuleController::class, 'store']);
        Route::get('/{id}/edit', [ModuleController::class, 'edit']);
        Route::put('/{id}', [ModuleController::class, 'update']);
        Route::delete('/{id}', [ModuleController::class, 'destroy']);
    });

    Route::group(['prefix' => 'application-menu-inactive'], function () {
        Route::get('/', [ApplicationMenuController::class, 'index']);
        Route::post('/', [ApplicationMenuController::class, 'store']);
        Route::get('/fetch-data', [ApplicationMenuController::class, 'fetchData']);
    });

    // Routes for menus
    Route::group(['prefix' => 'menus'], function () {
        Route::get('/', [MenuController::class, 'index']);
        Route::get('/create', [MenuController::class, 'create']);
        Route::post('/', [MenuController::class, 'store']);
        Route::get('/{id}/edit', [MenuController::class, 'edit']);
        Route::put('/{id}', [MenuController::class, 'update']);
        Route::delete('/{id}', [MenuController::class, 'destroy']);
    });
    Route::get('/search-menus', [MenuController::class, 'searchMenus']);
    Route::get('/get-menus/{module_id}', [MenuController::class, 'getMenus']);
    Route::get('/top-menu-search', [MenuController::class, 'searchJsonMenu']);
    Route::get('search-query-menu', [MenuController::class, 'getMenusByQuery']);

    // Routes for role wise permission
    Route::group(['prefix' => 'assign-permissions'], function () {
        Route::get('/', [AssignPermissionController::class, 'index']);
        Route::get('/create', [AssignPermissionController::class, 'create']);
        Route::post('/', [AssignPermissionController::class, 'store']);
        Route::get('/{id}/edit', [AssignPermissionController::class, 'edit']);
        Route::put('/{id}', [AssignPermissionController::class, 'update']);
        Route::delete('/{id}', [AssignPermissionController::class, 'destroy']);
        Route::get('/search', [AssignPermissionController::class, 'userPermissionSearch']);
        Route::get('/{id}', [AssignPermissionController::class, 'viewAssignedMenus']);
    });

    Route::get('assign-permission/module/menu/{id}', [AssignPermissionController::class, 'getMenus']);
    Route::post('/assign-module-wise-permission', [AssignPermissionController::class, 'assignModuleWisePermission']);
    Route::get('/get-menu-with-permission-form/{module_id}', [AssignPermissionController::class, 'getMenuWisePermissionForm']);

    Route::group(['prefix' => 'assign-module-wise-full-permission'], function () {
        Route::get('/', [AssignPermissionController::class, 'assignModuleWiseFullPermission']);
        Route::post('/', [AssignPermissionController::class, 'assignModuleWiseFullPermissionStore']);
        Route::post('/remove', [AssignPermissionController::class, 'removeModuleWiseFullPermissionStore']);
    });

    Route::get('production-date-change', 'DateChangeController@productionDateChange');
    Route::post('/production-date-change-post', 'DateChangeController@productionDateChangePost');

    Route::get('/account-system-settings', 'AccountSettingController@accountSettings');
    Route::put('/account-system-settings/{id}', 'AccountSettingController@UpdateAccountSettings');
    Route::get('/change-password', 'AccountSettingController@changePasswordForm');
    Route::post('/change-password-post', 'AccountSettingController@changePasswordPost');

    /* Item creation by siam */

    Route::get('items', 'ItemController@index');
    Route::post('items', 'ItemController@store');
    Route::get('items/{id}', 'ItemController@show');
    Route::put('items/{id}', 'ItemController@update');
    Route::delete('items/{id}/delete', 'ItemController@destroy');
    Route::get('items-search', 'ItemController@search');

    /* Section creation by muhid */

    Route::get('section', 'SectionCreationController@index');
    Route::get('get-section-list', 'SectionCreationController@index');
    Route::post('section/sections-store', 'SectionCreationController@itemsStore');
    Route::get('section/{id}/edit', 'SectionCreationController@edit');
    Route::put('section/{id}/update', 'SectionCreationController@update');
    Route::delete('section/{id}/delete', 'SectionCreationController@destroy');
    Route::get('section/search', 'SectionCreationController@search');

    /* Item Group Creation by siam */
    Route::get('item-group', 'ItemGroupController@index');
    Route::get('item-groups', 'ItemGroupController@itemGroups');
    Route::get('get-item-group-list', 'ItemGroupController@getItemGroupList');
    Route::get('item-group/add-item-group', 'ItemGroupController@addItemGroup');
    Route::get('item-group/{id}/edit', 'ItemGroupController@edit');
    Route::post('item-group/item-group-store', 'ItemGroupController@itemGroupStore');
    Route::put('item-group/{id}/update', 'ItemGroupController@update');
    Route::delete('item-group/{id}/delete', 'ItemGroupController@destroy');
    Route::get('/get-item-groups-on-factory/{factory_id}', 'ItemGroupController@getItemGroupsOnFactoryId');

    /* Item to Group Assign by siam*/
    Route::get('item-to-group', 'ItemGroupAssignController@index');
    Route::get('item-to-group/assign-item-to-group', 'ItemGroupAssignController@assignItemToGroup');
    Route::post('item-to-group/item-group-assign', 'ItemGroupAssignController@itemGroupAssign');
    Route::get('get-item-group-assign-list', 'ItemGroupAssignController@getItemGroupAssignList');
    Route::get('item-to-group/{id}/edit', 'ItemGroupAssignController@edit');
    Route::put('item-to-group/{id}/update', 'ItemGroupAssignController@update');
    Route::delete('item-to-group/{id}/delete', 'ItemGroupAssignController@delete');
    Route::get('item-to-group/search', 'ItemGroupAssignController@search');

    // Routes for suppliers
    Route::get('/suppliers', 'SupplierController@index');
    Route::get('/suppliers/create', 'SupplierController@create');
    Route::post('/suppliers', 'SupplierController@store');
    Route::get('/suppliers/{id}/edit', 'SupplierController@edit');
    Route::put('/suppliers/{id}', 'SupplierController@update');
    Route::delete('/suppliers/{supplier}', 'SupplierController@destroy');
    Route::get('/suppliers/search', 'SupplierController@search');

    // Routes for unit of measurements
    Route::get('/unit-of-measurements', 'UnitOfMeasurementsController@index');
    Route::post('/unit-of-measurements', 'UnitOfMeasurementsController@store');
    Route::get('/unit-of-measurements/{id}', 'UnitOfMeasurementsController@show');
    Route::put('/unit-of-measurements/{id}', 'UnitOfMeasurementsController@update');
    Route::delete('/unit-of-measurements/{id}', 'UnitOfMeasurementsController@destroy');
    Route::get('/unit-of-measurements-search', 'UnitOfMeasurementsController@search');

    // Routes for product department
    Route::get('/product-department', 'ProductDepartmentController@index');
    Route::get('/product-department/create', 'ProductDepartmentController@create');
    Route::post('/product-department', 'ProductDepartmentController@store');
    Route::post('/product-department/save', 'ProductDepartmentController@save');
    Route::get('/product-department/{id}/edit', 'ProductDepartmentController@edit');
    Route::put('/product-department/{id}', 'ProductDepartmentController@update');
    Route::delete('/product-department/{id}', 'ProductDepartmentController@destroy');
    Route::get('product-department/pdf', 'ProductDepartmentController@pdfDownload');
    Route::get('product-department/select-search', 'ProductDepartmentController@selectSearch');
    // Routes for Stores
    Route::get('/stores', 'StoreController@index');
    Route::post('/stores', 'StoreController@store');
    Route::get('/stores/{id}', 'StoreController@show');
    Route::put('/stores/{id}', 'StoreController@update');
    Route::delete('/stores/{id}', 'StoreController@destroy');
    Route::get('/stores-search', 'StoreController@search');
    Route::get('/fetch-factory-address', 'StoreController@fetchAddress');
    Route::get('/stores-search', 'StoreController@search');

    // Routes for teams
    Route::get('/teams', 'TeamController@index');
    Route::get('/teams/create', 'TeamController@create');
    Route::post('/teams', 'TeamController@store');
    Route::get('/teams/{name}/edit', 'TeamController@edit');
    Route::put('/teams/update/{name}', 'TeamController@update');
    Route::delete('/teams/{id}', 'TeamController@destroy');

    // Routes for Yarn Composition
    Route::get('/yarn-compositions', 'YarnCompositionController@index');
    Route::post('/yarn-compositions', 'YarnCompositionController@store');
    Route::get('/yarn-compositions/{id}', 'YarnCompositionController@show');
    Route::put('/yarn-compositions/{id}', 'YarnCompositionController@update');
    Route::delete('/yarn-compositions/{id}', 'YarnCompositionController@destroy');
    Route::get('yarn-compositions-search', 'YarnCompositionController@search');

    // Routes for Yarn Count
    Route::get('/yarn-counts', 'YarnCountController@index');
    Route::post('/yarn-counts', 'YarnCountController@store');
    Route::get('/yarn-counts/{id}', 'YarnCountController@show');
    Route::put('/yarn-counts/{id}', 'YarnCountController@update');
    Route::delete('/yarn-counts/{id}', 'YarnCountController@destroy');
    Route::get('/yarn-counts-search', 'YarnCountController@search');

    // Routes for Incoterm
    Route::get('/incoterms', 'IncotermController@index');
    Route::post('/incoterms', 'IncotermController@store');
    Route::get('/incoterms/{id}', 'IncotermController@show');
    Route::put('/incoterms/{id}', 'IncotermController@update');
    Route::delete('/incoterms/{id}', 'IncotermController@destroy');
    Route::get('/incoterms-search', 'IncotermController@search');

    /*Router for Garments Sample*/
    Route::get('garments-sample', 'GarmentsSampleController@index');
    Route::get('garments-sample/create', 'GarmentsSampleController@create');
    Route::get('garments-sample/{sample}/edit', 'GarmentsSampleController@edit');
    Route::post('garments-sample', 'GarmentsSampleController@store');
    Route::post('garments-sample/save', 'GarmentsSampleController@save');
    Route::put('garments-sample/{sample}', 'GarmentsSampleController@update');
    Route::get('buyers-for-factory/{factoryId}', 'GarmentsSampleController@getBuyers');

    //Routes for Currency
    Route::get('/currencies', 'CurrencyController@index');
    Route::post('/currencies', 'CurrencyController@store');
    Route::get('/currencies/{id}', 'CurrencyController@show');
    Route::put('/currencies/{id}', 'CurrencyController@update');
    Route::delete('/currencies/{id}', 'CurrencyController@destroy');
    Route::get('/currencies-search', 'CurrencyController@search');

    //Routes for product-category
    Route::get('/product-category', 'ProductCategoryController@index');
    Route::get('/product-categories', 'ProductCategoryController@productCategories');
    Route::get('/product-category/create', 'ProductCategoryController@create');
    Route::post('/product-category', 'ProductCategoryController@store');
    Route::get('/product-category/{id}/edit', 'ProductCategoryController@edit');
    Route::put('/product-category/{id}', 'ProductCategoryController@update');
    Route::delete('/product-category/{id}', 'ProductCategoryController@destroy');
    Route::get('/search-product-category', 'ProductCategoryController@search');

    // Routes for process
    Route::get('processes', 'ProcessController@index');
    Route::post('processes', 'ProcessController@store');
    Route::get('processes/{id}', 'ProcessController@show');
    Route::put('processes/{id}', 'ProcessController@update');
    Route::delete('processes/{id}', 'ProcessController@destroy');
    Route::get('processes-search', 'ProcessController@search');

    // Routes for buying process
    Route::get('buying-agent', 'BuyingAgentController@index');
    Route::post('buying-agent', 'BuyingAgentController@store');
    Route::get('buying-agent/{id}', 'BuyingAgentController@show');
    Route::put('buying-agent/{id}', 'BuyingAgentController@update');
    Route::delete('buying-agent/{id}', 'BuyingAgentController@delete');
    Route::get('buying-agent-search', 'BuyingAgentController@search');

    // Routes for buying agent mMerchant process
    Route::get('buying-agent-merchant', 'BuyingAgentMerchantController@index');
    Route::post('buying-agent-merchant', 'BuyingAgentMerchantController@store');
    Route::get('buying-agent-merchant/{id}', 'BuyingAgentMerchantController@show');
    Route::put('buying-agent-merchant/{id}', 'BuyingAgentMerchantController@update');
    Route::delete('buying-agent-merchant/{id}', 'BuyingAgentMerchantController@delete');
    Route::get('buying-agent-merchant-search', 'BuyingAgentMerchantController@search');


    // Advising Bank
    Route::get('advising-bank', 'AdvisingBankController@index');
    Route::post('advising-bank', 'AdvisingBankController@store');

    //ajaxChatController
    Route::get('/chat-user', 'ChatController@getUserMessage');
    Route::post('send-user-message', 'ChatController@sendMessage');
    Route::get('chat-delete', 'ChatController@chatDelete');
    Route::get('/get-user-unread-chat-count', 'ChatController@getUserUnreadChatCount');
    Route::get('/get-user-chat-channel-ids', 'ChatController@getUserChatChannelIds');

    //ajax social feed controller
    Route::post('send-post', 'PostController@store');
    Route::get('get-post', 'PostController@showStatus');
    Route::post('store-comment', 'PostController@storeComment');
    Route::get('show-comments', 'PostController@showComment');
    Route::get('count-likes', 'PostController@likeCount');
    Route::post('send-like', 'PostController@sendLike');
    Route::get('get-like-count', 'PostController@ajaxGetLikeCount');
    Route::get('like-check', 'PostController@userhasLike');
    Route::get('get-comment-count', 'PostController@showCommentCount');
    Route::get('get-delete-status', 'PostController@deletePost');
    Route::get('get_delete_comment', 'PostController@deleteComment');
    Route::get('get-comment-delete-status', 'PostController@getCommentDeleteStatus');

    //notification route
    Route::get('notification-read/{id}', 'NotificationController@unreadNotification');
    Route::get('all-notification-read', 'NotificationController@allread');
    Route::get('/get-notification-dropdown-view', 'NotificationController@getNotificationDropdownView');

    // New fabric composition
    Route::get('fabric-compositions', 'NewFabricCompositionController@index');
    Route::get('fabric-compositions-info', 'NewFabricCompositionController@fabricCompositions');
    Route::get('fabric-compositions/create', 'NewFabricCompositionController@create');
    Route::post('fabric-compositions', 'NewFabricCompositionController@store');
    Route::get('fabric-compositions/{id}/edit', 'NewFabricCompositionController@edit');
    Route::put('fabric-compositions/{id}', 'NewFabricCompositionController@update');
    Route::delete('fabric-compositions/{id}', 'NewFabricCompositionController@destroy');
    Route::delete('fabric-composition-details/{id}', 'NewFabricCompositionController@deleteDetails');

    // Old fabric composition
    Route::get('fabric-composition', 'FabricComposition@index');
    Route::get('fabric-composition/create', 'FabricComposition@create');
    Route::post('fabric-composition/store', 'FabricComposition@store');
    Route::get('fabric-composition/edit', 'FabricComposition@edit');
    Route::put('fabric-composition/{id}/update', 'FabricComposition@store');
    Route::get('search-fabric-composition', 'FabricComposition@searchFabricComposition');
    Route::get('fabric-composition/pdf', 'FabricComposition@pdfDownload');
    Route::delete('fabric-composition/delete', 'FabricComposition@delete');

    // color ranges
    Route::get('color-ranges', 'ColorRangeController@index');
    Route::get('color-ranges/create', 'ColorRangeController@create');
    Route::post('color-ranges', 'ColorRangeController@store');
    Route::get('color-ranges/{id}/edit', 'ColorRangeController@edit');
    Route::put('color-ranges/{id}', 'ColorRangeController@update');
    Route::delete('color-ranges/{id}', 'ColorRangeController@destroy');

    // color types
    Route::get('color-types', 'ColorTypeController@index');
    Route::post('color-types', 'ColorTypeController@store');
    Route::get('color-types/{id}', 'ColorTypeController@show');
    Route::delete('color-types/{id}', 'ColorTypeController@delete');
    Route::put('color-types/{id}', 'ColorTypeController@update');
    Route::get('color-types-search', 'ColorTypeController@search');

    # MAIL EMPLOYEE LIST
    Route::get('mail-employee-list', 'MailEmployeeListController@index');
    Route::post('mail-employee-list/store', 'MailEmployeeListController@store');
    Route::get('mail-employee-list/{id}/edit', 'MailEmployeeListController@edit');
    Route::put('mail-employee-list/{id}/update', 'MailEmployeeListController@store');
    Route::delete('mail-employee-list/delete/{id}', 'MailEmployeeListController@delete');

    // PRINT FACTORY TABLE
    Route::get('print-factory-tables', 'PrintFactoryTableController@index');
    Route::get('print-factory-tables/create', 'PrintFactoryTableController@create');
    Route::post('print-factory-tables', 'PrintFactoryTableController@store');
    Route::get('print-factory-tables/{table}', 'PrintFactoryTableController@edit');
    Route::put('print-factory-tables/{table}', 'PrintFactoryTableController@update');
    Route::delete('print-factory-tables/{table}/delete', 'PrintFactoryTableController@delete');

    // Other Factories
    Route::get('/others-factories', 'PrintFactoryController@index');
    Route::get('/others-factories/create', 'PrintFactoryController@create');
    Route::post('/others-factories', 'PrintFactoryController@store');
    Route::get('/others-factories/{id}/edit', 'PrintFactoryController@edit');
    Route::put('/others-factories/{id}', 'PrintFactoryController@update');
    Route::delete('/others-factories/{id}', 'PrintFactoryController@destroy');
    Route::get('/get-knitting-factories-for-select-search', 'PrintFactoryController@getKnittingFactoriesForSelectSearch');

    // Routes for lots
    Route::get('/lots', 'LotController@index');
    Route::get('/lots/create', 'LotController@create');
    Route::post('/lots', 'LotController@store');
    Route::get('/lots/{id}/edit', 'LotController@edit');
    Route::put('/lots/{id}', 'LotController@update');
    Route::delete('/lots/{id}', 'LotController@destroy');
    Route::get('search-lots', 'LotController@searchLots');

    // Routes for cutting floors
    Route::get('/cutting-floors', 'CuttingFloorController@index');
    Route::get('/cutting-floors/create', 'CuttingFloorController@create');
    Route::post('/cutting-floors', 'CuttingFloorController@store');
    Route::get('/cutting-floors/{id}/edit', 'CuttingFloorController@edit');
    Route::put('/cutting-floors/{id}', 'CuttingFloorController@update');
    Route::delete('/cutting-floors/{id}', 'CuttingFloorController@destroy');

    // Routes for cutting table
    Route::get('/cutting-tables', 'CuttingTableController@index');
    Route::get('/cutting-tables/create', 'CuttingTableController@create');
    Route::post('/cutting-tables', 'CuttingTableController@store');
    Route::get('/cutting-tables/{id}/edit', 'CuttingTableController@edit');
    Route::put('/cutting-tables/{id}', 'CuttingTableController@update');
    Route::delete('/cutting-tables/{id}', 'CuttingTableController@destroy');
    Route::get('search-cutting-tables', 'CuttingTableController@searchCuttingTables');
    Route::get('/get-cutting-tables/{cutting_floor_id}', 'CuttingTableController@getCuttingTables');

    // floors = sewing floors
    Route::get('/floors', 'FloorController@index');
    Route::get('/floors/create', 'FloorController@create');
    Route::post('/floors', 'FloorController@store');
    Route::get('/floors/{id}/edit', 'FloorController@edit');
    Route::put('/floors/{id}', 'FloorController@update');
    Route::delete('/floors/{id}', 'FloorController@destroy');

    // Routes for tasks
    Route::get('/tasks', 'TaskController@index');
    Route::get('/tasks/create', 'TaskController@create');
    Route::post('/tasks', 'TaskController@store');
    Route::get('/tasks/{id}/edit', 'TaskController@edit');
    Route::put('/tasks/{id}', 'TaskController@update');
    Route::delete('/tasks/{id}', 'TaskController@destroy');
    Route::get('/search-tasks', 'TaskController@searchTask');

    // lines = sewing lines
    Route::get('/lines', 'LineController@index');
    Route::get('/lines/create', 'LineController@create');
    Route::post('/lines', 'LineController@store');
    Route::get('/lines/{id}/edit', 'LineController@edit');
    Route::put('/lines/{id}', 'LineController@update');
    Route::delete('/lines/{id}', 'LineController@destroy');
    Route::get('/get-lines/{floor_id}', 'LineController@getLines');
    Route::get('search-lines', 'LineController@searchLines');

    // Routes for machine types
    Route::get('/machine-types', 'MachineTypeController@index');
    Route::get('/machine-types/create', 'MachineTypeController@create');
    Route::post('/machine-types', 'MachineTypeController@store');
    Route::get('/machine-types/{id}/edit', 'MachineTypeController@edit');
    Route::put('/machine-types/{id}', 'MachineTypeController@update');
    Route::delete('/machine-types/{id}', 'MachineTypeController@destroy');
    Route::get('/search-machine-types', 'MachineTypeController@searchMachineType');

    //Routes for operator skills
    Route::get('/operator-skill', 'OperatorSkillsController@index');
    Route::get('/operator-skill/create', 'OperatorSkillsController@create');
    Route::post('/operator-skill', 'OperatorSkillsController@store');
    Route::get('/operator-skill/{id}/edit', 'OperatorSkillsController@edit');
    Route::put('/operator-skill/{id}', 'OperatorSkillsController@update');
    Route::delete('/operator-skill/{id}', 'OperatorSkillsController@destroy');
    Route::get('/search-operator-skill', 'OperatorSkillsController@searchOperatorSkills');

    // Routes for guide or folder
    Route::get('/guide-or-folders', 'GuideOrFolderController@index');
    Route::get('/guide-or-folders/create', 'GuideOrFolderController@create');
    Route::post('/guide-or-folders', 'GuideOrFolderController@store');
    Route::get('/guide-or-folders/{id}/edit', 'GuideOrFolderController@edit');
    Route::put('/guide-or-folders/{id}', 'GuideOrFolderController@update');
    Route::delete('/guide-or-folders/{id}', 'GuideOrFolderController@destroy');
    Route::get('/search-guide-or-folders', 'GuideOrFolderController@searchGuideOrFolder');

    // Routes for user cutting plan permission
    Route::get('/user-cutting-floor-plan-permissions', 'UserCuttingFloorPlanPermissionController@index');
    Route::post('/user-cutting-floor-plan-permissions', 'UserCuttingFloorPlanPermissionController@store');
    Route::get('/update-cutting-plan-board-lock-info', 'UserCuttingFloorPlanPermissionController@updateLockInfo');

    /* Yarn Type */
    Route::get('/yarn-types', 'YarnTypeController@index');
    Route::get('/yarn-types/create', 'YarnTypeController@create');
    Route::post('/yarn-types', 'YarnTypeController@saveYarnType');
    Route::get('/yarn-types/{id}/edit', 'YarnTypeController@editYarnType');
    Route::put('/yarn-types/{id}', 'YarnTypeController@saveYarnType');
    Route::delete('/yarn-types/{id}', 'YarnTypeController@deleteYarnType');
    Route::get('/yarn-types/search', 'YarnTypeController@search');

    /* Party Type */
    Route::get('/party-types', 'PartyTypeController@index');
    Route::post('/party-types', 'PartyTypeController@store');
    Route::get('/party-types/{id}', 'PartyTypeController@show');
    Route::put('/party-types/{id}', 'PartyTypeController@update');
    Route::delete('/party-types/{id}', 'PartyTypeController@destroy');
    Route::get('/party-types-search', 'PartyTypeController@search');

    /* Parties */
    Route::get('/parties', 'PartyController@index');
    Route::get('/parties/create', 'PartyController@create');
    Route::post('/parties', 'PartyController@store');
    Route::get('/parties/{id}/edit', 'PartyController@edit');
    Route::put('/parties/{id}', 'PartyController@store');
    Route::delete('/parties/{id}', 'PartyController@deleteParty');

    /* KnitFabricGradeSetting */
    Route::get('/knit_fabric_grade_settings', 'KnitFabricGradeSettingController@index');
    Route::get('/knit_fabric_grade_settings', 'KnitFabricGradeSettingController@index');
    Route::get('/knit_fabric_grade_settings/create', 'KnitFabricGradeSettingController@create');
    Route::post('/knit_fabric_grade_settings', 'KnitFabricGradeSettingController@store');
    Route::get('/knit_fabric_grade_settings/{id}/edit', 'KnitFabricGradeSettingController@edit');
    Route::put('/knit_fabric_grade_settings/{id}', 'KnitFabricGradeSettingController@store');
    Route::delete('/knit_fabric_grade_settings/{id}', 'KnitFabricGradeSettingController@destroy');


    /* KnitFabricFaultSetting */
    Route::get('/knit_fabric_fault_settings', 'KnitFabricFaultSettingController@index');
    Route::get('/knit_fabric_fault_settings', 'KnitFabricFaultSettingController@index');
    Route::get('/knit_fabric_fault_settings/create', 'KnitFabricFaultSettingController@create');
    Route::post('/knit_fabric_fault_settings', 'KnitFabricFaultSettingController@store');
    Route::get('/knit_fabric_fault_settings/{id}/edit', 'KnitFabricFaultSettingController@edit');
    Route::put('/knit_fabric_fault_settings/{id}', 'KnitFabricFaultSettingController@store');
    Route::delete('/knit_fabric_fault_settings/{id}', 'KnitFabricFaultSettingController@destroy');

    /* Shifts */
    Route::get('/shifts', 'ShiftController@index');
    Route::get('/shifts/create', 'ShiftController@create');
    Route::post('/shifts', 'ShiftController@store');
    Route::get('/shifts/{id}/edit', 'ShiftController@edit');
    Route::put('/shifts/{id}', 'ShiftController@store');
    Route::delete('/shifts/{id}', 'ShiftController@destroy');

    /* Designation */
    Route::get('/designations', 'DesignationController@index');
    Route::get('/designations/create', 'DesignationController@create');
    Route::post('/designations', 'DesignationController@store');
    Route::get('/designations/{id}/edit', 'DesignationController@edit');
    Route::put('/designations/{id}', 'DesignationController@store');
    Route::delete('/designations/{id}', 'DesignationController@deleteDesignation');

    /* Operators */
    Route::get('/operators', 'OperatorController@index');
    Route::get('/operators/create', 'OperatorController@create');
    Route::post('/operators', 'OperatorController@store');
    Route::get('/operators/{id}/edit', 'OperatorController@edit');
    Route::put('/operators/{id}', 'OperatorController@store');
    Route::delete('/operators/{id}', 'OperatorController@destroy');

    /* Knitting Floor */
    Route::get('/knitting-floor', 'KnittingFloorController@index');
    Route::get('/knitting-floor/create', 'KnittingFloorController@create');
    Route::post('/knitting-floor', 'KnittingFloorController@store');
    Route::get('/knitting-floor/{id}/edit', 'KnittingFloorController@edit');
    Route::put('/knitting-floor/{id}', 'KnittingFloorController@store');
    Route::delete('/knitting-floor/{id}', 'KnittingFloorController@destroy');

    /* Brands */
    Route::get('/brands', 'BrandController@index');
    Route::get('/brands/create', 'BrandController@create');
    Route::post('/brands', 'BrandController@store');
    Route::get('/brands/{id}/edit', 'BrandController@edit');
    Route::put('/brands/{id}', 'BrandController@store');
    Route::delete('/brands/{id}', 'BrandController@deleteBrand');

    /* Machine Type */
    Route::get('/knit-machine-types', 'KnitMachineTypeController@index');
    Route::get('/knit-machine-types/create', 'KnitMachineTypeController@create');
    Route::post('/knit-machine-types', 'KnitMachineTypeController@store');
    Route::get('/knit-machine-types/{id}/edit', 'KnitMachineTypeController@edit');
    Route::put('/knit-machine-types/{id}', 'KnitMachineTypeController@store');
    Route::delete('/knit-machine-types/{id}', 'KnitMachineTypeController@destroy');

    /* Machines */
    Route::get('/machines', 'MachineController@index');
    Route::get('/machines/create', 'MachineController@create');
    Route::post('/machines', 'MachineController@store');
    Route::get('/machines/{id}/edit', 'MachineController@edit');
    Route::put('/machines/{id}', 'MachineController@update');
    Route::delete('machines/{id}', 'MachineController@delete');
    Route::get('/get-machine-info-ajax/{id}', 'MachineController@getMachineInfoAjax');
    Route::get('/get-machine-rpm/{id}', 'MachineController@getMachineRpm');
    Route::get('/get-machines-for-knitcard/{factory_id}/{machine_dia?}', 'MachineController@getMachinesForKnitcard');
    Route::get('/get-knitting-machines-for-single-factory/{factory_id}', 'MachineController@getKnittingMachinesForSingleFactory');

    /* Fabric Types */
    Route::get('/fabric-types', 'FabricTypeController@index');
    Route::get('/fabric-types/create', 'FabricTypeController@create');
    Route::post('/fabric-types', 'FabricTypeController@store');
    Route::get('/fabric-types/{id}/edit', 'FabricTypeController@edit');
    Route::put('/fabric-types/{id}', 'FabricTypeController@store');
    Route::delete('/fabric-types/{id}', 'FabricTypeController@destroy');

    // Season
    Route::get('/seasons', [SeasonController::class, 'index']);
    Route::post('/save-season-api', [SeasonController::class, 'saveSeason']);
    Route::post('/seasons', [SeasonController::class, 'store']);
    Route::get('/seasons/edit/{factoryId}/{buyerId}', [SeasonController::class, 'edit']);
    Route::put('/seasons/update/{factoryId}/{buyerId}', [SeasonController::class, 'update']);

    // Item Creation
    Route::get('item-creations', 'ItemCreationController@index');
    Route::get('item-creations/create', 'ItemCreationController@create');
    Route::get('item-creations/get-groups/{itemId}', 'ItemCreationController@getItemWiseGroup');
    Route::post('item-creations', 'ItemCreationController@store');
    Route::get('item-creations/{id}/edit', 'ItemCreationController@edit');
    Route::delete('item-creations/{id}', 'ItemCreationController@delete');
    Route::put('item-creations/{id}', 'ItemCreationController@update');

    // Product Type
    Route::get('/product-types', 'ProductTypeController@index');
    Route::get('/product-types/create', 'ProductTypeController@create');
    Route::post('/product-types', 'ProductTypeController@store');
    Route::get('/product-types/{id}/edit', 'ProductTypeController@edit');
    Route::put('/product-types/{id}', 'ProductTypeController@update');
    Route::delete('/product-types/{id}', 'ProductTypeController@destroy');
    Route::get('/product-types/search', 'ProductTypeController@search');

    // Fabric Nature
    Route::get('/fabric-natures', 'FabricNatureController@index');
    Route::get('/fabric-natures/create', 'FabricNatureController@create');
    Route::post('/fabric-natures', 'FabricNatureController@store');
    Route::get('/fabric-natures/{id}/edit', 'FabricNatureController@edit');
    Route::put('/fabric-natures/{id}', 'FabricNatureController@update');
    Route::delete('/fabric-natures/{id}', 'FabricNatureController@destroy');

    // Garments Item
    Route::get('garments-items', 'GarmentsItemController@index');
    Route::get('garments-items/create', 'GarmentsItemController@create');
    Route::post('garments-items', 'GarmentsItemController@store');
    Route::post('garments-items/save', 'GarmentsItemController@save');
    Route::get('garments-items/{id}/edit', 'GarmentsItemController@edit');
    Route::put('garments-items/{id}', 'GarmentsItemController@update');
    Route::delete('garments-items/{id}', 'GarmentsItemController@destroy');
    Route::get('garments-items/search', 'GarmentsItemController@search');

    // Garments Item Group
    Route::resource('garments-item-group', 'GarmentsItemGroupController');

    // Composition Type
    Route::get('/composition-types', 'CompositionTypeController@index');
    Route::get('/composition-types/create', 'CompositionTypeController@create');
    Route::post('/composition-types', 'CompositionTypeController@store');
    Route::get('/composition-types/{id}/edit', 'CompositionTypeController@edit');
    Route::put('/composition-types/general-store{id}', 'CompositionTypeController@update');
    Route::delete('/composition-types/{id}', 'CompositionTypeController@destroy');

    // trims-accessories
    Route::get('/trims-accessories-item', 'TrimsAccessoriesItemsController@index');
    Route::post('/trims-accessories-item', 'TrimsAccessoriesItemsController@store');
    Route::get('/trims-accessories-item/{id}', 'TrimsAccessoriesItemsController@show');
    Route::put('/trims-accessories-item/{id}', 'TrimsAccessoriesItemsController@update');
    Route::delete('/trims-accessories-item/{id}', 'TrimsAccessoriesItemsController@destroy');
    Route::get('/trims-accessories-item-search', 'TrimsAccessoriesItemsController@search');

    // Routes for Body Parts
    Route::get('/body-parts', 'BodyPartController@index');
    Route::post('/body-parts', 'BodyPartController@store');
    Route::get('/body-parts/{id}', 'BodyPartController@show');
    Route::put('/body-parts/{id}', 'BodyPartController@update');
    Route::delete('/body-parts/{id}', 'BodyPartController@destroy');
    Route::get('/body-parts-search', 'BodyPartController@search');

    // Routs For FinancialParameterSetup
    Route::get('financial-parameter-setups', 'FinancialParameterSetupController@index');
    Route::get('financial-parameter-setups/create', 'FinancialParameterSetupController@create');
    Route::post('financial-parameter-setups', 'FinancialParameterSetupController@store');
    Route::get('financial-parameter-setups/working-day-count', 'FinancialParameterSetupController@workingDayCount');
    Route::get('financial-parameter-setups/{id}/edit', 'FinancialParameterSetupController@edit');
    Route::put('financial-parameter-setups/{id}', 'FinancialParameterSetupController@update');
    Route::delete('financial-parameter-setups/{id}', 'FinancialParameterSetupController@destroy');
    Route::get('search-financial-parameter-setups', 'FinancialParameterSetupController@search');

    // Route For Costing Template
    Route::get('/costing-templates', 'CostingTemplateController@index');

    // Routes for embellishment item
    Route::get('/embellishment-items', 'EmbellishmentItemController@index');
    Route::get('/embellishment-items/{id}/edit', 'EmbellishmentItemController@edit');
    Route::post('/embellishment-items', 'EmbellishmentItemController@store');
    Route::put('/embellishment-items/{id}', 'EmbellishmentItemController@update');
    Route::delete('/embellishment-items/{id}', 'EmbellishmentItemController@destroy');

    //Route for Commercial Cost Method In PQ
    Route::get('/commercial-cost-method-in-pq', 'CommercialCostMethodInPqController@index');
    Route::post('/commercial-cost-method', 'CommercialCostMethodInPqController@store');
    Route::get('/commercial-cost-method/{id?}', 'CommercialCostMethodInPqController@show');
    Route::put('/commercial-cost-method/{id?}', 'CommercialCostMethodInPqController@update');
    Route::delete('/commercial-cost-method/{id?}', 'CommercialCostMethodInPqController@destroy');
    Route::get('/commercial-cost-method-search', 'CommercialCostMethodInPqController@search');

    // MERCHANDISING SYSTEM SETTINGS
    Route::post("/merchandising_variable_settings/multiple-buyer", "MerchandisingVariableSettingsController@multipleBuyerWiseSave");
    Route::get("/merchandising_variable_settings/{factoryId}/{buyerId}", "MerchandisingVariableSettingsController@loadPreviousData");
    Route::resource("/merchandising_variable_settings", "MerchandisingVariableSettingsController");
    Route::get("/factories_api", "FactoryApiController");
    Route::get("/variables_api", "VariablesApiController");
    Route::get("/get_variable_settings_data", "VariableSettingsApiController@variableData");
    Route::get("/get_variable_settings_data/{id}/buyers", "VariableSettingsApiController@getBuyers");
    Route::get("/logs", "LogController@index");
    Route::delete("/logs/all-delete", "LogController@destroy");

    Route::get('/page-wise-view-permission', [PageWiseViewPermissionController::class, 'index']);
    Route::get('/page-wise-view-permission/{id}/edit', [PageWiseViewPermissionController::class, 'edit']);
    Route::post('/page-wise-view-permission', [PageWiseViewPermissionController::class, 'store']);
    Route::get('/page-wise-view-permission/get-views', [PageWiseViewPermissionController::class, 'getViews']);
    Route::delete('/page-wise-view-permission/{id}', [PageWiseViewPermissionController::class, 'destroy']);
    Route::put('/page-wise-view-permission/update/{id}', [PageWiseViewPermissionController::class, 'update']);
    Route::delete('/page-wise-view-permission/delete-page/{userId}/{id}', [PageWiseViewPermissionController::class, 'deletePage']);
    Route::delete('/page-wise-view-permission/delete-view/{userId}/{id}', [PageWiseViewPermissionController::class, 'deleteView']);

    Route::get('/user-wise-buyer-permission', [UserWiseBuyerPermissionController::class, 'create']);
    Route::post('/user-wise-buyer-permission', [UserWiseBuyerPermissionController::class, 'store']);
    Route::get('/user-wise-buyer-permission-list', [UserWiseBuyerPermissionController::class, 'index']);
    Route::delete('/user-wise-buyer-permission-list/{id}', [UserWiseBuyerPermissionController::class, 'destroy']);

    //   Planning System production_variable_settings
    Route::get("/knitting-production-variable", "ProductionVariableSettingsController@index");
    Route::post("/knitting-production-variable", "ProductionVariableSettingsController@store");
    Route::put("/knitting-production-variable/{id}", "ProductionVariableSettingsController@store");
    Route::get("/get-knitting-production-variable/{id?}", "ProductionVariableSettingsController@getProductionVariableSetting");

    //Route for fabric construction entry
    Route::get('/fabric-construction-entry', 'FabricConstructionEntryController@index');
    Route::post('/fabric-construction-entry', 'FabricConstructionEntryController@store');
    Route::get('/fabric-construction-entry/{id}', 'FabricConstructionEntryController@show');
    Route::put('/fabric-construction-entry/{id}', 'FabricConstructionEntryController@update');
    Route::delete('/fabric-construction-entry/{id}', 'FabricConstructionEntryController@destroy');
    Route::get('fabric-construction-entry-search', 'FabricConstructionEntryController@search');
    Route::get('fabric-constructions', 'FabricConstructionEntryController@fabricConstructions');

    // Po File Issue Settings
    Route::resource("/po_file_issue_settings", "PoFileIssueController");
    Route::resource('/short-bookings-settings', 'ShortBookingsSettingsController')->only([
        'index', 'update',
    ]);


    Route::get('/localizations', [HeadingLocalizationController::class, 'index']);
    Route::get('/get-localizations', [HeadingLocalizationController::class, 'show']);
    Route::post('/localizations', [HeadingLocalizationController::class, 'store']);
    Route::post('/get-localization/{heading}', [HeadingLocalizationController::class, 'getLocalization']);
    // Lian Bank

    Route::resource('/lien-banks', 'LienBankController');
    Route::resource('commercial/bonded-warehouse', BondedWarehouseController::class);
    Route::get('/commercial/fetch-bonded-warehouse', [BondedWarehouseController::class, 'fetch_warehouses']);

    Route::group(['prefix' => 'garments-production-entry'], function () {
        Route::get('/', [GarmentsProductionEntryController::class, 'index']);
        Route::post('/', [GarmentsProductionEntryController::class, 'store']);
        Route::get('/{id}/fetch', [GarmentsProductionEntryController::class, 'fetch']);
        Route::put('/{id}', [GarmentsProductionEntryController::class, 'update']);
        Route::delete('/{id}', [GarmentsProductionEntryController::class, 'destroy']);
    });

    Route::group(['prefix' => 'finance-menu'], function () {
        Route::get('/', 'FinanceMenuController@index');
        Route::post('/', 'FinanceMenuController@store');
        Route::get('/{menu}', 'FinanceMenuController@edit');
    });

    Route::group(['prefix' => 'audits'], function () {
        Route::get('/', [UserAuditController::class, 'index']);
        Route::get('/excel', [UserAuditController::class, 'excel']);
    });
    // Terms and condition
    Route::get('/terms-conditions', 'TermsAndConditionController@index');
    Route::post('/terms-conditions', 'TermsAndConditionController@store');
    Route::get('/terms-conditions/{id}/edit', 'TermsAndConditionController@edit');
    Route::put('/terms-conditions/{terms}', 'TermsAndConditionController@update');
    Route::delete('/terms-conditions/{terms}', 'TermsAndConditionController@destroy');

    //Route For Report Signature
    Route::group(['prefix' => 'report-signature'], function () {
        Route::get("/", [ReportSignatureController::class, 'index']);
        Route::post("/", [ReportSignatureController::class, 'store']);
        Route::get("/create", [ReportSignatureController::class, 'create']);
        Route::delete("/details/{id}", [ReportSignatureController::class, 'deleteDetails']);
        Route::get("/{id}", [ReportSignatureController::class, 'show']);
        Route::put("/{id}", [ReportSignatureController::class, 'update']);
        Route::delete("/{id}", [ReportSignatureController::class, 'destroy']);
        Route::get("{any?}", [ReportSignatureController::class, 'create'])->where('any', '.*');
    });

    Route::get("/fetch-page-names", [ReportSignatureController::class, 'fetchPages']);
    Route::get("/fetch-templates", [ReportSignatureController::class, 'fetchTemplates']);
    Route::get("/fetch-user-signatures", [ReportSignatureController::class, 'fetchUserSignatures']);

    Route::group(['prefix' => 'finishing-floor'], function () {
        Route::get('/', [FinishingFloorController::class, 'index']);
        Route::post('/', [FinishingFloorController::class, 'store']);
        Route::put('/{finishingFloor}', [FinishingFloorController::class, 'update']);
        Route::get('/{finishingFloor}', [FinishingFloorController::class, 'edit']);
        Route::delete('/{finishingFloor}', [FinishingFloorController::class, 'destroy']);
    });

    Route::group(['prefix' => 'finishing-table'], function () {
        Route::get('/', [FinishingTableController::class, 'index']);
        Route::post('/', [FinishingTableController::class, 'store']);
        Route::put('/{finishingTable}', [FinishingTableController::class, 'update']);
        Route::get('/{finishingTable}', [FinishingTableController::class, 'edit']);
        Route::delete('/{finishingTable}', [FinishingTableController::class, 'destroy']);
    });

    Route::group(['prefix' => 'care-instructions'], function () {
        Route::get('/', [CareInstructionController::class, 'index']);
        Route::post('/', [CareInstructionController::class, 'store']);
        Route::put('/{careInstruction}', [CareInstructionController::class, 'update']);
        Route::get('/{careInstruction}', [CareInstructionController::class, 'edit']);
        Route::delete('/{careInstruction}', [CareInstructionController::class, 'destroy']);
    });

    Route::get('/fetch-factory-finishing-floors/{id}', [FinishingFloorController::class, 'getFinishingFactoryFloor']);
    Route::get('/fetch-finishing-floors', [FinishingFloorController::class, 'getFinishingFloor']);
    Route::get('/fetch-finishing-tables/{id}', [FinishingFloorController::class, 'getFinishingFloorTable']);

    // Fabric store variable settings
    Route::get('/fabric-store-variable-settings', [FabricStoreVariableSettingsController::class, 'index']);
    Route::post('/fabric-store-variable-settings', [FabricStoreVariableSettingsController::class, 'store']);
    Route::put('/fabric-store-variable-settings/{fabricStoreVariableSetting}', [FabricStoreVariableSettingsController::class, 'update']);

    // Yarn store variable settings
    Route::get('/yarn-store-variable-settings', [YarnStoreVariableSettingsController::class, 'index']);
    Route::post('/yarn-store-variable-settings', [YarnStoreVariableSettingsController::class, 'store']);
    Route::put('/yarn-store-variable-settings/{yarnStoreVariableSetting}', [YarnStoreVariableSettingsController::class, 'update']);
    Route::get('/yarn-store-approval-maintain-status', [YarnStoreVariableSettingsController::class, 'getApprovalMaintainStatus']);

    // dyes chemical store variable settings
    Route::get('/dyes-chemical-store-variable-settings', [DyesChemicalStoreVariableSettingsController::class, 'index']);
    Route::post('/dyes-chemical-store-variable-settings', [DyesChemicalStoreVariableSettingsController::class, 'store']);
    Route::put('/dyes-chemical-store-variable-settings/{dyesChemicalStoreVariableSetting}', [DyesChemicalStoreVariableSettingsController::class, 'update']);
    Route::get('/dyes-chemical-store-approval-maintain-status', [DyesChemicalStoreVariableSettingsController::class, 'getApprovalMaintainStatus']);

    Route::get('/accounting-variable-settings', [AccountingVariableSettingsController::class, 'index']);
    Route::post('/accounting-variable-settings', [AccountingVariableSettingsController::class, 'store']);

    Route::group(['prefix' => 'archive-file'], function () {
        Route::get('/', [ArchiveFileController::class, 'index']);
        Route::post('/', [ArchiveFileController::class, 'store']);
        Route::put('/', [ArchiveFileController::class, 'update']);
        Route::get('/create', [ArchiveFileController::class, 'create']);
        Route::get('/{id}/edit', [ArchiveFileController::class, 'edit']);
        Route::delete('/{id}/delete', [ArchiveFileController::class, 'deleteArchiveFile']);
        Route::get('/{id}/buyer-wise-style', [ArchiveFileController::class, 'buyerWiseStyle']);
    });


    Route::get('/item-subgroups', [ItemSubgroupController::class, 'index']);
    Route::post('/item-subgroups', [ItemSubgroupController::class, 'store']);
    Route::get('/item-subgroups/{itemSubgroup}/edit', [ItemSubgroupController::class, 'edit']);
    Route::put('/item-subgroups/{itemSubgroup}', [ItemSubgroupController::class, 'update']);
    Route::delete('/item-subgroups/{itemSubgroup}', [ItemSubgroupController::class, 'destroy']);

    Route::view('hide-fields-variable', 'system-settings::variables.hide-fields');
    Route::get('fetch-pages-and-fields', [HiddenFieldsController::class, 'pages']);
    Route::get('hidden-fields', [HiddenFieldsController::class, 'show']);
    Route::post('hidden-fields', [HiddenFieldsController::class, 'store']);

    // management dashboard
    Route::get('management-dashboard', [ManagementDashboardController::class, 'index']);

    //log book
    Route::get('/audit-log-book', [AuditController::class, 'view'])->name('audit-log-book');
    Route::get('/audit-value', [AuditController::class, 'auditDetails']);

    //Group Wise Fields
    Route::group(['prefix' => 'group-wise-fields'], function () {
        Route::get('', [GroupWiseFieldController::class, 'index']);
        Route::post('', [GroupWiseFieldController::class, 'store']);
        Route::get('/{groupWiseField}', [GroupWiseFieldController::class, 'edit']);
        Route::put('/{groupWiseField}', [GroupWiseFieldController::class, 'store']);
        Route::delete('/{groupWiseField}', [GroupWiseFieldController::class, 'destroy']);
    });

    Route::group(['prefix' => 'mail-configuration'], function () {
        Route::get('', [MailConfigurationsController::class, 'index']);
        Route::post('', [MailConfigurationsController::class, 'store']);
    });
    Route::group(['prefix' => 'mail-group'], function () {
        Route::get('', [MailGroupController::class, 'index']);
        Route::post('', [MailGroupController::class, 'store']);
        Route::post('{mailGroup}', [MailGroupController::class, 'store']);
        Route::get('{mailGroup}', [MailGroupController::class, 'edit']);
        Route::delete('{mailGroup}', [MailGroupController::class, 'destroy']);
    });
    Route::group(['prefix' => 'mail-setting'], function () {
        Route::get('', [MailSettingController::class, 'index']);
        Route::post('', [MailSettingController::class, 'store']);
        Route::post('{mailSetting}', [MailSettingController::class, 'store']);
        Route::get('{mailSetting}', [MailSettingController::class, 'edit']);
        Route::delete('{mailSetting}', [MailSettingController::class, 'destroy']);
    });
    Route::group(['prefix' => 'mail-signature'], function () {
        Route::get('', [MailSignatureController::class, 'index']);
        Route::post('', [MailSignatureController::class, 'store']);
        Route::post('{mailSignature}', [MailSignatureController::class, 'store']);
        Route::get('{mailSignature}', [MailSignatureController::class, 'edit']);
        Route::delete('{mailSignature}', [MailSignatureController::class, 'destroy']);
    });

    Route::group(['prefix' => 'notification-group'], function () {
        Route::get('', [NotificationGroupController::class, 'index']);
        Route::post('', [NotificationGroupController::class, 'store']);
        Route::post('{notificationGroup}', [NotificationGroupController::class, 'store']);
        Route::get('{notificationGroup}', [NotificationGroupController::class, 'edit']);
        Route::delete('{notificationGroup}', [NotificationGroupController::class, 'destroy']);
    });
    Route::group(['prefix' => 'notification-setting'], function () {
        Route::get('', [NotificationSettingController::class, 'index']);
        Route::post('', [NotificationSettingController::class, 'store']);
        Route::post('{notificationSetting}', [NotificationSettingController::class, 'store']);
        Route::get('{notificationSetting}', [NotificationSettingController::class, 'edit']);
        Route::delete('{notificationSetting}', [NotificationSettingController::class, 'destroy']);
    });

    Route::group(['prefix' => 'dyeing-company'], function () {
        Route::get('/', [DyeingCompanyController::class, 'index']);
        Route::post('/', [DyeingCompanyController::class, 'store']);
        Route::get('/{id}', [DyeingCompanyController::class, 'edit']);
        Route::put('/{id}', [DyeingCompanyController::class, 'update']);
        Route::delete('/{id}', [DyeingCompanyController::class, 'destroy']);
    });
    Route::get('/dyeing-company-search', [DyeingCompanyController::class, 'search']);

    Route::group(['prefix' => 'commercial-mailing-variable-settings'], function () {
        Route::get('/', [CommercialMailingSettingsController::class, 'index']);
        Route::post('/', [CommercialMailingSettingsController::class, 'store']);
    });

    Route::group(['prefix' => '/care-label-types'], function () {
        Route::get('', [CareLabelTypesController::class, 'index']);
        Route::post('', [CareLabelTypesController::class, 'store']);
        Route::get('/{careLabelType}/edit', [CareLabelTypesController::class, 'edit']);
        Route::put('/{careLabelType}', [CareLabelTypesController::class, 'update']);
        Route::delete('', [CareLabelTypesController::class, 'destroy']);
    });

    Route::group(['prefix' => 'sample-line'], function () {
        Route::get('/all', [SampleLineController::class, 'sampleLines']);
        Route::get('/', [SampleLineController::class, 'index']);
        Route::post('/', [SampleLineController::class, 'store']);
        Route::put('/{sampleLine}', [SampleLineController::class, 'update']);
        Route::get('/{sampleLine}', [SampleLineController::class, 'edit']);
        Route::delete('/{sampleLine}', [SampleLineController::class, 'destroy']);
    });

    Route::group(['prefix' => 'sample-floor'], function () {
        Route::get('/', [SampleFloorController::class, 'index']);
        Route::post('/', [SampleFloorController::class, 'store']);
        Route::put('/{sampleFloor}', [SampleFloorController::class, 'update']);
        Route::get('/{sampleFloor}', [SampleFloorController::class, 'edit']);
        Route::delete('/{sampleFloor}', [SampleFloorController::class, 'destroy']);
    });

    Route::group(['prefix' => 'trims-sensitivity-variables'], function () {
        Route::get('', [TrimsSensitivityVariableController::class, 'index']);
        Route::post('', [TrimsSensitivityVariableController::class, 'store']);
        Route::get('{trimsSensitivityVariable}/edit', [TrimsSensitivityVariableController::class, 'edit']);
        Route::put('{trimsSensitivityVariable}', [TrimsSensitivityVariableController::class, 'update']);
        Route::delete('{trimsSensitivityVariable}', [TrimsSensitivityVariableController::class, 'destroy']);
    });
});

Route::resource('service-company', ServiceCompanyController::class, [
    'names' => [
        'index' => 'service-company.index',
        'store' => 'service-company.store',
        'update' => 'service-company.update',
        'edit' => 'service-company.edit',
        'destroy' => 'service-company.destroy',
    ],
])->middleware(['web', 'auth']);
