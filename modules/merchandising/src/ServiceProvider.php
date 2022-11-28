<?php

namespace SkylarkSoft\GoRMG\Merchandising;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as LaravelProvider;
use SkylarkSoft\GoRMG\Merchandising\Commands\FabricCosting;
use SkylarkSoft\GoRMG\Merchandising\Commands\ProTracker;
use SkylarkSoft\GoRMG\Merchandising\Commands\UpdatePoPcQuantityInPurchaseOrderCommand;
use SkylarkSoft\GoRMG\Merchandising\Directives\BladeDirectiveCriteria;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\ColorWiseFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\ItemFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\QtyWiseFormatter;
use SkylarkSoft\GoRMG\Merchandising\Services\Booking\SizeSensitiveFormatter;
use SkylarkSoft\GoRMG\SystemSettings\Models\UserWiseBuyerPermission;

class ServiceProvider extends LaravelProvider
{
    protected $rootPath;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->rootPath = realpath(__DIR__ . '/../');

        $this->app->bind(ItemFormatter::class, function () {
            if (in_array(request('sensitivity'), [1, 2])) {
                return new ColorWiseFormatter();
            }

            if (in_array(request('sensitivity'), [3, 4])) {
                return new SizeSensitiveFormatter();
            }

            return new QtyWiseFormatter();
        });

        $this->app->register(EventServiceProvider::class);
    }

    public function boot()
    {
        $this->loadMigrationsFrom($this->rootPath . '/database/migrations');
        $this->loadViewsFrom($this->rootPath . '/resources/views', PackageConst::VIEW_NAMESPACE);
        $this->loadRoutesFrom($this->rootPath . '/routes/web.php');
        $this->loadRoutesFrom($this->rootPath . '/routes/api.php');
        $this->loadModuleConfig();

        $this->loadMenu();
        $this->bindViewComposer();
        $this->publishAssets();
        $this->commands([FabricCosting::class, ProTracker::class, UpdatePoPcQuantityInPurchaseOrderCommand::class]);

        \Blade::if('buyerPermission', function ($buyerId = null, $permission = null) {
            return BladeDirectiveCriteria::buyerPermission($buyerId, $permission);
        });

        \Blade::if('permission', function ($permission) {
            return BladeDirectiveCriteria::permission($permission);
        });

        \Blade::if('buyerViewPermission', function ($buyerId, $viewName) {
            return BladeDirectiveCriteria::buyerViewPermission($buyerId, $viewName);
        });
    }

    private function loadMenu()
    {
        // keep menu items to a session variable and name it as 'menu'
        $menu = require($this->rootPath . '/menu/menu.php');
        if (session()->has('menu') && count($menu)) {
            foreach ($menu as $item) {
                session()->push('menu', $item);
            }
        } elseif (count($menu)) {
            session()->put('menu', $menu);
        }
    }

    private function loadModuleConfig()
    {
        $this->mergeConfigFrom($this->rootPath . '/config/module.php', PackageConst::VIEW_NAMESPACE);
        $module = config(PackageConst::VIEW_NAMESPACE);
        $modules = config('erp.modules') ?? [];
        array_push($modules, $module);

        config(['erp.modules' => $modules]);
    }

    private function bindViewComposer()
    {
        View::composer(PackageConst::VIEW_NAMESPACE . '::*', function ($view) {
            $view->with('viewPath', PackageConst::VIEW_NAMESPACE . '::');
        });
    }

    private function publishAssets()
    {
        $this->publishes([
            $this->rootPath . '/resources/assets' => public_path('modules/' . PackageConst::PACKAGE_NAME),
        ], 'public');
    }
}
