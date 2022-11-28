<?php

namespace SkylarkSoft\GoRMG\Cuttingdroplets;

use Illuminate\Support\ServiceProvider as LaravelProvider;
use Illuminate\Support\Facades\View;
use SkylarkSoft\GoRMG\Cuttingdroplets\Commands\UpdateBundleCardGenerationCacheCommand;
use SkylarkSoft\GoRMG\Cuttingdroplets\Commands\UpdateGarmentsItemIdInBundlleCardsCommand;
use SkylarkSoft\GoRMG\Cuttingdroplets\Commands\UpdateGarmentsItemInDateTableWiseCutProductionReportCommand;
use SkylarkSoft\GoRMG\Cuttingdroplets\Commands\UpdateGarmentsItemInTotalProductionReportCommand;
use SkylarkSoft\GoRMG\Cuttingdroplets\Models\BundleCard;
use SkylarkSoft\GoRMG\Cuttingdroplets\Observers\BundleCardObserver;

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
        $this->rootPath = realpath(__DIR__.'/../');
    }

    public function boot()
    {
        $this->loadMigrationsFrom($this->rootPath . '/database/migrations');
        $this->loadViewsFrom($this->rootPath . '/resources/views', PackageConst::VIEW_NAMESPACE);
        $this->loadRoutesFrom($this->rootPath . '/routes/web.php');
        $this->loadModuleConfig();

        $this->loadMenu();
        $this->bindViewComposer();
        $this->publishAssets();
        $this->commands([
            UpdateGarmentsItemIdInBundlleCardsCommand::class,
            UpdateGarmentsItemInTotalProductionReportCommand::class,
            UpdateBundleCardGenerationCacheCommand::class,
            UpdateGarmentsItemInDateTableWiseCutProductionReportCommand::class
        ]);

        BundleCard::observe(BundleCardObserver::class);
    }

    private function loadMenu()
    {
        // keep menu items to a session variable and name it as 'menu'
        $menu = require($this->rootPath. '/menu/menu.php');
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
        View::composer(PackageConst::VIEW_NAMESPACE . '::*', function($view) {
            $view->with('viewPath', PackageConst::VIEW_NAMESPACE . '::');
        });
    }

    private function publishAssets()
    {
        $this->publishes([
            $this->rootPath . '/resources/assets' => public_path('modules/'.PackageConst::PACKAGE_NAME),
        ], 'public');
    }
}
