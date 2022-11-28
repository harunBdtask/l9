<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Services;

use SkylarkSoft\GoRMG\SystemSettings\Models\ApplicationMenuActiveData;
use SkylarkSoft\GoRMG\SystemSettings\Models\GarmentsProductionEntry;

class AuthenticatedMenuViewStatus
{
    private $productionEntryData;

    public function __construct($productionEntryData)
    {
        $this->productionEntryData = $productionEntryData;
    }

    public function setMenuViewStatus()
    {
        $menus = [];
        $erp_menu_view_status = $this->productionEntryData['erp_menu_view_status'];

        if ($erp_menu_view_status) {
            $applicationMenuHiddenData = ApplicationMenuActiveData::first() ?? null;
            if ($applicationMenuHiddenData && $applicationMenuHiddenData->inactive_menus) {
                $menus = $this->setCustomizedMenuView($applicationMenuHiddenData->inactive_menus);
            } else {
                $menus = $this->setCustomizedMenuView(null);
            }
        }
        return $menus;
    }

    public function setMenuViewStatusV1()
    {
        $menus = [];
        $erp_menu_view_status = $this->productionEntryData['erp_menu_view_status'];

        switch ($erp_menu_view_status) {
            case GarmentsProductionEntry::ERP_MENU_VIEW:
                $menus = $this->setErpMenuView();
                break;

            case GarmentsProductionEntry::PROTRACKER_MENU_VIEW:
                $menus = $this->setProtrackerMenuView();
                break;

            case GarmentsProductionEntry::TEXTILE_MENU_VIEW:
                $menus = $this->setTextileMenuView();
                break;

            case GarmentsProductionEntry::MC_INVENTORY_MENU_VIEW:
                $menus = $this->setMcInvntoryMenuView();
                break;
            
            case GarmentsProductionEntry::CUSTOMIZED_MENU_VIEW:
                $applicationMenuHiddenData = ApplicationMenuActiveData::first() ?? null;
                if ($applicationMenuHiddenData && $applicationMenuHiddenData->inactive_menus) {
                    $menus = $this->setCustomizedMenuView($applicationMenuHiddenData->inactive_menus);
                } else {
                    $menus = $this->setErpMenuView();
                }
                break;
            
            default:
                $menus = [];
                break;
        }
        return $menus;
    }

    private function setErpMenuView()
    {
        if (count(session('menu'))) {
            $menus = collect(session('menu'))->map(function ($menu, $key) {
                $module_name = $menu['title'];
                $priority = $menu['priority'];

                switch ($module_name) {
                    case 'PRODUCTION':
                        $menu['view_status'] = $this->productionEntryData['production_entry_type'] == 1 ? true : false;
                        break;
                    case 'PROTRACKER':
                        $menu['view_status'] = $this->productionEntryData['production_entry_type'] != 1 ? true : false;
                        if ($priority == 7004) { // For PROTRACKER Settings
                            $menu['view_status'] = true;
                        } else {
                            $menu_items = collect(collect($menu['items'])->where('title', 'IE Droplets')->first()['items']);
                            $menu_key = collect($menu['items'])->where('title', 'IE Droplets')->keys()->first();
                            $cutting_target_version = $this->productionEntryData['cutting_target_version'];
                            $cutting_target_v1_menu_key = $menu_items->where('url', url('/date-wise-cutting-targets'))->keys()->first();
                            $cutting_target_v2_menu_key = $menu_items->where('url', url('/v2/date-wise-cutting-targets'))->keys()->first();
                            $menu['items'][$menu_key]['items'][$cutting_target_v1_menu_key]['view_status'] = $cutting_target_version == 1;
                            $menu['items'][$menu_key]['items'][$cutting_target_v2_menu_key]['view_status'] = $cutting_target_version == 2;
                            
                            $sewing_line_target_vesrion = $this->productionEntryData['sewing_line_target_vesrion'];
                            $sewing_target_v1_menu_key = $menu_items->where('url', url('/sewing-line-target'))->keys()->first();
                            $sewing_target_v2_menu_key = $menu_items->where('url', url('/v2/sewing-line-target'))->keys()->first();
                            $menu['items'][$menu_key]['items'][$sewing_target_v1_menu_key]['view_status'] = $sewing_line_target_vesrion == 1;
                            $menu['items'][$menu_key]['items'][$sewing_target_v2_menu_key]['view_status'] = $sewing_line_target_vesrion == 2;
                        }
                        break;
                    case 'Merchandising':
                        $menu_items = collect($menu['items']);
                        if ($priority == 1002) { // priority 1002 is for CRM, Prioriry 7002 is for Settings
                            $pdf_view_status = $this->productionEntryData['pdf_upload_menu_hide_status'] == 1 ? false : true;
                            $pdf_menu_key = $menu_items->where('title', 'PDF File Uploads')->keys()->first();
                            $menu['items'][$pdf_menu_key]['view_status'] = $pdf_view_status;
                        }
                        $menu['view_status'] = true;
                        break;

                    case 'PLANNING':
                        $menu['view_status'] = !$this->productionEntryData['cutting_plan_menu_hide_status'] || !$this->productionEntryData['sewing_plan_menu_hide_status'];
                        break;

                    case 'Cutting Plan':
                        $menu['view_status'] = !$this->productionEntryData['cutting_plan_menu_hide_status'];
                        break;

                    case 'Sewing Plan':
                        $menu['view_status'] = !$this->productionEntryData['sewing_plan_menu_hide_status'];
                        break;
                    default:
                        $menu['view_status'] = true;
                        break;
                }
                return $menu;
            })->all();
        }

        return $menus;
    }

