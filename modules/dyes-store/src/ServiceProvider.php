<?php

namespace SkylarkSoft\GoRMG\DyesStore;

use Illuminate\Support\ServiceProvider as LaravelProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
        $this->app->register(DsInventoryEventServiceProvider::class);
    }

    public function boot()
    {
        $this->loadMigrationsFrom($this->rootPath . '/database/migrations');
        $this->loadViewsFrom($this->rootPath . '/resources/views', PackageConst::VIEW_NAMESPACE);
        $this->loadRoutesFrom($this->rootPath . '/routes/web.php');
        $this->loadModuleConfig();
        $this->registerQueryBuilderMacros();
        $this->loadMenu();
        $this->bindViewComposer();
        $this->publishAssets();

        Collection::macro('paginate', function ($perPage = 5, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            return new LengthAwarePaginator(
                $this->forPage($page, $perPage),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => LengthAwarePaginator::resolveCurrentPath(),
                    'pageName' => $pageName,
                ]
            );
        });
    }

    protected function registerQueryBuilderMacros()
    {
        Builder::macro('addSubSelect', function ($column, $query) {
            if (is_null($this->columns)) {
                $this->select($this->from . '.*');
            }
            return $this->selectSub($query, $column);
        });

        Builder::macro('orderBySub', function ($query, $direction = 'asc') {
            list($query, $bindings) = $this->createSub($query);
            return $this->addBinding($bindings, 'order')->orderBy(DB::raw('(' . $query . ')'), $direction);
        });

        Builder::macro('addSelect', function ($column) {
            $column = is_array($column) ? $column : func_get_args();
            $this->columns = array_merge((array)$this->columns, $column);
            return $this;
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
