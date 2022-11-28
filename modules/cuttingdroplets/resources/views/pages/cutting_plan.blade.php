<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <title>goRMG | An Ultimate ERP Solutions For Garments</title>
  <meta name="description" content="Admin, Dashboard, Bootstrap, Bootstrap 4, Angular, AngularJS" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimal-ui" />
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


  <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler.js') }}' type="text/javascript" charset="utf-8"></script>
  <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler_timeline.js') }}' type="text/javascript" charset="utf-8">
  </script>
  <script src='{{ asset('dhtmlx_scheduler/js/dhtmlxscheduler_tooltip.js') }}' type="text/javascript" charset="utf-8">
  </script>

  <link rel='stylesheet' type='text/css' href='{{ asset('dhtmlx_scheduler/css/dhtmlxscheduler_material.css') }}'>

  <!-- style -->
  <link rel="stylesheet" href="{{ asset('flatkit/assets/animate.css/animate.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('flatkit/assets/glyphicons/glyphicons.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('flatkit/assets/font-awesome/css/font-awesome.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('flatkit/assets/material-design-icons/material-design-icons.css') }}"
    type="text/css" />

  <link rel="stylesheet" href="{{ asset('flatkit/assets/bootstrap/dist/css/bootstrap.min.css') }}" type="text/css" />
  <link rel="stylesheet" href="{{ asset('flatkit/assets/animate.css/animate.min.css') }}" type="text/css" />
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i&display=swap"
    rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('css/cutting-plan.css') }}" type="text/css" />

  <style type="text/css">

  </style>

</head>

