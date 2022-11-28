@includeIf('merchandising::marketing.marketing_nav')
@includeIf('merchandising::pages.merchandising_nav')
@includeIf('tna::include.tna_nav')
@includeIf('commercial::partials.commercial_nav')

@includeIf('merchandising::textile-production-nav.textile_production_nav')
@includeIf('merchandising::garments-production-nav.garments_production_nav')
@includeIf('finance::pages.finance_nav')
<!-- Knitracker Super Admin Nav -->
@includeIf('knittingdroplets::pages.knitracker_nav')
@includeIf('dyeingdroplets::pages.dyeingdroplets_nav')
@includeIf('textiledroplets::pages.textiledroplets_nav')
{{--Inventory Droplets--}}
@includeIf('inventory::inventory_droplets_nav')


{{--@includeIf('partials.planning_nav')--}}
<!-- cutting droplets-->
@includeIf('cuttingdroplets::pages.cutting_droplets_nav')

<!-- print/embr. droplets-->
@includeIf('printembrdroplets::pages.printembr_droplets_nav')

<!--input droplets -->
@includeIf('inputdroplets::pages.inputdroplets_nav')

<!-- sewing output droplets -->
@includeIf('sewingdroplets::pages.sewing_droplets_nav')

<!--washing droplets -->
@includeIf('washingdroplets::pages.washing_droplets_nav')

<!-- finishing droplets-->
@includeIf('finishingdroplets::pages.finishing_droplets_nav')

<!-- it droplets-->
@includeIf('it::it_nav')

<!--ie droplets -->
@includeIf('iedroplets::pages.ie_droplets_nav')

<!--subcontractnav-->
@includeIf('merchandising::subcontract-nav.subcontract_nav')

<!--skillmatrixnav-->
@includeIf('skillmatrix::pages.skillmatrix_nav')

<!--incentive droplets -->
@includeIf('incentive::pages.incentive_nav')

<!--MIS droplets-->
@if(getRole() == 'super-admin' || getRole() == 'admin')
    @includeIf('misdroplets::pages.mis_droplets_nav')
@endif

<!--warehousemanagement -->
@if(getRole() == 'super-admin' || getRole() == 'admin')
    @includeIf('warehousemanagement::pages.warehousemanagement_nav')
@endif

<!--security control -->
{{--@if(getRole() == 'super-admin')--}}
{{--    @includeIf('partials.security_control_nav')--}}
{{--@endif--}}

<!-- system system-settings droplets -->
@if(getRole() == 'super-admin' || getRole() == 'admin')
    @includeIf('system-settings::pages.systemsettings_nav')
@endif
