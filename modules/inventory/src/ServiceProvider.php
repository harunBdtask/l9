<?php

namespace SkylarkSoft\GoRMG\Inventory;

use Illuminate\Support\Facades\View;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider as LaravelProvider;
use SkylarkSoft\GoRMG\Commercial\Models\ProformaInvoice;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceive;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\FabricStore\FabricReceiveReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsIssueReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceive;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\TrimsReceiveReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnIssueReturnDetails;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceive;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnReceiveReturnDetail;
use SkylarkSoft\GoRMG\Inventory\Models\YarnTransferDetail;
use SkylarkSoft\GoRMG\Inventory\Observers\FabricIssueDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\FabricIssueReturnDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\FabricReceiveDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\FabricReceiveReturnDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\TrimsIssueDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\TrimsIssueReturnDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\TrimsReceiveDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\TrimsReceiveReturnDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\YarnIssueDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\YarnIssueReturnDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\YarnReceiveDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\YarnReceiveReturnDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Observers\YarnTransferDetailObserver;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsInfoSearchInterface;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsSearchFromBooking;
use SkylarkSoft\GoRMG\Inventory\Services\TrimsSearchFromPI;
use SkylarkSoft\GoRMG\Merchandising\Models\Bookings\FabricBooking;
use SkylarkSoft\GoRMG\Merchandising\Models\ShortBookings\ShortFabricBooking;

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
        $this->registerTrimsReceiveSearchContainer();
    }

    private function registerTrimsReceiveSearchContainer()
    {
        $this->app->bind(TrimsInfoSearchInterface::class, function () {
            $REQUEST_BASIS = request('receive_basis');

            if (!in_array($REQUEST_BASIS, [TrimsReceive::PI_BASIS, TrimsReceive::BOOKING_BASIS])) {
                return new \Exception('Invalid booking Basis for Search');
            }
            if ($REQUEST_BASIS == TrimsReceive::BOOKING_BASIS) {
                return new TrimsSearchFromBooking();
            }

            if ($REQUEST_BASIS == TrimsReceive::PI_BASIS) {
                return new TrimsSearchFromPI();
            }
        });
    }

    public function boot()
    {
        $this->loadViewsFrom($this->rootPath . '/resources/views', PackageConst::VIEW_NAMESPACE);
        $this->loadMigrationsFrom($this->rootPath . '/database/migrations');
        $this->loadRoutesFrom($this->rootPath . '/routes/web.php');
        $this->loadModuleConfig();

        $this->loadMenu();
        $this->bindViewComposer();
        $this->publishAssets();

        $this->bootObservers();

        Relation::morphMap([
            FabricReceive::PROFORMA_INVOICE => ProformaInvoice::class,
            FabricReceive::FABRIC_BOOKING => FabricBooking::class,
            FabricReceive::SHORT_BOOKING => ShortFabricBooking::class,
            YarnReceive::WO_BASIS => FabricBooking::class,
            YarnReceive::PI_BASIS => ProformaInvoice::class,
        ]);
    }

    private function bootObservers()
    {
        TrimsReceiveDetail::observe(TrimsReceiveDetailObserver::class);
        TrimsReceiveReturnDetail::observe(TrimsReceiveReturnDetailObserver::class);
        TrimsIssueDetail::observe(TrimsIssueDetailObserver::class);
        TrimsIssueReturnDetail::observe(TrimsIssueReturnDetailObserver::class);

        FabricReceiveDetail::observe(FabricReceiveDetailObserver::class);
        FabricReceiveReturnDetail::observe(FabricReceiveReturnDetailObserver::class);
        FabricIssueDetail::observe(FabricIssueDetailObserver::class);
        FabricIssueReturnDetail::observe(FabricIssueReturnDetailObserver::class);

        //YarnReceiveDetail::observe(YarnReceiveDetailObserver::class);
        //YarnReceiveReturnDetail::observe(YarnReceiveReturnDetailObserver::class);
        //YarnIssueDetail::observe(YarnIssueDetailObserver::class);
        //YarnIssueReturnDetail::observe(YarnIssueReturnDetailObserver::class);
        YarnTransferDetail::observe(YarnTransferDetailObserver::class);
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
