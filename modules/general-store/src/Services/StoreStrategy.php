<?php

namespace SkylarkSoft\GoRMG\GeneralStore\Services;

class StoreStrategy
{
    public static function getStrategy($store, $type): Store
    {

//        switch ($store) {
//            case 'general':
//                return new General($store, $type);
//            case 'yarn':
//                return new Yarn($store, $type);
//            case 'dnc':
//                return new DyesChemical($store, $type);
//            case 'trims':
//                return new Trims($store, $type);
//            case 'maintenance':
//                return new Maintenance($store, $type);
//        }

        return new General($store, $type);
    }
}
