@includeIf('merchandising::pages.merchandising_reports_nav')
{{-- @includeIf('tna::include.tna_nav') --}}
@includeIf('commercial::partials.commercial_reports_nav')
@includeIf('finance::pages.finance_reports_nav')
<!-- Knitracker Super Admin Nav -->
@includeIf('knittingdroplets::pages.knitracker_reports_nav')
@includeIf('dyeingdroplets::pages.dyeingdroplets_reports_nav')
@includeIf('textiledroplets::pages.textiledroplets_reports_nav')
{{--Inventory Droplets--}}
{{-- @includeIf('inventory::inventory_droplets_nav')
@includeIf('partials.planning_nav') --}}
<!-- cutting droplets-->
@includeIf('cuttingdroplets::pages.cutting_droplets_reports_nav')

<!-- print/embr. droplets-->
@includeIf('printembrdroplets::pages.printembr_droplets_reports_nav')

<!--input droplets -->
@includeIf('inputdroplets::pages.inputdroplets_reports_nav')

<!-- sewing output droplets -->
@includeIf('sewingdroplets::pages.sewing_droplets_reports_nav')

<!--washing droplets -->
@includeIf('washingdroplets::pages.washing_droplets_reports_nav')

<!-- finishing droplets-->
@includeIf('finishingdroplets::pages.finishing_droplets_reports_nav')

<!--ie droplets -->
@includeIf('iedroplets::pages.ie_droplets_reports_nav')

<!--skillmatrixnav-->
@includeIf('skillmatrix::pages.skillmatrix_reports_nav')

<!--incentive droplets -->
{{-- @includeIf('incentive::pages.incentive_nav') --}}

<!--MIS droplets-->
@if(getRole() == 'super-admin' || getRole() == 'admin' || getRole() == 'report-viewer')
    @includeIf('misdroplets::pages.mis_droplets_reports_nav')
@endif

<!--warehousemanagement -->
@if(getRole() == 'super-admin' || getRole() == 'admin' || getRole() == 'report-viewer')
    @includeIf('warehousemanagement::pages.warehousemanagement_reports_nav')
@endif

<!--security control -->
{{-- @if(getRole() == 'super-admin')
    @includeIf('partials.security_control_nav')
@endif --}}

<!-- system system-settings droplets -->
{{-- @if(getRole() == 'super-admin' || getRole() == 'admin')
    @includeIf('system-settings::pages.systemsettings_nav')
@endif --}}
