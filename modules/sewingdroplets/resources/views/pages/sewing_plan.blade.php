<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>goRMG | An Ultimate ERP Solutions For Garments</title>
    <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- for ios 7 style, multi-resolution icon of 152x152 -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-barstyle" content="black-translucent">
    <link rel="apple-touch-icon" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">
    <meta name="apple-mobile-web-app-title" content="Flatkit">
    <!-- for Chrome on Android, multi-resolution icon of 196x196 -->
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" sizes="196x196" href="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}">


    <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler.js') }}' type="text/javascript"
            charset="utf-8"></script>
    <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler_timeline.js') }}' type="text/javascript"
            charset="utf-8"></script>
    <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler_tooltip.js') }}' type="text/javascript"
            charset="utf-8"></script>

    <link rel='stylesheet' type='text/css' href='{{ asset('dhtmlx_scheduler/css/dhtmlxscheduler_material.css') }}'>

    <!-- style -->
    <link rel="stylesheet" href="{{ asset('flatkit/assets/animate.css/animate.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('flatkit/assets/glyphicons/glyphicons.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('flatkit/assets/font-awesome/css/font-awesome.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('flatkit/assets/material-design-icons/material-design-icons.css') }}"
          type="text/css"/>

    <link rel="stylesheet" href="{{ asset('flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('css/animate.css') }}" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i&display=swap"
          rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/sewing-plan.css') }}" type="text/css"/>

    <style type="text/css">

    </style>

