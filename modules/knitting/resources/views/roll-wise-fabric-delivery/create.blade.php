@extends('skeleton::layout')
@section('title','Roll Wise Fabric Delivery Challan')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Roll Wise Fabric Delivery Challan
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['url' => url('/knitting/roll-wise-fabric-delivery/search-deliverable-rolls'),'method' => "GET", 'id' => 'roll-search-form']) !!}
                    <div class="col-md-4 col-md-offset-4">
                        <div class="form-group">
                            <label><strong>Roll Barcode</strong></label>
                            {!! Form::text('roll_no', null, ['class' => 'form-control form-control-sm ', 'id' => 'roll_no', 'placeholder' => 'Scan here']) !!}
                        </div>
                    </div>
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="table-responsive">
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <th style="width: 10%;">Company Name</th>
                                    <th style="width: 10%;">Buyer</th>
                                    <th style="width: 10%;">Program No</th>
                                    <th style="width: 10%;">Knit Card No</th>
                                    <th style="width: 10%;">Booking Type</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <select name="factory_id" class="form-control form-control-sm select2-input"
                                                id="factory_id">
                                            @foreach($factories as $key=>$factory)
                                                <option value="{{ $factory->id }}">{{ $factory->factory_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        {!! Form::select('buyer_id', [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'buyer_id', 'placeholder' => 'Select']) !!}
                                    </td>
                                    <td>
                                        {!! Form::select('program_id', [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'program_id', 'placeholder' => 'Select']) !!}
                                    </td>
                                    <td>
                                        {!! Form::select('knit_card_id', [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'knit_card_id', 'placeholder' => 'Select']) !!}
                                    </td>
                                    <td>
                                        <select
                                            name="type"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            <option @if(request()->get('type') == 'main') selected @endif value="main">Main</option>
                                            <option @if(request()->get('type') == 'short') selected @endif value="short">Short</option>
                                            <option @if(request()->get('type') == 'sample') selected @endif value="sample">Sample</option>
                                        </select>
                                    </td>
                                    <td style="width: 10%">
                                        <button type="submit" class="btn btn-info btn-xs">
                                            <i class="fa fa-search"></i> Search
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <hr>
                <div class="form-group row">
                    <div class="col-sm-3">
                        {!! Form::label('challan_no', "Challan No", ['class' => 'text-sm']) !!}
                        {!! Form::text('challan_no_fake', $challan_no, ['class' => 'form-control form-control-sm', 'disabled']) !!}
                        {!! Form::hidden('challan_no', $challan_no) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::label('challan_date', "Challan Date", ['class' => 'text-sm']) !!}
                        {!! Form::date('challan_date', $challan_date ?? date('Y-m-d'), ['class' => 'form-control form-control-sm']) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::label('destination', "Destination", ['class' => 'text-sm']) !!}
                        {!! Form::text('destination', $destination ?? null, ['class' => 'form-control form-control-sm']) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::label('driver_name', "Driver Name", ['class' => 'text-sm']) !!}
                        {!! Form::text('driver_name', $driver_name ?? null, ['class' => 'form-control form-control-sm']) !!}
                    </div>
                </div>

                <div class="form-group row m-t">
                    <div class="col-sm-3">
                        {!! Form::label('vehicle_no', "Vehicle No", ['class' => 'text-sm']) !!}
                        {!! Form::text('vehicle_no', $vehicle_no ?? null, ['class' => 'form-control form-control-sm']) !!}
                    </div>
                    <div class="col-sm-3">
                        {!! Form::label('remarks', "Remarks", ['class' => 'text-sm']) !!}
                        {!! Form::text('remarks', $remarks ?? null, ['class' => 'form-control form-control-sm']) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 flash-message">
                        @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                            @if(Session::has('alert-' . $msg))
                                <p class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</p>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive parentTableFixed" id="searched-rolls-container">
                            <table class="reportTable fixTable">
                                <thead>
                                <tr class="blue-200">
                                    <th>Knitting Source</th>
                                    <th>Booking Type</th>
                                    <th>Buyer</th>
                                    <th>Style</th>
                                    <th>Booking No</th>
                                    <th>Body Part</th>
                                    <th>Color Type</th>
                                    <th>Fabrication</th>
                                    <th>Color</th>
                                    <th>Prog. No</th>
                                    <th>Knit Card No</th>
                                    <th>Prod. Qty</th>
                                    <th>Pcs Prod. Qty</th>
                                    <th>Roll Barcode</th>
                                    <th>Roll Seq.</th>
                                    <th>
                                        @permission('permission_of_roll_wise_fabric_delivery_add')
                                        <input name="select_all" type="checkbox" id="select_all" class="select_all p-2">
                                        @endpermission
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="roll-search-results">
                                <tr>
                                    <th colspan="16">Search to Deliver Rolls</th>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class=col-md-12>
                        @permission('permission_of_roll_wise_fabric_delivery_add')
                        <button type="button" class="btn btn-info btn-sm pull-right" id="process-rolls-delivery-btn">
                            Process
                        </button>
                        @endpermission
                    </div>
                </div>
            </div>
        </div>
        <div class="box box-primary">
            <div class="box-header">
                <h2 class="text-center">
                    Rolls To be Challaned
                </h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="table-responsive parentTableFixed" id="challaned-rolls-container">
                            <table class="reportTable fixTable">
                                <thead>
                                <tr class="green-200">
                                    <th>Company Name</th>
                                    <th>Book. Company</th>
                                    <th>Knitting Source</th>
                                    <th>Buyer</th>
                                    <th>Style</th>
                                    <th>Booking No</th>
                                    <th>Body Part</th>
                                    <th>Color Type</th>
                                    <th>Fabrication</th>
                                    <th>Color</th>
                                    <th>Prog. No</th>
                                    <th>Prod. Qty</th>
                                    <th>Pcs Prod. Qty</th>
                                    <th>UOM</th>
                                    <th>Roll Barcode</th>
                                    <th>Roll Sequence</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody class="roll-to-challan">
                                @if(isset($challan_list))
                                    @foreach($challan_list as $challanRoll)
                                        @php
                                            $rollData = $challanRoll->knitProgramRoll;
                                            $book_company = $challanRoll->knittingProgram ? ($challanRoll->knittingProgram->knittingParty->factory_name ?? $challanRoll->knittingProgram->knittingParty->name): '';
                                            $scanable_barcode = $rollData->id ? str_pad($rollData->id, 9, '0', STR_PAD_LEFT) : '';
                                        @endphp
                                        <tr class="text-left">
                                            <td>{{ $rollData->factory->factory_name ?? '' }}</td>
                                            <td>{{ $book_company }}</td>
                                            <td>{{ $challanRoll->knittingProgram->knitting_source_value ?? '' }}</td>
                                            <td>{{ $challanRoll->planningInfo->buyer_name ?? '' }}</td>
                                            <td>{{ $challanRoll->planningInfo->style_name ?? '' }}</td>
                                            <td>{{ $challanRoll->planningInfo->booking_no ?? '' }}</td>
                                            <td>{{ $challanRoll->planningInfo->bodyPart->name ?? '' }}</td>
                                            <td>{{ $challanRoll->planningInfo->colorType->color_types ?? '' }}</td>
                                            <td style="width: 7%">{{ $challanRoll->planningInfo->fabric_description ?? '' }}</td>
                                            <td style="width: 15%;">{{ $challanRoll->planningInfo->item_color ?? '' }}</td>
                                            <td>{{ $challanRoll->knittingProgram->program_no ?? '' }}</td>
                                            <td style="text-align: right">{{ $rollData->qc_roll_weight ?? $rollData->roll_weight ?? '' }}</td>
                                            <td style="text-align: right">{{ $rollData->production_pcs_total ?? '' }}</td>
                                            <td>KG</td>
                                            <td style="width: 9%">&nbsp;<?php echo DNS1D::getBarcodeSVG(($scanable_barcode ?? '1234'), "C128A", 1, 15, '', false); ?> &nbsp;</td>
                                            <td>{{ $scanable_barcode }}</td>
                                            <td style="text-align: center">
                                                @permission('permission_of_roll_wise_fabric_delivery_delete')
                                                <button type="button"
                                                        class="btn btn-danger btn-xs individual-challaned-roll"
                                                        data-id="{{ $challanRoll->id }}"><i class="fa fa-trash"></i>
                                                </button>
                                                @endpermission
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <th class="text-center" colspan="18">No Data Found</th>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class=col-md-12>
                        <button type="button" class="btn btn-success btn-sm pull-right"
                                id="make-delivery-challan-btn">{{ $form_mode }} Challan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var factories = [];
        const factoryDropdown = $('#roll-search-form select[name="factory_id"]');
        const buyerDropdown = $('#roll-search-form select[name="buyer_id"]');
        const programNoDropdown = $('#roll-search-form select[name="program_id"]');
        const styleNameDropdown = $('#roll-search-form select[name="style_name"]');
        const uniqueIdDropdown = $('#roll-search-form select[name="unique_id"]');
        const poNoDropdown = $('#roll-search-form select[name="po_no"]');
        const bookingNoDropdown = $('#roll-search-form select[name="booking_no"]');
        const rollSearchResultDom = $('.roll-search-results');
        const rollToChallanDom = $('.roll-to-challan');
        const createChallanBtnDom = $('#make-delivery-challan-btn');
        var deliveryRollsData = [];
        $(function () {
            getBuyer();

            factoryDropdown.select2({
                ajax: {
                    url: '/factories/select2-search',
                    data: function (params) {
                        return {
                            search: params.term,
                        }
                    },
                    processResults: function (response, params) {
                        setFactories(response.data);
                        return {
                            results: response.data,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    cache: true,
                    delay: 250
                },
                placeholder: 'Select Factory',
                allowClear: true
            });

            // buyerDropdown.select2({
            //     ajax: {
            //         url: '/fetch-buyers',
            //         data: function (params) {
            //             return {
            //                 search: params.term,
            //             }
            //         },
            //         processResults: function (response, params) {
            //             return {
            //                 results: response,
            //                 pagination: {
            //                     more: false
            //                 }
            //             }
            //         },
            //         cache: true,
            //         delay: 250
            //     },
            //     placeholder: 'Select Buyer',
            //     allowClear: true
            // });
            //
            // programNoDropdown.select2({
            //     ajax: {
            //         url: '/knitting/api/v1/program',
            //         data: function (params) {
            //             return {
            //                 search: params.term,
            //             }
            //         },
            //         processResults: function (data, params) {
            //             return {
            //                 results: data,
            //                 pagination: {
            //                     more: false
            //                 }
            //             }
            //         },
            //         cache: true,
            //         delay: 250
            //     },
            //     placeholder: 'Select Program No',
            //     allowClear: true
            // });

            // styleNameDropdown.select2({
            //     ajax: {
            //         url: '/knitting/api/v1/plan-info/style-search',
            //         data: function (params) {
            //             return {
            //                 search: params.term,
            //             }
            //         },
            //         processResults: function (response, params) {
            //             return {
            //                 results: response.data,
            //                 pagination: {
            //                     more: false
            //                 }
            //             }
            //         },
            //         cache: true,
            //         delay: 250
            //     },
            //     placeholder: 'Select Style',
            //     allowClear: true
            // });
            //
            // uniqueIdDropdown.select2({
            //     ajax: {
            //         url: '/knitting/api/v1/plan-info/unique-id-search',
            //         data: function (params) {
            //             return {
            //                 search: params.term,
            //             }
            //         },
            //         processResults: function (response, params) {
            //             return {
            //                 results: response.data,
            //                 pagination: {
            //                     more: false
            //                 }
            //             }
            //         },
            //         cache: true,
            //         delay: 250
            //     },
            //     placeholder: 'Select Unique Id',
            //     allowClear: true
            // });
            //
            // poNoDropdown.select2({
            //     ajax: {
            //         url: '/knitting/api/v1/plan-info/po-search',
            //         data: function (params) {
            //             return {
            //                 search: params.term,
            //             }
            //         },
            //         processResults: function (response, params) {
            //             return {
            //                 results: response.data,
            //                 pagination: {
            //                     more: false
            //                 }
            //             }
            //         },
            //         cache: true,
            //         delay: 250
            //     },
            //     placeholder: 'Select PO',
            //     allowClear: true
            // });
            //
            // bookingNoDropdown.select2({
            //     ajax: {
            //         url: '/knitting/api/v1/plan-info/booking-no-search',
            //         data: function (params) {
            //             return {
            //                 search: params.term,
            //             }
            //         },
            //         processResults: function (response, params) {
            //             return {
            //                 results: response.data,
            //                 pagination: {
            //                     more: false
            //                 }
            //             }
            //         },
            //         cache: true,
            //         delay: 250
            //     },
            //     placeholder: 'Select Booking No',
            //     allowClear: true
            // });

            function loadMoreData(page) {
                let paginateTrHtml = document.querySelector('.paginate-tr');
                var form = $('#roll-search-form');
                var data = form.serialize();
                data += `&page=${page}`
                loadNow(5);
                $.ajax({
                    url: form.attr('action'),
                    type: form.attr('method'),
                    data: data,
                    beforeSend: function () {
                        paginateTrHtml.remove();
                    }
                }).done(function (response) {
                    if (response.status == 200) {
                        rollSearchResultDom.append(response.view);
                    }

                    if (response.status == 500) {
                        alert(response.message);
                    }
                }).fail(function (response) {
                    alert("Something went wrong! Please reload this page!");
                    console.log(response);
                });
            }

            $('#searched-rolls-container').scroll(function () {
                let currentPageDom = document.querySelector('[name="current_page"]');
                let lastPageDom = document.querySelector('[name="last_page"]');
                if (currentPageDom && lastPageDom) {
                    let current_page = Number(currentPageDom.value);
                    let last_page = Number(lastPageDom.value);
                    if (current_page < last_page) {
                        if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
                            let page = parseInt(current_page) + 1;
                            loadMoreData(page);
                        }
                    }
                }
            });

            createChallanBtnDom.click((e) => {
                e.preventDefault();
                let challan_no = $('[name="challan_no"]').val();
                let challan_date = $('[name="challan_date"]').val();
                let destination = $('[name="destination"]').val();
                let driver_name = $('[name="driver_name"]').val();
                let vehicle_no = $('[name="vehicle_no"]').val();
                let remarks = $('[name="remarks"]').val();
                let confirmCheck = confirm("Are you sure to submit challan?");
                if (challan_no && confirmCheck) {
                    let data = {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        challan_no: challan_no,
                        challan_date: challan_date,
                        destination: destination,
                        driver_name: driver_name,
                        vehicle_no: vehicle_no,
                        remarks: remarks,
                    }
                    loadNow(5);
                    $.ajax({
                        type: 'POST',
                        url: '/knitting/roll-wise-fabric-delivery/' + challan_no + '/save',
                        data: data
                    }).done(function (response) {
                        if (response.status == 200) {
                            alert(response.message);
                            gotoListPage();
                        }

                        if (response.status == 500) {
                            alert(response.message);
                        }
                    }).fail(function () {
                        alert('Something went wrong!')
                    });
                }
            });
        });

        function gotoListPage() {
            window.location.href = '/knitting/roll-wise-fabric-delivery/';
        }

        function setFactories(data) {
            factories = data;
        }

        $(document).on('change', '#factory_id', function () {
            let factory_id = $(this).val();
            $.each(factories, function (key, item) {
                if (item.id == factory_id) {
                    $("#factory_location").text(item.factory_address);
                }
            });

            getBuyer();
        })

        $(document).on('change', '#buyer_id', function () {
            const element = $('#program_id');
            const buyer_id = $(this).val();
            if(!buyer_id) return;
            $.ajax({
                method: 'GET',
                url: `/knitting/api/v1/program?buyer_id=${buyer_id}`,
                success(response) {
                    element.empty().append(`<option value="">Select</option>`).val('').trigger('change');
                    $.each(response, function (index) {
                        element.append(`<option value="${response[index].id}">${response[index].text}</option>`)
                    })
                }
            })
        })

        $(document).on('change', '#program_id', function () {
            const element = $('#knit_card_id');
            const program_id = $(this).val();
            if(!program_id) return;
            $.ajax({
                method: 'GET',
                url: `/knitting/api/v1/get-knit-card-no?program_id=${program_id}`,
                success(response) {
                    element.empty().append(`<option value="">Select</option>`).val('').trigger('change');
                    $.each(response, function (index) {
                        element.append(`<option value="${response[index].id}">${response[index].text}</option>`)
                    })
                }
            })
        })

        $(document).on('submit', '#roll-search-form', function (e) {
            e.preventDefault();
            loadNow(5);
            var form = $(this);
            $('.text-danger').html('');
            rollSearchResultDom.empty();
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize()
            }).done(function (response) {
                if (response.status == 200) {
                    rollSearchResultDom.append(response.view);
                }

                if (response.status == 500) {
                    alert(response.message);
                }
            }).fail(function () {
                alert('Something went wrong!')
            });
        });

        $(document).on('change', '.roll-data', function (e) {
            selectRow($(this), e.target.checked);
        });

        $(document).on('click', '#process-rolls-delivery-btn', function (e) {
            e.preventDefault();
            if (deliveryRollsData && deliveryRollsData.length > 0) {
                rollSearchResultDom.empty();
                $(".select_all").prop("checked", false);
                processRollsToDelivery()
                // for (var index = 0; index < deliveryRollsData.length; index++) {
                //     if(!deliveryRollsData[index]['submit_status']) {
                //         deliveryRollsData[index]['submit_status'] = true;
                //         processRollsToDelivery(deliveryRollsData[index]);
                //     }
                // }
            } else {
                alert("Please select rolls first");
            }
        });

        function selectRow(e, isChecked) {
            let challan_no = $('[name="challan_no"]').val();
            let knitting_program_roll_id = e.attr('data-id');
            let plan_info_id = e.attr('data-plan-info-id');
            let knitting_program_id = e.attr('data-program-id');
            let knit_card_id = e.attr('data-knit-card-id');
            let selectedStatus = e.attr('data-selected');
            if (!isChecked) {
                let tempData = [];
                for (let index = 0; index < deliveryRollsData.length; index++) {
                    if (deliveryRollsData[index].knitting_program_roll_id != knitting_program_roll_id) {
                        tempData.push(deliveryRollsData[index]);
                    }
                }
                deliveryRollsData = tempData;
            } else {
                let deliveryData = {
                    'challan_no': challan_no,
                    'plan_info_id': plan_info_id,
                    'knit_card_id': knit_card_id,
                    'knitting_program_id': knitting_program_id,
                    'knitting_program_roll_id': knitting_program_roll_id,
                };
                deliveryRollsData.push(deliveryData);
            }

            e.attr('data-selected', isChecked ? 1 : 0);
            e.parent().parent().css('background-color', isChecked ? "#ffc" : "transparent");
        }

        function processRollsToDelivery() {
            loadNow(5);
            $.ajax({
                type: 'POST',
                url: '/knitting/roll-wise-fabric-delivery/detail/store',
                data: {
                    data: deliveryRollsData,
                    _token: $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function (response) {
                if (response.status == 200) {
                    rollToChallanDom.empty().append(response.view);
                    console.log(response.message);
                }

                if (response.status == 500) {
                    console.log(response.message);
                }
            }).fail(function () {
                alert('Something went wrong!')
            });
        }

        function getBuyer() {
            const element = $('#buyer_id');
            $.ajax({
                method: 'GET',
                url: `/fetch-buyers?factory_id=${$('#factory_id').val()}`,
                success(response) {
                    element.empty().append(`<option value="">Select</option>`).val('').trigger('change');
                    $.each(response, function (index) {
                        element.append(`<option value="${response[index].id}">${response[index].text}</option>`)
                    })
                }
            })
        }

        $(document).on('click', 'button.individual-challaned-roll', function (e) {
            e.preventDefault();
            let id = $(this).attr('data-id');
            let thisHtml = $(this);
            thisHtml.parents('tr').css('background-color', '#ff8a65');
            let confirmReq = confirm("Are you sure?");

            if (confirmReq && id) {
                loadNow(3);
                $.ajax({
                    type: 'DELETE',
                    url: '/knitting/roll-wise-fabric-delivery/detail/' + id + '/delete',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    }
                }).done(function (response) {
                    if (response.status == 200) {
                        thisHtml.parents('tr').remove();
                        alert(response.message);
                    }

                    if (response.status == 500) {
                        console.log(response.message);
                    }
                }).fail(function () {
                    alert('Something went wrong!')
                });
            } else {
                thisHtml.parents('tr').css('background-color', 'transparent');
            }
        });

        $(document).on('click', '.select_all', function (e) {
            if (e.target.checked) {
                $("input[type='checkbox']").prop("checked", true);
            } else {
                $("input[type='checkbox']").prop("checked", false);
            }
            $('.roll-data').each(function () {
                selectRow($(this), e.target.checked);
            });
        });
    </script>
@endsection
