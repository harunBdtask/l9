@extends('skeleton::layout')
@section("title","Price Quotation")

@section('styles')
<style>
    .tooltip-inner {
        background-color: #eee;
        color: black;
    }

    .tooltip.bs-tooltip-right .arrow:before {
        border-right-color: #eee !important;
    }

    .tooltip.bs-tooltip-left .arrow:before {
        border-right-color: #eee !important;
    }

    .tooltip.bs-tooltip-bottom .arrow:before {
        border-right-color: #eee !important;
    }

    .tooltip.bs-tooltip-top .arrow:before {
        border-right-color: #eee !important;
    }

    .tooltip-info {
        text-align: center;
        font-size: 10px;
    }
</style>
@endsection
@section('content')

<div class="padding">
    <div class="box">

        <div class="box-header">
            <div class="row">
                <div class="col-md-6">
                    <h2>{{ __('Price Quotation List') }}</h2>
                </div>
            </div>
        </div>
        <div class="box-body b-t ">
            @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])
            @include('skeleton::partials.row-number',['allExcel' => false])

            <div class="col-md-6" style="padding-left: 0px!important;">
                @permission('permission_of_price_quotation_add')
                <a href="{{url('price-quotations/main-section-form')}}" class="btn btn-sm btn-info m-b add-new-btn btn-sm">
                    <i class="glyphicon glyphicon-plus"></i> New Entry
                </a>
                @endpermission
            </div>

            <div class="col-md-6 pull-right" style="padding-right: 0px!important;">
                {!! Form::open(['url' => 'price-quotations', 'method' => 'GET']) !!}
                <div class="form-group">
                    <div class="row">
                        <div class="col-md-3" style="display: flex; align-items: center;">
                            <span style="margin-right: 5px;">From: </span>
                            <div class="input-group" style="width: 80%">
                                <input type="date" class="form-control form-control-sm" name="from_date" value="{{ request('from_date') }}" style="margin-right: -5px;">
                            </div>
                        </div>
                        <div class="col-md-3" style="display: flex; align-items: center;">
                            <span style="margin-right: 5px;">To: </span>
                            <div class="input-group w-100" style="width: 80%">
                                <input type="date" class="form-control form-control-sm" value="{{ request('to_date') }}" name="to_date">
                            </div>
                        </div>
                        <div class="col-sm-6 pull-right">
                            <form action="{{ url('price-quotations') }}" method="GET">
                                <div class="input-group">
                                    <input type="hidden" name="paginateNumber" id="paginateNumber" value={{$paginateNumber}}>
                                    <input type="text" class="form-control form-control-sm" name="search" id="search" value="{{ $search ?? '' }}" placeholder="Search">
                                    <span class="input-group-btn">
                                        <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                    </span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {!! Form::close() !!}
            </div>

            <!-- <div class="flash-message">
                @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                @if(Session::has('alert-' . $msg))
                <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                @endif
                @endforeach
            </div> -->
            <hr class="print-delete">
            <div class="table-responsive" style="min-height: 300px" id="tableOrder">
                <table class="reportTable reportTableCustom">
                    <thead>
                        <tr class="table-header">
                            @php
                            $sort = request('sort') == 'asc' ? 'desc' : 'asc';
                            $search = request('search') ?? null;
                            $extended = isset($search) ? '&search='. $search : null;
                            @endphp

                            <th>
                                <a class="btn btn-sm btn-light" href="{{  url('price-quotations?sort=' . $sort . $extended)}}">
                                    <i class="fa {{ $sort == 'asc' ? 'fa-angle-down' : 'fa-angle-up' }}">SL</i>
                                </a>
                            </th>
                            <th>Company</th>
                            <th>Buyer</th>
                            <th>Quotation Id</th>
                            <th>Inquiry Id</th>
                            <th>Product Dept.</th>
                            <th>{{ localizedFor('Style') }}</th>
                            <th>Offer Qty.</th>
                            <th>Uom</th>
                            <th>Price/DZN</th>
                            <th>Price/PCS</th>
                            <th>Season</th>
                            <th>Size Group</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="company-list">
                        @if(!$price_quotations->getCollection()->isEmpty())
                        @foreach($price_quotations->getCollection() as $price_quotation)
                        <?php
                        $tooltipInfo = "<div class='tooltip-info'><span><strong>Created by: </strong>" . $price_quotation->createdBy->screen_name . "</span><br><span><strong>Created at: </strong>" . date("F j, Y, g:i a", strtotime($price_quotation->created_at)) . "</span><br><span><strong>Updated at: </strong>" . date("F j, Y, g:i a", strtotime($price_quotation->updated_at)) . "</span></div>";
                        ?>
                        <tr data-html=true data-toggle="tooltip" data-placement="top" title="{{ $tooltipInfo }}" class="tooltip-data">
                            <td style="font-weight: bold">{{ str_pad($loop->iteration + $price_quotations->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td>{{ $price_quotation->factory->factory_short_name ?? $price_quotation->factory->factory_name}}</td>
                            <td>{{ $price_quotation->buyer->name }}</td>
                            <td>{{ $price_quotation->quotation_id }}</td>
                            <td>{{ $price_quotation->quotationInquiry->quotation_id }}</td>
                            <td>{{ $price_quotation->productDepartment->product_department }}</td>
                            <td>{{ $price_quotation->style_name }}</td>
                            <td>{{ $price_quotation->offer_qty }}</td>
                            <td>{{ $price_quotation->style_uom_name }}</td>
                            <td>{{ getCurrencySign(strtolower($price_quotation->currency->currency_name)) }}{{ $price_quotation->price_with_commn_dzn ?? 0 }}</td>
                            <td>{{ getCurrencySign(strtolower($price_quotation->currency->currency_name)) }}{{ $price_quotation->confirm_price_pc_set ?? 0 }}</td>
                            <td>{{ $price_quotation->season->season_name }}</td>
                            <td>{{ $price_quotation->season_grp }}</td>
                            <td>
                                @if($price_quotation->is_approve == 1)
                                <i class="fa fa-check-circle-o label-success-md"></i>
                                @elseif($price_quotation->step > 0 || $price_quotation->ready_to_approve == 1)
                                <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" onclick="getApproveList('{{ $price_quotation->step }}', {{ $price_quotation->buyer_id }})" data-target="#exampleModalCenter">
                                    <i class="fa  fa-circle-o-notch label-primary-md"></i>
                                </button>
                                @elseif($price_quotation->ready_to_approved != 'Yes')
                                <i class="fa fa-times label-default-md"></i>
                                @endif
                            </td>
                            <td style="padding: 0.2%;">
                                @if($price_quotation->cancel_status == 0)
                                @buyerViewPermission($price_quotation->buyer->id,'PRICE_QUOTATION_VIEW')
                                <a target="_blank" title="View" href="{{url('price-quotations/'.$price_quotation->quotation_id.'/view')}}" class="btn btn-xs btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endbuyerViewPermission

                                @buyerViewPermission($price_quotation->buyer->id,'PRICE_QUOTATION_COSTING')
                                <a target="_blank" href="{{url('price-quotations/'.$price_quotation->quotation_id.'/costing')}}" title="Costing" class="btn btn-xs btn-primary">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endbuyerViewPermission
                                @buyerViewPermission($price_quotation->buyer->id,'PRICE_QUOTATION_VIEW_AN')
                                <a target="_blank" title="View" href="{{url('price-quotations/'.$price_quotation->quotation_id.'/view-an')}}" class="btn btn-xs btn-info">
                                    <i class="fa fa-eye"></i>
                                </a>
                                @endbuyerViewPermission

                                @buyerPermission($price_quotation->buyer->id,'permission_of_price_quotation_edit')
                                <a href="{{url('price-quotations/main-section-form?quotation_id='.$price_quotation->quotation_id)}}" title="Edit" class="btn btn-xs btn-success">
                                    <i class="fa fa-edit"></i>
                                </a>
                                @endbuyerPermission
                                @buyerPermission($price_quotation->buyer->id,'permission_of_price_quotation_delete')
                                <button type="button" class="btn btn-xs btn-danger show-modal" data-toggle="modal" data-target="#confirmationModal" ui-toggle-class="flip-x" ui-target="#animate" title="Delete" data-url="{{ url('price-quotations/'.$price_quotation->id) }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                                @endbuyerPermission
                                @buyerPermission($price_quotation->buyer->id,'permission_of_price_quotation_add')
                                <a type="button" class="btn btn-xs btn-info" title="copy" href="{{ url('price-quotations/'.$price_quotation->quotation_id."/copy" )}}">
                                    <i class="fa fa-copy"></i>
                                </a>
                                @endbuyerPermission

                                <a type="button" title="Generate Style and Budget" href="{{ url('price-quotations/'.$price_quotation->quotation_id."/style-generate" )}}" class="btn btn-xs btn-primary">
                                    <i class="fa fa-book"></i>
                                </a>
                                <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#fileModal" onclick="filePreview({{ $price_quotation->attachments }})" style="padding: 2px 7px; font-size: 12px;">
                                    <i class="fa fa-file"></i>
                                </button>

                                @else
                                <small class="label bg-danger">Cancelled</small>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="14" align="center">No Data</td>
                        </tr>
                        @endif
                    </tbody>

                </table>
            </div>
            <div class="row m-t">
                <div class="col-sm-12">
                    {{ $price_quotations->appends(request()->except('page'))->links() }}
                </div>
            </div>

            {{--MULTIPLE FILE MODAL--}}
            <div class="modal fade" id="fileModal" tabindex="-1" role="dialog" aria-labelledby="fileModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close btn btn-danger btn-sm " data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title" id="imagePreviewModalLabel">Files</h5>
                        </div>

                        <div class="modal-body">
                            <div class="row append-files">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--MULTIPLE FILE MODAL--}}

            <!-- Modal -->
            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h5 class="modal-title" id="exampleModalLongTitle">Approval List</h5>
                        </div>
                        <div class="modal-body" style="max-height : 350px; overflow-x: scroll">
                            <table class="reportTable">
                                <thead>
                                    <tr style="background: #0ab4e6;">
                                        <th>Sl.</th>
                                        <th>User</th>
                                        <th>Approve Status</th>
                                    </tr>
                                </thead>
                                <tbody class="approve-list"></tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

@endsection
@section('scripts')
<script>
    $(document).ready(function() {

        $(document).on('click', '#excel_all', function() {
            let link = `{{ url('/price-quotations/excel-list-all') }}?search={{$search}}`;
            window.open(link, '_blank');
        });
        $(document).on('click', '#list_excel', function () {
            let search = $('#search').val()
            let page = {{$price_quotations->currentPage()}};

            let link = `{{ url('/price-quotations/excel-list-by-page') }}?search={{$search}}&page=${page}&paginateNumber={{$paginateNumber}}`;
            window.open(link, '_blank');
        });


    })

    $("#selectOption").change(function(){
        var selectBox = document.getElementById("selectOption");
        var selectedValue = (selectBox.value);
        if (selectedValue == -1){
            if(window.location.href.indexOf("search") != -1){
                selectedValue = {{$searchedOrders}};
            }
            else{
                selectedValue = {{$dashboardOverview["Total Price Quotation"]}};
            }
        }
        let url = new URL(window.location.href);
        url.searchParams.set('paginateNumber',parseInt(selectedValue));
        window.location.replace(url);
    });

    const approveList = jQuery('.approve-list');
    const appendFiles = jQuery('.append-files');

    function getApproveList(step, buyer_id) {
        var buyerId = buyer_id;
        var page = 'Price Quotation';

        $.ajax({
            url: `/get-approval-list/${buyerId}/${page}`,
            type: `get`,
            success: function(data) {
                approveList.empty();

                if (data.length) {
                    $.each(data, function(index, value) {
                        $priority = value.priority;
                        approveList.append(`
                            <tr>
                                <td style="padding: 4px; font-weight: bold">${index + 1}</td>
                                <td style="padding: 4px; text-align: left">${value.user}</td>
                                <td style="padding: 4px;">${value.priority <= step ? 'Approved' : 'Un-Approved'}</td>
                           </tr>
                        `);
                    })
                } else {
                    approveList.append(`
                            <tr>
                                <td colspan="3">No Data Found</td>
                            </tr>
                        `)
                }
            }
        })
    }

    function filePreview(files) {
        console.log(files);
        appendFiles.empty();
        if (files.length) {
            $.each(files, function(index, file) {
                appendFiles.append(`
                    <div class="col-md-4" id="attachment-${file.id}">
                        <div class="card text-center"
                             style="height: 70px;
                                 align-items: center;
                                 display: flex;
                                 justify-content: center;">
                            <a target="_blank"
                                href="/price-quotations/${file.price_quotation_id}/attachment/${file.id}"
                                style="color: #0ab4e6">
                                <i class="fa fa-link"></i>${file.name}
                            </a>
                                <button style="margin: 5px; text-decoration: none; border: none; background: none;" type="button"
                                        class="text-danger"
                                        onclick="deleteAttachment(${file.price_quotation_id} ,${file.id})">
                                    <i class="fa fa-times"></i>
                                </button>
                            </div>
                        </div>
                    `)
            })
        } else {
            appendFiles.append(`
                    <div class="col-md-12">
                            <div class="card text-center"
                                 style="height: 70px;
                                     align-items: center;
                                     display: flex;
                                     justify-content: center;">

                                No Files Found!
                                </div>
                        </div>
                    `)
        }
    }
    function deleteAttachment(quotationId, attachmentId) {
            $.ajax({
                type: "GET",
                url: "/price-quotations/" + quotationId + "/attachment/" + attachmentId + "/delete",
            }).done(function (response) {
                if (response.status === 'success') {
                    $('#attachment-' + attachmentId).remove();
                    toastr.success("Delete Successfully");
                }
            }).fail(function (response) {
                console.log("Something went wrong!");
            });
        }
</script>
@endsection