<body class="body" onload="init();">
  <div class="header">
    <div class="sub-header">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-4">
            <a href="{{ url('/') }}" class="btn btn-sm logo">
              <img src="{{ asset('flatkit/assets/images/gormg_fav_ico.png') }}" alt="cut plan board"
                style="height: 30px;">
              <span class="p app-header" style="font-weight: 400; word-spacing: 0.2em; letter-spacing: 1px">CUT <span
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
          <div class="col-sm-6">
            {!! Form::open(['id' => 'cutting_plan_form']) !!}
            @csrf
            <div class="form-group">
              <div class="row mb plan-form">
                <div class="col-md-4">
                  {!! Form::date('plan_date', $date ?? null, ['class' => 'form-control form-control-sm', 'id' =>
                  'plan_date']) !!}
                </div>
                <div class="col-md-4">
                  {!! Form::select('factory_id', $factories ?? [], null, ['class' => 'form-control form-control-sm',
                  'id' => 'factory_id', 'placeholder' => 'Select Factory']) !!}
                </div>
                <div class="col-md-4">
                  {!! Form::select('cutting_floor_id', $cutting_floors ?? [], null, ['class' => 'form-control
                  form-control-sm', 'id' => 'cutting_floor_id', 'placeholder' => 'Select Floor']) !!}
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
    var plan_date = $(document).find('#plan_date').val();
    var floor_id = '0';
    var date = '{{ date('Y,m,d', strtotime($date)) }}';
    var booking_no_options = scheduler.serverList("booking_no_list");
    var permission;
    var isLocked;

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

        scheduler.createTimelineView({
            name: "timeline",
            x_unit: "minute",
            x_date: "%H:%i",
            x_step: 60,
            x_size: 24,
            x_start: 0,
            x_length: 24,
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

        scheduler.config.buttons_right = ["dhx_save_btn", "dhx_cancel_btn", "copy_button"];
        scheduler.config.buttons_left = ["dhx_delete_btn"];
        scheduler.locale.labels["copy_button"] = "Duplicate";

        scheduler.locale.labels.section_description = "Plan Description";
        scheduler.locale.labels.section_buyerselect = "Buyer";
        scheduler.locale.labels.section_bookingselect = "Style";
        scheduler.locale.labels.section_purchaseorderselect = "PO";
        scheduler.locale.labels.section_colorselect = "Color";
        scheduler.locale.labels.section_planqty = "Plan Qty";
        scheduler.locale.labels.section_noofmarker = "No of Marker";
        scheduler.locale.labels.section_rating = "Rating(%)";
        scheduler.locale.labels.section_smv = "SMV";
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
                name: "bookingselect",
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
                    getColor(this.value);
                },
            },
            {
                name: "colorselect",
                height: 20,
                map_to: "color_id",
                type: "select",
                options: [],
            },
            {name: "description", height: 20, map_to: "plan_text", type: "textarea", focus: true},
            {name: "planqty", height: 20, map_to: "plan_qty", type: "textarea"},
            {name: "noofmarker", height: 20, map_to: "no_of_marker", type: "textarea"},
            {name: "rating", height: 20, map_to: "rating", type: "textarea"},
            {name: "smv", height: 20, map_to: "smv", type: "textarea"},
            {name: "boardcolor", height: 20, map_to: "board_color", type: "boardcolor", default_value: "#0288d1"},
            {name: "time", height: 72, type: "time", map_to: "auto"}
        ];
        scheduler.init('scheduler_here', new Date(date), "timeline");
    }

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

    function planDateChangeHandler() {
        date = $(this).val();
        // For reseting sections
        plan_date = date;
        $('#cutting_floor_id').val('');
        $('#factory_id').val('');
        init();
        floor_id = '0';
        scheduler.load("/api/cutting-plans/" + floor_id + "/" + plan_date + "/" + user_id, "json");
    }

    // For Date Change recall the scheduler
    $(document).on('change', '#plan_date', planDateChangeHandler);

    // Get Cutting Floor For selected factory
    $(document).on('change', '#factory_id', function () {
        date = $('#plan_date').val();
        // For reseting sections
        floor_id = '0';
        plan_date = date;
        $('#cutting_floor_id').val('');
        scheduler.clearAll();
        scheduler.parse([]);
        let factoryID = $(this).val();
        if (factoryID) {
            getBuyer(factoryID);
            getCuttingFloor(factoryID);
            scheduler.load("/api/cutting-plans/" + floor_id + "/" + plan_date + "/" + user_id, "json");
        }
    });

    // For change Floor
    $('#cutting_floor_id').on('change', function () {
        var cutting_floor_id = $(this).val();
        plan_date = $('#plan_date').val();
        floor_id = cutting_floor_id;
        $('#cutting_table_id').empty();
        scheduler.clearAll();
        scheduler.parse([]);
        if (cutting_floor_id) {
            scheduler.load("/api/cutting-plans/" + floor_id + "/" + plan_date + "/" + user_id, "json");
            checkIfUserHavePermission(cutting_floor_id, user_id);
            checkPlanBoardIsLocked(cutting_floor_id)
        }
    });

    // Check Permission
    function checkIfUserHavePermission(cutting_floor_id, user_id) {
        var permission_data;
        $.ajax({
            type: 'GET',
            url: '/get-cutting-plan-user-permission/' + cutting_floor_id + '/' + user_id,
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
    function checkPlanBoardIsLocked(cutting_floor_id) {
        $.ajax({
            type: 'GET',
            url: '/check-cutting-plan-board-lock/' + cutting_floor_id,
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
    var dp = new dataProcessor("/api/cutting-plans/" + floor_id + "/" + plan_date + "/" + user_id);
    dp.init(scheduler);
    dp.setTransactionMode("REST");

    // After Update Data
    dp.attachEvent("onAfterUpdateFinish", function () {
        scheduler.clearAll();
        scheduler.load("/api/cutting-plans/" + floor_id + "/" + plan_date + "/" + user_id, "json");
    });

    // Before opening lightbox set transition and fetch data for edit
    scheduler.attachEvent("onBeforeLightbox", function (id) {
        if (permission > 0) {
            if (!isLocked) {
                getCuttingPlanData(id);
                $(document).find('.copy_button')[0].setAttribute('event-id', id);
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
    scheduler.attachEvent("onBeforeDrag", function (id, mode, e){
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
        $(document).find('.copy_button_set')[0].style.display = 'block';
        scheduler.showLightbox(id);
    });
    var event_step = 60;
    scheduler.attachEvent("onEmptyClick", function (date, native_event) {
        if (permission > 0) {
            if (!isLocked) {
                $(document).find('.copy_button_set')[0].style.display = 'none';
                var section_id = native_event.path[3].getAttribute('data-section-id');
                var fixed_date = fix_date(date);
                scheduler.addEventNow({
                    section_id: section_id,
                    start_date: fixed_date
                }, scheduler.date.add(fixed_date, event_step, "minute"));
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
                scheduler.formSection('bookingselect').control.options.length = 0;
                scheduler.formSection('purchaseorderselect').control.options.length = 0;
                scheduler.formSection('colorselect').control.options.length = 0;
                let b = 0;
                scheduler.formSection('bookingselect').control[b] = new Option('Select Style', '');
                $.each(response.booking_no_list, function (key, val) {
                    b++;
                    scheduler.formSection('bookingselect').control[b] = new Option(val.label, val.key);
                });
                let p = 0;
                scheduler.formSection('purchaseorderselect').control[p] = new Option('Select PO', '');
                $.each(response.po_list, function (key, val) {
                    p++;
                    scheduler.formSection('purchaseorderselect').control[p] = new Option(val.label, val.key);
                });
                let c = 0;
                scheduler.formSection('colorselect').control[c] = new Option('Select Color', '');
                $.each(response.color_list, function (key, val) {
                    c++;
                    scheduler.formSection('colorselect').control[c] = new Option(val.label, val.key);
                });
                scheduler.formSection('bookingselect').setValue(response.order_id);
                scheduler.formSection('purchaseorderselect').setValue(response.purchase_order_id);
                scheduler.formSection('colorselect').setValue(response.color_id);
            }
        });
    }

    // Get Cutting Floor
    function getCuttingFloor(factoryID) {
        $.ajax({
            type: 'GET',
            url: '/get-cutting-floors-for-factory/' + factoryID,
            success: function (response) {
                var cuttingFloorDropdown = '<option value="">Select Floor</option>';
                if (Object.keys(response).length > 0) {
                    $.each(response, function (index, val) {
                        cuttingFloorDropdown += '<option value="' + index + '">' + val + '</option>';
                    });
                    $('#cutting_floor_id').html(cuttingFloorDropdown);
                }
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
        if (buyer_id) {
            $.ajax({
                type: "GET",
                url: "/get-order-list-for-cut-plan/" + buyer_id,
                success: function (response) {
                    if (response.status == 200 && response.orders != null) {
                        let p = 0;
                        scheduler.formSection('bookingselect').control.options.length = 0;
                        scheduler.formSection('purchaseorderselect').control.options.length = 0;
                        scheduler.formSection('colorselect').control.options.length = 0;

                        scheduler.formSection('bookingselect').control[p] = new Option('Select Style', '');
                        $.each(response.orders, function (key, order) {
                            var style_name = order.style_name ? order.style_name : 'N/A';
                            p++;
                            scheduler.formSection('bookingselect').control[p] = new Option(style_name, order.id);
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
        var booking_no;
        if (order_id) {
            $.each(booking_no_options, function (index, value) {
                if (value.key == order_id) {
                    booking_no = value.label;
                }
            });
            scheduler.formSection('description').setValue(buyer + '/' + booking_no);
            $.ajax({
                type: 'GET',
                url: '/get-purchase-orders-for-cutting-plan/' + order_id,
                success: function (response) {
                    if (Object.keys(response).length > 0) {
                        let p = 0;
                        scheduler.formSection('purchaseorderselect').control.options.length = 0;
                        scheduler.formSection('purchaseorderselect').control[p] = new Option('Select Purchase Order', '');
                        scheduler.formSection('colorselect').control.options.length = 0;
                        $.each(response, function (index, val) {
                            p++;
                            scheduler.formSection('purchaseorderselect').control[p] = new Option(val, index);
                        });
                    }
                }
            });
        }
    }

    // Get Color
    function getColor(purchase_order_id) {
        if (purchase_order_id) {
            $.ajax({
                type: 'GET',
                url: '/utility/get-colors/' + purchase_order_id,
                success: function (response) {
                    if (Object.keys(response).length > 0) {
                        let p = 0;
                        scheduler.formSection('colorselect').control.options.length = 0;
                        scheduler.formSection('colorselect').control[p] = new Option('Select Color', '');
                        $.each(response, function (index, val) {
                            p++;
                            scheduler.formSection('colorselect').control[p] = new Option(val, index);
                        });
                    }
                }
            });
        }

    }

    // Validate Add form
    scheduler.attachEvent("onEventSave", function (id, ev) {
        if (!ev.plan_text) {
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
        }
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
        if (!ev.plan_text) {
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
    scheduler.attachEvent("onBeforeViewChange", function (old_mode, old_date, mode, date) {
        var old_plan_date = $(document).find('#plan_date').val();
        var new_plan_date = getDate(date);
        $(document).find('#plan_date').val(new_plan_date);
        plan_date = new_plan_date;
        floor_id = $('#cutting_floor_id').val();
        scheduler.templates.event_bar_text = function (start, end, event) {
            return event.text;
        };
        if (old_plan_date !== new_plan_date) {
            if (floor_id) {
                scheduler.load("/api/cutting-plans/" + floor_id + "/" + plan_date + "/" + user_id + "?mode=" + mode, "json");
            }
        } else if (mode == 'week' || mode == 'month') {
            scheduler.templates.event_bar_text = function (start, end, event) {
                return event.plan_text;
            };
            scheduler.load("/api/cutting-plans/" + floor_id + "/" + plan_date + "/" + user_id + "?mode=" + mode, "json");
        }
        return true;
    });

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
        var cutting_floor_id = $('#cutting_floor_id').val();
        var is_locked = 1;
        var formData = {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'cutting_floor_id' : cutting_floor_id,
            'user_id' : user_id,
            'is_locked' : is_locked
        };
        if (cutting_floor_id) {
            $.ajax({
                type: 'GET',
                url: '/update-cutting-plan-board-lock-info/',
                data : formData
            }).done(function (response) {
                setPlanBoardLocked(response, thisDom, 'unlock-btn', is_locked);
            });
        }
    });


    $(document).on('click', '.unlock-btn', function () {
        var thisDom = $(this);
        var cutting_floor_id = $('#cutting_floor_id').val();
        var is_locked = 0;
        var formData = {
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'cutting_floor_id' : cutting_floor_id,
            'user_id' : user_id,
            'is_locked' : is_locked
        };
        if (cutting_floor_id) {
            $.ajax({
                type: 'GET',
                url: '/update-cutting-plan-board-lock-info/',
                data : formData
            }).done(function (response) {
                setPlanBoardLocked(response, thisDom, 'lock-btn', is_locked);
            });
        }
    });

    function setPlanBoardLocked(response, thisDom, otherBtn, is_locked) {
        if (response.type == 'success') {
            thisDom.addClass('hide');
            $('.'+otherBtn+'').removeClass('hide');
            isLocked = is_locked;
        } else if (response.type == 'danger') {
            permissionErrorMessage();
        }
    }

  </script>
</body>

</html>