</head>
<body class="body" onload="init();">
<div class="header">
    <div class="sub-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <a href="{{ url('/') }}" class="btn btn-sm logo">
                        <img src="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}" alt="cut plan board"
                             style="height: 30px;">
                        <span class="p app-header" style="font-weight: 400; word-spacing: 0.2em; letter-spacing: 1px">SEWING <span
                                    class="p" style="font-weight: 700;">PLANNING</span> BOARD</span>
                    </a>
                </div>
                <div class="col-sm-2">
                    <div class="pull-right lock-buttons-div" style="padding-top: 0.23rem;">
                        <button class="lock-btn btn btn-sm btn-success hide" title="Lock Board"><i
                                    class="fa fa-unlock"></i></button>
                        <button class="unlock-btn btn btn-sm btn-danger hide" title="Unlock Board"><i
                                    class="fa fa-lock"></i></button>
                    </div>
                </div>
                <div class="col-sm-4">
                    {!! Form::open(['id' => 'sewing_plan_form']) !!}
                    @csrf
                    <div class="form-group">
                        <div class="row mb plan-form">
                            <div class="col-md-6">
                                {!! Form::select('factory_id', $factories ?? [],  null, ['class' => 'form-control form-control-sm', 'id' => 'factory_id', 'placeholder' => 'Select Factory']) !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::select('sewing_floor_id', $sewing_floors ?? [], null, ['class' => 'form-control form-control-sm', 'id' => 'sewing_floor_id', 'placeholder' => 'Select Floor']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
<div id="scheduler_here" class="dhx_cal_container">
    <div class="dhx_cal_navline">
        <div class="dhx_cal_prev_button">&nbsp;</div>
        <div class="dhx_cal_next_button">&nbsp;</div>
        <div class="dhx_cal_today_button"></div>
        <div class="dhx_cal_date"></div>
        <div class="dhx_cal_tab" name="day_tab" style="right:204px;"></div>
        <div class="dhx_cal_tab" name="week_tab" style="right:140px;"></div>
        <div class="dhx_cal_tab" name="timeline_tab" style="right:280px;"></div>
        <div class="dhx_cal_tab" name="month_tab" style="right:76px;"></div>
    </div>
    <div class="dhx_cal_header">
    </div>
    <div class="dhx_cal_data">
    </div>
</div>

<!-- jQuery -->
<script src="{{ asset('libs/jquery/jquery/dist/jquery.js') }}"></script>

<!-- ajax -->
<script src="{{ asset('flatkit/scripts/ajax.js') }}"></script>
<script type="text/javascript">
    /* Define Element */
    var user_id = '{{ userId() }}';
    var floor_id = '0';
    var permission = 1;
    var isLocked = false;
    var order_options = scheduler.serverList("order_list");
    var line_no = '';
    var selected_line_id = '';
    var learning_curve = [
        {'key': 0, 'label': 'Select One'},
        {'key': 1, 'label': 'Critical'},
        {'key': 2, 'label': 'Semi Critical'},
        {'key': 3, 'label': 'Most Critical'},
        {'key': 4, 'label': 'Not Critical'},
    ];
    var week_dates = [];
    var factory_open_time = 8;
    var lunch_time_start = 13;
    var lunch_time_end = 14;
    var lunch_period = lunch_time_end - lunch_time_start;

    var line_wise_work_hour = [
        {'line_id': 1, 'working_hour': 10},
        {'line_id': 2, 'working_hour': 8},
        {'line_id': 3, 'working_hour': 10},
        {'line_id': 4, 'working_hour': 8},
        {'line_id': 5, 'working_hour': 8},
        {'line_id': 6, 'working_hour': 8},
        {'line_id': 7, 'working_hour': 8},
        {'line_id': 8, 'working_hour': 8},
        {'line_id': 9, 'working_hour': 8},
        {'line_id': 10, 'working_hour': 8},
        {'line_id': 11, 'working_hour': 8},
        {'line_id': 12, 'working_hour': 8},
    ];

    function init() {

        scheduler.locale.labels.timeline_tab = "Timeline";
        scheduler.locale.labels.section_custom = "Section";
        scheduler.config.details_on_create = true;
        scheduler.config.details_on_dblclick = true;
        scheduler.templates.tooltip_date_format = scheduler.date.date_to_str("%Y-%m-%d");
        scheduler.templates.tooltip_time_format = scheduler.date.date_to_str("%H:%i");
        scheduler.skin = "material";
        scheduler.config.time_step = 1;

        //===============
        //Configuration
        //===============

        scheduler.ignore_timeline = function (date) {
            if (date.getDay() == 5 || inArray(getDate(date), week_dates))
                return true;
        };

        scheduler.createTimelineView({
            name: "timeline",
            x_unit: "day",
            x_date: "%D, %d %F",
            x_step: 1,
            x_size: 10,
            x_start: 0,
            y_unit: scheduler.serverList("sections"),
            y_property: "section_id",
            render: "bar",
            scrollable: true,
            column_width: 48,
            event_min_dy: 31
        });


        // Board Color Input
        scheduler.form_blocks["boardcolor"] = {
            render: function (sns) {
                return "<div class='dhx_cal_ltext' style='height:40px;'>" +
                    "<input name='board_color' type='color' class='board-color-picker'></div>";
            },
            set_value: function (node, value, ev) {
                node.querySelector("[name='board_color']").value = value || "";
            },
            get_value: function (node, ev) {
                return node.querySelector("[name='board_color']").value;
            },
            focus: function (node) {
                var input = node.querySelector("[name='board_color']");
                input.select();
                input.focus();
            }
        };
        //===============
        //Data loading
        //===============

        scheduler.locale.labels.section_description = "Plan Description";
        scheduler.locale.labels.section_buyerselect = "Buyer";
        scheduler.locale.labels.section_orderselect = "Order/Style";
        scheduler.locale.labels.section_purchaseorderselect = "PO";
        scheduler.locale.labels.section_orderqty = "Order Qty";
        scheduler.locale.labels.section_smv = "SMV";
        scheduler.locale.labels.section_shipmentdate = "Shipment Date";
        scheduler.locale.labels.section_shipmentcountry = "Shipment Country";
        scheduler.locale.labels.section_shipmethod = "Ship Method";
        scheduler.locale.labels.section_lineno = "Line No";
        scheduler.locale.labels.section_noofmachine = "No of Machine";
        scheduler.locale.labels.section_manpower = "Manpower";
        scheduler.locale.labels.section_absencepercent = "Absence(%)";
        scheduler.locale.labels.section_workhour = "Work Hour";
        scheduler.locale.labels.section_lineefficiency = "Line Efficiency";
        scheduler.locale.labels.section_linetarget = "Line Target";
        scheduler.locale.labels.section_linecapacity = "Line Capacity";
        scheduler.locale.labels.section_allocatedqty = "Allocated Qty";
        scheduler.locale.labels.section_remainingqty = "Remaining Qty";
        scheduler.locale.labels.section_learningcurve = "Learning Curve";
        scheduler.locale.labels.section_requiredhour = "Required Hour";
        scheduler.locale.labels.section_boardcolor = "Board Color";
        scheduler.config.show_loading = true;
        scheduler.config.lightbox.sections = [
            {
                name: "buyerselect",
                height: 20,
                map_to: "buyer_id",
                type: "select",
                options: [],
                onchange: function () {
                    getOrder(this.value);
                }
            },
            {
                name: "orderselect",
                height: 20,
                map_to: "order_id",
                type: "select",
                options: [],
                onchange: function () {
                    getPurchaseOrder(this.value);
                },
            },
            {
                name: "purchaseorderselect",
                height: 20,
                map_to: "purchase_order_id",
                type: "select",
                options: [],
                onchange: function () {
                    getPoInfo(this.value);
                },
            },
            {name: "description", height: 20, map_to: "plan_text", type: "textarea", focus: true},
            {name: "orderqty", height: 20, map_to: "order_qty", type: "textarea"},
            {name: "smv", height: 20, map_to: "smv", type: "textarea"},
            {name: "shipmentdate", height: 20, map_to: "shipment_date", type: "textarea"},
            {name: "shipmentcountry", height: 20, map_to: "shipment_country", type: "textarea"},
            {name: "shipmethod", height: 20, map_to: "ship_method", type: "textarea"},
            {name: "lineno", height: 20, map_to: "line_no", type: "textarea"},
            {name: "noofmachine", height: 20, map_to: "no_of_machine", type: "textarea"},
            {name: "manpower", height: 20, map_to: "man_power", type: "textarea"},
            {name: "absencepercent", height: 20, map_to: "absence_percent", type: "textarea"},
            {name: "workhour", height: 20, map_to: "work_hour", type: "textarea"},
            {name: "lineefficiency", height: 20, map_to: "line_efficiency", type: "textarea"},
            {name: "linetarget", height: 20, map_to: "line_target", type: "textarea"},
            {name: "linecapacity", height: 20, map_to: "line_capacity", type: "textarea"},
            {name: "allocatedqty", height: 20, map_to: "allocated_qty", type: "textarea"},
            {name: "remainingqty", height: 20, map_to: "remaining_qty", type: "textarea"},
            {
                name: "learningcurve",
                height: 20,
                map_to: "learningcurve",
                type: "select",
                options: learning_curve
            },
            {name: "requiredhour", height: 20, map_to: "required_hour", type: "textarea"},
            {name: "boardcolor", height: 20, map_to: "board_color", type: "boardcolor", default_value: "#0288d1"},
            {name: "time", height: 72, type: "time", map_to: "auto"}
        ];
        scheduler.init('scheduler_here', new Date(), "timeline");
    }

    // For Readonly Field
    scheduler.attachEvent("onLightbox", function () {
        var orderqty_section = scheduler.formSection("orderqty");
        var smv_section = scheduler.formSection("smv");
        var shipmentdate_section = scheduler.formSection("shipmentdate");
        var shipmentcountry_section = scheduler.formSection("shipmentcountry");
        var shipmethod_section = scheduler.formSection("shipmethod");
        var lineno_section = scheduler.formSection("lineno");
        var linetarget_section = scheduler.formSection("linetarget");
        var linecapacity_section = scheduler.formSection("linecapacity");
        var allocatedqty_section = scheduler.formSection("allocatedqty");
        var remainingqty_section = scheduler.formSection("remainingqty");
        var requiredhour_section = scheduler.formSection("requiredhour");
        var noofmachine_section = scheduler.formSection("noofmachine");
        var manpower_section = scheduler.formSection("manpower");
        var absencepercent_section = scheduler.formSection("absencepercent");
        var workhour_section = scheduler.formSection("workhour");
        var lineefficiency_section = scheduler.formSection("lineefficiency");
        // Time related
        var time = scheduler.formSection("time");
        let start_date_time = time.getValue().start_date;
        let end_date_time = time.getValue().end_date;
        let line_working_hour;
        $.each(line_wise_work_hour, function (index, value) {
            if (value.line_id == selected_line_id) {
                line_working_hour = value.working_hour;
            }
        });
        let daily_line_closing_time = factory_open_time + line_working_hour;

        if (start_date_time.getHours() < factory_open_time || start_date_time.getHours() >= daily_line_closing_time) {
            start_date_time.setHours(factory_open_time);
        }

        if (end_date_time.getHours() < factory_open_time || end_date_time.getHours() >= daily_line_closing_time) {
            end_date_time.setHours(factory_open_time);
        }

        time.setValue(null, {start_date: start_date_time, end_date: end_date_time});

        noofmachine_section.control.setAttribute('onkeyup', 'getLineCapacity();');
        manpower_section.control.setAttribute('onkeyup', 'getLineTarget();getRequiredHour();');
        absencepercent_section.control.setAttribute('onkeyup', 'getLineCapacity();');
        workhour_section.control.setAttribute('onkeyup', 'getLineCapacity(); getLineTarget();getRequiredHour();');
        lineefficiency_section.control.setAttribute('onkeyup', 'getLineCapacity(); getLineTarget();getRequiredHour();');
        allocatedqty_section.control.setAttribute('onkeyup', 'getRemainingQty();getRequiredHour();');

        orderqty_section.control.disabled = true;
        orderqty_section.control.className = 'readonly-text';

        smv_section.control.disabled = true;
        smv_section.control.className = 'readonly-text';

        shipmentdate_section.control.disabled = true;
        shipmentdate_section.control.className = 'readonly-text';

        shipmentcountry_section.control.disabled = true;
        shipmentcountry_section.control.className = 'readonly-text';

        shipmethod_section.control.disabled = true;
        shipmethod_section.control.className = 'readonly-text';

        lineno_section.control.disabled = true;
        lineno_section.control.className = 'readonly-text';
        lineno_section.control.value = line_no;

        linetarget_section.control.disabled = true;
        linetarget_section.control.className = 'readonly-text';

        linecapacity_section.control.disabled = true;
        linecapacity_section.control.className = 'readonly-text';

        remainingqty_section.control.disabled = true;
        remainingqty_section.control.className = 'readonly-text';

        requiredhour_section.control.disabled = true;
        requiredhour_section.control.className = 'readonly-text';

        // SET calculation function

    });

    // For Copy Plan
    scheduler.attachEvent("onLightboxButton", function (button_id, node, native_event) {
        if (button_id == "copy_button") {
            eventId = node.getAttribute('event-id');
            var event = scheduler.getEvent(eventId);
            event.id = "";
            scheduler.addEvent(event);
            $(document).find('.dhx_cal_light')[0].className = 'dhx_cal_light dhx_cal_light_wide zoomOut animated';
            setTimeout(() => {
                $(document).find('.dhx_cal_light')[0].className = 'dhx_cal_light dhx_cal_light_wide';
                scheduler.endLightbox(false);
            }, 500);
            return false;
        }
    });

    // Get Cutting Floor For selected factory
    $(document).on('change', '#factory_id', function () {
        // For reseting sections
        floor_id = '0';
        $('#sewing_floor_id').val('');
        scheduler.clearAll();
        scheduler.parse([]);
        let factoryID = $(this).val();
        if (factoryID) {
            getBuyer(factoryID);
            getSewingFloor(factoryID);
            scheduler.load("/api/sewing-plans/" + floor_id + "/" + user_id, "json");
        }
    });

    // For change Floor
    $('#sewing_floor_id').on('change', function () {
        var sewing_floor_id = $(this).val();
        floor_id = sewing_floor_id;
        scheduler.clearAll();
        scheduler.parse([]);
        if (sewing_floor_id) {
            scheduler.load("/api/sewing-plans/" + floor_id + "/" + user_id, "json");
            /*checkIfUserHavePermission(sewing_floor_id, user_id);
            checkPlanBoardIsLocked(sewing_floor_id)*/
        }
    });

    // Check Permission
    function checkIfUserHavePermission(sewing_floor_id, user_id) {
        var permission_data;
        $.ajax({
            type: 'GET',
            url: '/get-cutting-plan-user-permission/' + sewing_floor_id + '/' + user_id,
        }).done(function (response) {
            permission_data = response;
            setPermission(permission_data);
        });
        return true;
    }

    // Set Permission
    function setPermission(permission_data) {
        permission = permission_data;
    }

    // Check For Lock Board
    function checkPlanBoardIsLocked(sewing_floor_id) {
        $.ajax({
            type: 'GET',
            url: '/check-cutting-plan-board-lock/' + sewing_floor_id,
        }).done(function (response) {
            setLockInfo(response);
        });
    }

    function setLockInfo(data) {
        isLocked = data.is_locked;
        if (isLocked) {
            $('.unlock-btn').removeClass('hide');
        } else {
            $('.lock-btn').removeClass('hide');
        }
    }

    // For Database processing
    var dp = new dataProcessor("/api/sewing-plans/" + floor_id + "/" + user_id);
    dp.init(scheduler);
    dp.setTransactionMode("REST");

    // After Update Data
    dp.attachEvent("onAfterUpdateFinish", function () {
        scheduler.clearAll();
        scheduler.load("/api/sewing-plans/" + floor_id + "/" + user_id, "json");
    });

    // Before opening lightbox set transition and fetch data for edit
    scheduler.attachEvent("onBeforeLightbox", function (id) {
        if (permission > 0) {
            if (!isLocked) {
                let section_id = $(document).find('[event_id = ' + id + ']').parents('.dhx_matrix_line')[0].getAttribute('data-section-id');
                let sections = scheduler.serverList("sections");
                $.each(sections, function (key, val) {
                    if (val.key == section_id) {
                        line_no = val.label;
                        selected_line_id = val.key;
                    }
                });
                //getCuttingPlanData(id);
                $(document).find('.dhx_cal_light')[0].className = 'dhx_cal_light dhx_cal_light_wide zoomIn animated';
                $(document).find('.dhx_section_time').parents('.dhx_wrap_section')[0].style.cssText = "width: 100%;";

                setTimeout(() => {
                    $(document).find('.dhx_cal_light')[0].className = 'dhx_cal_light dhx_cal_light_wide';
                }, 1000);

                return true;
            } else {
                planLockedMessage();
                return false;
            }
        } else {
            permissionErrorMessage();
            return false;
        }
    });

    // Before Drag Event
    scheduler.attachEvent("onBeforeDrag", function (id, mode, e) {
        if (permission > 0) {
            if (!isLocked) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    });

    function planLockedMessage() {
        dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-check-circle' style='font-size: 39px;'></i></span><span style='float: right;'>Locked Board: <br/><b>This Board is Locked!</b></span>",
            type: 'successMsg'
        });
    }

    function permissionErrorMessage() {
        dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-times-circle' style='font-size: 39px;'></i></span><span style='float: right;'>Authentication Error: <br/><b>You are not Permitted!</b></span>",
            type: 'dangerMsg'
        });
    }

    // Single click event create and lightbox open
    var fix_date = function (date) { // 17:48:56 -> 17:30:00
        date = new Date(date);
        if (date.getMinutes() > 30) date.setMinutes(30);
        else date.setMinutes(0);
        date.setSeconds(0);
        return date;
    };
    scheduler.attachEvent("onClick", function (id, e) {
//        $(document).find('.copy_button_set')[0].style.display = 'block';
        scheduler.showLightbox(id);
    });
    var event_step = 1;
    scheduler.attachEvent("onEmptyClick", function (date, native_event) {
        if (permission > 0) {
            if (!isLocked) {
                var section_id = native_event.path[3].getAttribute('data-section-id');
                var fixed_date = fix_date(date);
                scheduler.addEventNow({
                    section_id: section_id,
                    start_date: fixed_date
                }, scheduler.date.add(fixed_date, event_step, "day"));
            } else {
                planLockedMessage();
                return false;
            }
        } else {
            permissionErrorMessage();
            return false;
        }
    });

    // For Edit Form Data fetching and setting function
    function getCuttingPlanData(event_id) {
        $.ajax({
            type: "GET",
            url: "/get-cutting-plan-data/" + event_id,
            success: function (response) {
                scheduler.formSection('orderselect').control.options.length = 0;
                scheduler.formSection('purchaseorderselect').control.options.length = 0;
                let b = 0;
                scheduler.formSection('orderselect').control[b] = new Option('Select Order', '');
                $.each(response.booking_no_list, function (key, val) {
                    b++;
                    scheduler.formSection('orderselect').control[b] = new Option(val.label, val.key);
                });
                let p = 0;
                scheduler.formSection('purchaseorderselect').control[p] = new Option('Select PO', '');
                $.each(response.po_list, function (key, val) {
                    p++;
                    scheduler.formSection('purchaseorderselect').control[p] = new Option(val.label, val.key);
                });
                scheduler.formSection('orderselect').setValue(response.order_id);
                scheduler.formSection('purchaseorderselect').setValue(response.purchase_order_id);
            }
        });
    }

    // Get Cutting Floor
    function getSewingFloor(factoryID) {
        $('#sewing_floor_id').empty();
        $.ajax({
            type: 'GET',
            url: '/get-sewing-floors-for-factory/' + factoryID,
            success: function (response) {
                var sewingFloorDropdown = '<option value="">Select Floor</option>';
                if (Object.keys(response).length > 0) {
                    $.each(response, function (index, val) {
                        sewingFloorDropdown += '<option value="' + index + '">' + val + '</option>';
                    });
                }
                $('#sewing_floor_id').html(sewingFloorDropdown);
            }
        });
    }

    // Get Buyer
    function getBuyer(factoryId) {
        // Get Buyers for cutting plan
        $.ajax({
            type: 'GET',
            url: '/get-buyers-for-dropdown/' + factoryId,
            success: function (response) {
                if (Object.keys(response).length > 0) {
                    let i = 0;
                    scheduler.formSection('buyerselect').control.length = 0;
                    scheduler.formSection('buyerselect').control[i] = new Option('Select Buyer', '');
                    $.each(response, function (index, val) {
                        i++;
                        scheduler.formSection('buyerselect').control[i] = new Option(val, index);
                    });
                }
            }
        });
    }


    // Get Order
    function getOrder(buyer_id) {
        scheduler.formSection('description').setValue('');
        scheduler.formSection('orderqty').setValue('');
        scheduler.formSection('smv').setValue('');
        scheduler.formSection('shipmentdate').setValue('');
        scheduler.formSection('shipmentcountry').setValue('');
        scheduler.formSection('shipmethod').setValue('');
        if (buyer_id) {
            $.ajax({
                type: "GET",
                url: "/get-orders/" + buyer_id,
                success: function (response) {
                    if (Object.keys(response).length > 0) {
                        let p = 0;
                        scheduler.formSection('orderselect').control.options.length = 0;
                        scheduler.formSection('purchaseorderselect').control.options.length = 0;

                        scheduler.formSection('orderselect').control[p] = new Option('Select Order', '');
                        $.each(response, function (index, val) {
                            p++;
                            scheduler.formSection('orderselect').control[p] = new Option(val, index);
                        });
                    }
                }
            });
        }
    }

    // Get Purchase Order
    function getPurchaseOrder(order_id) {
        var selectedBuyer = scheduler.formSection('buyerselect').control.selectedIndex;
        var buyer = scheduler.formSection('buyerselect').control.options[selectedBuyer].text;
        var order_style;
        scheduler.formSection('description').setValue('');
        scheduler.formSection('orderqty').setValue('');
        scheduler.formSection('smv').setValue('');
        scheduler.formSection('shipmentdate').setValue('');
        scheduler.formSection('shipmentcountry').setValue('');
        scheduler.formSection('shipmethod').setValue('');
        if (order_id) {
            if (order_options.length > 0) {
                $.each(order_options, function (index, value) {
                    if (value.key == order_id) {
                        order_style = value.label;
                    }
                });
            } else {
                let selectedOrder = scheduler.formSection('orderselect').control.selectedIndex;
                order_style = scheduler.formSection('orderselect').control.options[selectedOrder].text;
            }

            scheduler.formSection('description').setValue(buyer + '/' + order_style);
            $.ajax({
                type: 'GET',
                url: '/get-purchase-orders-for-cutting-plan/' + order_id,
                success: function (response) {
                    if (Object.keys(response).length > 0) {
                        let p = 0;
                        scheduler.formSection('purchaseorderselect').control.options.length = 0;
                        scheduler.formSection('purchaseorderselect').control[p] = new Option('Select Purchase Order', '');
                        $.each(response, function (index, val) {
                            p++;
                            scheduler.formSection('purchaseorderselect').control[p] = new Option(val, index);
                        });
                    }
                }
            });
        }
    }

    // Get PO Info
    function getPoInfo(purchase_order_id) {
        if (purchase_order_id) {
            $.ajax({
                type: 'GET',
                url: '/get-po-info/' + purchase_order_id,
                success: function (response) {
                    if (Object.keys(response).length > 0) {
                        let orderQty = response.po_quantity;
                        let smv = response.smv;
                        let shipment_date = response.ex_factory_date;
                        let shipment_country = response.po_details[0].countries ? response.po_details[0].countries.name : 'Default';
                        let shipment_mode = {1: 'Sea', 2: 'Air', 3: 'Road', 4: 'Train', 5: 'Sea/Air', 6: 'Road/Air'};
                        let shipment_method = shipment_mode[response.shipping_mode];
                        scheduler.formSection('orderqty').setValue(orderQty);
                        scheduler.formSection('smv').setValue(smv);
                        scheduler.formSection('shipmentdate').setValue(shipment_date);
                        scheduler.formSection('shipmentcountry').setValue(shipment_country);
                        scheduler.formSection('shipmethod').setValue(shipment_method);
                    }
                }
            });
        }
    }

    // Validate Add form
    scheduler.attachEvent("onEventSave", function (id, ev) {
        /*if (!ev.plan_text) {
            dhtmlx.alert("Plan Description must not be empty");
            return false;
        }
        if (!ev.buyer_id) {
            dhtmlx.alert("Buyer must be selected");
            return false;
        }
        if (!ev.order_id) {
            dhtmlx.alert("Booking must be selected");
            return false;
        }
        if (!ev.purchase_order_id) {
            dhtmlx.alert("PO must be selected");
            return false;
        }
        if (!ev.color_id) {
            dhtmlx.alert("Color must be selected");
            return false;
        }
        if (!ev.plan_qty) {
            dhtmlx.alert("Plan Qty is mandatory");
            return false;
        }*/
        return true;
    });

    // After saving an event
    scheduler.attachEvent("onEventAdded", function (id, ev) {
        dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-check-circle' style='font-size: 39px;'></i></span><span style='float: right;'>You've created the plan: <br/><b>" + ev.plan_text + "</b></span>",
            type: 'successMsg'
        });
        return true;
    });

    // Validate Edit form
    scheduler.attachEvent("onEventChanged", function (id, ev) {
        /* if (!ev.plan_text) {
             dhtmlx.alert("Plan Description must not be empty");
             return false;
         }
         if (!ev.buyer_id) {
             dhtmlx.alert("Buyer must be selected");
             return false;
         }
         if (!ev.order_id) {
             dhtmlx.alert("Booking must be selected");
             return false;
         }
         if (!ev.purchase_order_id) {
             dhtmlx.alert("PO must be selected");
             return false;
         }
         if (!ev.color_id) {
             dhtmlx.alert("Color must be selected");
             return false;
         }*/

        dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-exclamation-circle' style='font-size: 39px;'></i></span><span style='float: right;'>You've updated the plan: <br/><b>" + ev.plan_text + "</b></span>",
            type: 'updateMsg'
        });
        return true;
    });

    scheduler.attachEvent("onEventDeleted", function (id, ev) {
        dhtmlx.message({
            text: "<span style='float: left;'><i class='fa fa-times-circle' style='font-size: 39px;'></i></span><span style='float: right;'>You've Deleted the plan: <br/><b>" + (ev.text ? ev.text : ev.plan_text) + "</b></span>",
            type: 'dangerMsg'
        });
        return true;
    });

    // View mode change event( day, week, month, timeline)
    /*scheduler.attachEvent("onBeforeViewChange", function (old_mode, old_date, mode, date) {
        var old_plan_date = $(document).find('#plan_date').val();
        var new_plan_date = getDate(date);
        $(document).find('#plan_date').val(new_plan_date);
        floor_id = $('#sewing_floor_id').val();
        scheduler.templates.event_bar_text = function (start, end, event) {
            return event.text;
        };
        if (old_plan_date !== new_plan_date) {
            if (floor_id) {
                scheduler.load("/api/sewing-plans/" + floor_id + "/" + user_id + "?mode=" + mode, "json");
            }
        } else if (mode == 'week' || mode == 'month') {
            scheduler.templates.event_bar_text = function (start, end, event) {
                return event.plan_text;
            };
            scheduler.load("/api/sewing-plans/" + floor_id + "/" + user_id + "?mode=" + mode, "json");
        }
        return true;
    });*/

    // parse date to set plan date in html
    function getDate(today) {
        var dd = today.getDate();
        var mm = today.getMonth() + 1; //January is 0!
        var yyyy = today.getFullYear();

        if (dd < 10) {
            dd = '0' + dd
        }

        if (mm < 10) {
            mm = '0' + mm
        }

        today = yyyy + '-' + mm + '-' + dd;
        return today;
    }

    function compareDates(date1, date2) {
        if (date1 <= date2) return 1;
        else return 0;
    }

    // Plan board color change
    scheduler.attachEvent("onLoadEnd", function () {
        // use an arbitrary id for the style element
        var styleId = "dynamicSchedulerStyles";

        // in case you'll be reloading options with colors - reuse previously
        // created style element

        var element = document.getElementById(styleId);
        if (!element) {
            element = document.createElement("style");
            element.id = styleId;
            document.querySelector("head").appendChild(element);
        }
        var html = [];
        var resources = scheduler.serverList("board_colors");
        var today = getDate(new Date());
        // generate css styles for each option and write css into the style element,

        resources.forEach(function (r) {
            progress_percent = r.plan_qty > 0 ? (r.production / r.plan_qty) * 100 : 0;
            let background_color;
            if (compareDates(new Date(r.plan_date), new Date(today))) {
                if (progress_percent <= 50) {
                    background_color = '#bf0000';
                } else if (progress_percent > 50 && progress_percent <= 70) {
                    background_color = '#e7b52e';
                } else {
                    background_color = '#08ab09';
                }
            } else {
                background_color = r.backgroundColor;
            }
            html.push(".dhx_cal_event_line.event_resource_" + r.key + "{" +
                "background-color:" + background_color + "!important; " +
                "}" +
                ".dhx_cal_event_line.event_resource_" + r.key + ">.event-progress{" +
                "background-color: rgba(0, 0, 0, 0.17)!important;" +
                "width:" + progress_percent + "%!important;" +
                "}" +
                ".dhx_cal_event.event_resource_" + r.key + ">.dhx_body{" +
                "background-color:" + background_color + "!important;" +
                "}" +
                ".dhx_cal_event.event_resource_" + r.key + ">.dhx_title{" +
                "background-color:" + background_color + "!important;" +
                "}" +
                ".dhx_cal_event_clear.event_resource_" + r.key + "{" +
                "color:" + background_color + "!important;" +
                "}"
            );
        });
        element.innerHTML = html.join("");
    });

    scheduler.templates.event_class = function (start, end, event) {
        var css = [];

        if (event.board_color) {
            css.push("event_resource_" + event.id);
        }

        return css.join(" ");
    };

    // Tooltip
    scheduler.templates.tooltip_text = function (start, end, ev) {
        var today = getDate(new Date());
        let progress_percent = ev.plan_qty > 0 ? (ev.production / ev.plan_qty) * 100 : 0;
        let background_color;
        if (compareDates(new Date(ev.plan_date), new Date(today))) {
            if (progress_percent <= 50) {
                background_color = '#bf0000';
            } else if (progress_percent > 50 && progress_percent <= 70) {
                background_color = '#e7b52e';
            } else {
                background_color = '#08ab09';
            }
        } else {
            background_color = ev.board_color;
        }
        return "<p class='tooltip-elements'>"
            + "<span class='tooltip-tag'>Plan </span>"
            + "<i class='fa fa-caret-right'></i> "
            + "<span class='tooltip-plan-desc '>" + ev.plan_text + "</span> "
            + "<span class='tooltip-plan-color pull-right' style='background-color: " + background_color + "'></span> "
            + "</p>"
            + "<p class='tooltip-elements'>"
            + "<span class='tooltip-tag'>Progress </span> "
            + "<i class='fa fa-caret-right'></i> "
            + "<span class='tooltip-progress-percent pull-right' style='padding: inherit;'>" + Math.round(progress_percent) + "% </span>"
            + "</p>"
            + "</p>"
            + "<p class='tooltip-elements'>"
            + "<span class='tooltip-date-time-range'>" + scheduler.templates.tooltip_time_format(start) + " - " + scheduler.templates.tooltip_time_format(end) + "</span> "
            + "<span class='tooltip-date-time-range pull-right' style='padding: inherit;'>" + scheduler.templates.tooltip_date_format(start) + " - " + scheduler.templates.tooltip_date_format(end) + "</span>"
            + "</p>";
    };

    // Update Lock Plan Data
    $(document).on('click', '.lock-btn', function () {
        var thisDom = $(this);
        var sewing_floor_id = $('#sewing_floor_id').val();
        var is_locked = 1;
        var formData = {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'sewing_floor_id': sewing_floor_id,
            'user_id': user_id,
            'is_locked': is_locked
        };
        if (sewing_floor_id) {
            $.ajax({
                type: 'GET',
                url: '/update-cutting-plan-board-lock-info/',
                data: formData
            }).done(function (response) {
                setPlanBoardLocked(response, thisDom, 'unlock-btn', is_locked);
            });
        }
    });


    $(document).on('click', '.unlock-btn', function () {
        var thisDom = $(this);
        var sewing_floor_id = $('#sewing_floor_id').val();
        var is_locked = 0;
        var formData = {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'sewing_floor_id': sewing_floor_id,
            'user_id': user_id,
            'is_locked': is_locked
        };
        if (sewing_floor_id) {
            $.ajax({
                type: 'GET',
                url: '/update-cutting-plan-board-lock-info/',
                data: formData
            }).done(function (response) {
                setPlanBoardLocked(response, thisDom, 'lock-btn', is_locked);
            });
        }
    });

    function setPlanBoardLocked(response, thisDom, otherBtn, is_locked) {
        if (response.type == 'success') {
            thisDom.addClass('hide');
            $('.' + otherBtn + '').removeClass('hide');
            isLocked = is_locked;
        } else if (response.type == 'danger') {
            permissionErrorMessage();
        }
    }

    // Line Capacity Calculation
    function getLineCapacity() {
        let smv = scheduler.formSection('smv').control.value;
        let machine = scheduler.formSection('noofmachine').control.value;
        let working_hour = scheduler.formSection('workhour').control.value;
        let absence_percent = scheduler.formSection('absencepercent').control.value;
        let efficiency = scheduler.formSection('lineefficiency').control.value;

        if (isNaN(smv)) {
            alert('Not a Number');
            scheduler.formSection('smv').setValue('');
            scheduler.formSection('linecapacity').setValue('');
            return false;
        }

        if (isNaN(machine)) {
            alert('Not a Number');
            scheduler.formSection('noofmachine').setValue('');
            scheduler.formSection('linecapacity').setValue('');
            return false;
        }

        if (isNaN(working_hour)) {
            alert('Not a Number');
            scheduler.formSection('workhour').setValue('');
            scheduler.formSection('linecapacity').setValue('');
            return false;
        }

        if (isNaN(absence_percent)) {
            alert('Not a Number');
            scheduler.formSection('absencepercent').setValue('');
            scheduler.formSection('linecapacity').setValue('');
            return false;
        }

        if (isNaN(efficiency)) {
            alert('Not a Number');
            scheduler.formSection('efficiency').setValue('');
            scheduler.formSection('linecapacity').setValue('');
            return false;
        }

        var lineCapacity;
        if (smv > 0) {
            lineCapacity = ((((Number(machine) * Number(working_hour) * 60) - Number(absence_percent)) * Number(efficiency)) / Number(smv)).toFixed(2);
        } else {
            lineCapacity = 0;
        }
        scheduler.formSection('linecapacity').setValue(lineCapacity);
    }

    // Line Target Calculation
    function getLineTarget() {
        let smv = scheduler.formSection('smv').control.value;
        let man_power = scheduler.formSection('manpower').control.value;
        let working_hour = scheduler.formSection('workhour').control.value;
        let efficiency = scheduler.formSection('lineefficiency').control.value;

        if (isNaN(Number(smv))) {
            alert('Not a Number');
            scheduler.formSection('smv').setValue('');
            scheduler.formSection('linetarget').setValue('');
            return false;
        }

        if (isNaN(Number(man_power))) {
            alert('Not a Number');
            scheduler.formSection('manpower').setValue('');
            scheduler.formSection('linetarget').setValue('');
            return false;
        }

        if (isNaN(Number(working_hour))) {
            alert('Not a Number');
            scheduler.formSection('workhour').setValue('');
            scheduler.formSection('linetarget').setValue('');
            return false;
        }

        if (isNaN(Number(efficiency))) {
            alert('Not a Number');
            scheduler.formSection('lineefficiency').setValue('');
            scheduler.formSection('linetarget').setValue('');
            return false;
        }

        var lineTarget;
        if (smv > 0) {
            lineTarget = ((Number(man_power) * Number(working_hour) * (Number(efficiency) / 100)) / Number(smv)).toFixed(2);
        } else {
            lineTarget = 0;
        }
        scheduler.formSection('linetarget').setValue(lineTarget);
    }

    // Calculate Remaining Qty
    function getRemainingQty() {
        let order_qty = scheduler.formSection('orderqty').control.value;
        let allocated_qty = scheduler.formSection('allocatedqty').control.value;

        if (isNaN(Number(allocated_qty))) {
            alert('Not a number!');
            scheduler.formSection('allocatedqty').setValue('');
            scheduler.formSection("remainingqty").setValue('');
            return false;
        }
        let remaining_qty = Number(order_qty) - Number(allocated_qty);

        if (remaining_qty < 0) {
            alert('Order Qty exceeded!');
            scheduler.formSection('allocatedqty').setValue('');
            scheduler.formSection("remainingqty").setValue('');
            return false;
        }
        scheduler.formSection("remainingqty").setValue(remaining_qty);
        //getRequiredHour();
    }

    function getRequiredHour() {
        let line_target = scheduler.formSection('linetarget').control.value;
        let allocated_qty = scheduler.formSection('allocatedqty').control.value;
        let requiredHourSection = scheduler.formSection('requiredhour');

        let required_hour;
        if (Number(line_target) > 0) {
            required_hour = Math.round(Number(allocated_qty) / Number(line_target));
        } else {
            required_hour = 0;
        }
        requiredHourSection.setValue(required_hour);

        let start_date_time = scheduler.formSection("time").getValue().start_date;
        let end_date_time = add_minutes(start_date_time, required_hour * 60);
        let time = scheduler.formSection('time');

        console.log(end_date_time);
        time.setValue(null, {start_date: start_date_time, end_date: end_date_time});
    }

    // add minutes
    var add_minutes = function (dt, minutes) {
        return new Date(dt.getTime() + minutes * 60000);
    }

    // Check if element exist in the array
    function inArray(needle, haystack) {
        var length = haystack.length;
        for (var i = 0; i < length; i++) {
            if (haystack[i] == needle) return true;
        }
        return false;
    }

</script>
</body>
</html>