    private function setProtrackerMenuView()
    {
        if (count(session('menu'))) {
            $menus = collect(session('menu'))->map(function ($menu, $key) {
                $module_name = $menu['title'];
                $priority = $menu['priority'];

                switch ($module_name) {
                    case 'CRM':
                    case 'GARMENTS PRODUCTION':
                    case 'SYSTEM SETTINGS':
                    case 'General Settings':
                    case 'System Variables':
                        $menu['view_status'] = true;
                        break;
                    case 'PRODUCTION':
                        $menu['view_status'] = $this->productionEntryData['production_entry_type'] == 1 ? true : false;
                        break;
                    case 'PROTRACKER':
                        $menu['view_status'] = $this->productionEntryData['production_entry_type'] != 1 ? true : false;
                        if ($priority == 7004) { // For PROTRACKER Settings
                            $menu['view_status'] = true;
                        } else {
                            $menu_items = collect(collect($menu['items'])->where('title', 'IE Droplets')->first()['items']);
                            $menu_key = collect($menu['items'])->where('title', 'IE Droplets')->keys()->first();
                            $sewing_line_target_vesrion = $this->productionEntryData['sewing_line_target_vesrion'];
                            $sewing_target_v1_menu_key = $menu_items->where('url', url('/sewing-line-target'))->keys()->first();
                            $sewing_target_v2_menu_key = $menu_items->where('url', url('/v2/sewing-line-target'))->keys()->first();
                            $menu['items'][$menu_key]['items'][$sewing_target_v1_menu_key]['view_status'] = $sewing_line_target_vesrion == 1;
                            $menu['items'][$menu_key]['items'][$sewing_target_v2_menu_key]['view_status'] = $sewing_line_target_vesrion == 2;
                        }
                        break;

                    case 'Merchandising':
                        $menu_items = collect($menu['items']);
                        if ($priority == 1002) { // priority 1002 is for CRM, Prioriry 7002 is for Settings
                            $menu_keys = $menu_items->whereNotIn('title', ['Order/Style Tracking', 'Reports'])->keys();
                            foreach ($menu_keys as $menu_key) {
                                $menu['items'][$menu_key]['view_status'] = false;
                            }
                            $order_tracking_menu_key = $menu_items->where('title', 'Order/Style Tracking')->keys()->first();
                            $order_tracking_inner_menu_keys = collect($menu['items'][$order_tracking_menu_key]['items'])->whereNotIn('title', ['Order/Style Entry'])->keys();
                            foreach ($order_tracking_inner_menu_keys as $menu_key) {
                                $menu['items'][$order_tracking_menu_key]['items'][$menu_key]['view_status'] = false;
                            }
                        }
                        $menu['view_status'] = true;
                        break;

                    case 'PLANNING':
                        $menu['view_status'] = $this->productionEntryData['cutting_plan_menu_hide_status'] || $this->productionEntryData['sewing_plan_menu_hide_status'];
                        break;

                    case 'Cutting Plan':
                        $menu['view_status'] = $this->productionEntryData['cutting_plan_menu_hide_status'];
                        break;

                    case 'Sewing Plan':
                        $menu['view_status'] = $this->productionEntryData['sewing_plan_menu_hide_status'];
                        break;
                    default:
                        $menu['view_status'] = false;
                        break;
                }
                return $menu;
            })->all();
            return $menus;
        }
    }

    private function setTextileMenuView()
    {
        if (count(session('menu'))) {
            $menus = collect(session('menu'))->map(function ($menu, $key) {
                $module_name = $menu['title'];
                $priority = $menu['priority'];

                switch ($module_name) {
                    case 'CRM':
                    case 'Marketing':
                    case 'Merchandising':
                    case 'Approval':
                    case 'Time & Action':
                    case 'GARMENTS PRODUCTION':
                    case 'PROTRACKER':
                        $menu['view_status'] = false;
                        if ($priority == 7004) { // For PROTRACKER Settings
                            $menu['view_status'] = true;
                        }
                        break; 
                    case 'Trims Store':
                    case 'PRODUCTION':
                    case 'PLANNING':
                    case 'Cutting Plan':
                    case 'Sewing Plan':
                        $menu['view_status'] = false;
                        break;

                    default:
                        $menu['view_status'] = true;
                        break;
                }
                return $menu;
            })->all();
        }
        return $menus;
    }
    
    private function setMcInvntoryMenuView()
    {
        if (count(session('menu'))) {
            $menus = collect(session('menu'))->map(function ($menu, $key) {
                $module_name = $menu['title'];
                $priority = $menu['priority'];

                switch ($module_name) {
                    case 'M/C INVENTORY':
                    case 'M/C Settings':
                    case 'Machine Modules':
                    case 'SYSTEM SETTINGS':
                        $menu['view_status'] = true;
                        break;
                    case 'General Settings':
                        $menu_items = collect($menu['items']);
                        $menu_keys = $menu_items->whereNotIn('title', ['Group', 'Factories', 'Modules', 'Menus', 'Assign Full Permission', 'Assign Permission', 'Dpartmnts', 'Users'])->keys();
                        foreach ($menu_keys as $menu_key) {
                            $menu['items'][$menu_key]['view_status'] = false;
                        }
                        $menu['view_status'] = true;
                        break;

                    default:
                        $menu['view_status'] = false;
                        break;
                }
                return $menu;
            })->all();
        }
        return $menus;
    }

    private function setCustomizedMenuView($inactive_menus)
    {
        $menus = [];
        collect(\session()->get('menu'))
        ->sortBy('priority')
        ->each(function($item) use(&$menus) {
            \array_push($menus, $item);
        });
        (new ApplicationMenuFormatService)->format($menus, $inactive_menus);
        return $menus;
    }
}
