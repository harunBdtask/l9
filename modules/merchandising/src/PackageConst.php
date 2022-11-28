<?php

namespace SkylarkSoft\GoRMG\Merchandising;

class PackageConst
{
    const VIEW_NAMESPACE = 'merchandising';
    const PACKAGE_NAME = 'merchandising';
    const ASSET_PATH = 'modules/merchandising';
    const PREFIX = 'UGL';

    const ROUTE_LOOKUP = [
        "budgeting/create" => ['BUDGET'],
        "price-quotations/main-section-form" => ['PRICE', 'QUOTATION'],
        "price-quotations/costing-section-form" => ['PRICE', 'QUOTATION', 'COSTING'],
        "orders/create" => ['ORDERS'],
        "orders/edit" => ['ORDERS'],
        "fabric-bookings/create" => ['FABRIC', 'BOOKINGS'],
        "short-fabric-bookings/create" => ['SHORT', 'FABRIC', 'BOOKINGS'],
        "fabric-service-bookings/create" => ['FABRIC', 'SERVICE', 'BOOKINGS'],
        "trims-bookings/create" => ['TRIMS', 'BOOKINGS'],
        "trims-bookings/{id}/edit" => ['TRIMS', 'BOOKINGS'],
        "short-trims-bookings/create" => ['SHORT', 'TRIMS', 'BOOKINGS'],
        "short-trims-bookings/{id}/edit" => ['SHORT', 'TRIMS', 'BOOKINGS'],
        "work-order/embellishment/create" => ['EMBELLISHMENT', 'WORK', 'ORDER'],
        "work-order/embellishment/{id}/edit" => ['EMBELLISHMENT', 'WORK', 'ORDER'],
        "subcontract/textile-orders/create" => ['SUB-CONTRACT', 'TEXTILE', 'ORDERS'],
        "subcontract/textile-orders/details/{id}" => ['SUB-CONTRACT', 'TEXTILE', 'ORDERS', 'DETAILS'],
        "subcontract/material-fabric-receive/create" => ['SUB-CONTRACT', 'TEXTILE', 'FABRIC', 'RECEIVE'],
        "subcontract/material-fabric-receive/{id}/independent-basis" => ['SUB-CONTRACT', 'TEXTILE', 'FABRIC', 'RECEIVE'],
        "subcontract/material-fabric-receive/{id}/order-basis" => ['SUB-CONTRACT', 'TEXTILE', 'FABRIC', 'RECEIVE'],
        "subcontract/dyeing-process/batch-entry/create" => ['DYEING-PROCESS', 'BATCH', 'CREATE'],
        "hr/employee/create" => ['EMPLOYEE', 'CREATE'],
        "hr/employee/{id}/edit" => ['EMPLOYEE', 'UPDATE'],
        "hr/attendance/manual-entry" => ['ATTENDANCE', 'LIST'],
        "hr/attendance/manual-entry/create" => ['ATTENDANCE', 'MANUAL ENTRY'],
        "hr/attendance/check-list" => ['ATTENDANCE', 'CHECK LIST'],
    ];
}
