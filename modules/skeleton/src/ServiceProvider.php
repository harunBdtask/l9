<?php

namespace SkylarkSoft\GoRMG\Skeleton;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider as LaravelProvider;

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
        View::composer(PackageConst::VIEW_NAMESPACE . '::*', function ($view) {
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
