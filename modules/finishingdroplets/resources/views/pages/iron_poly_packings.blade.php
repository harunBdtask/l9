@extends('finishingdroplets::layout')
@section('styles')
    <style type="text/css">
        /*
        .form-control form-control-sm {
          line-height: 1;
          min-height: 1rem !important;
        }*/
        .select2-container .select2-selection--single {
            height: 33px;
            padding-top: 3px !important;
            width: 180px;
        }
    </style>
@endsection
@section('title', 'Iron & Poly Packing')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Iron, Poly & Packing List</h2>
            </div>
            <div class="box-body b-t">
                @if(Session::has('permission_of_iron_poly_packings_add') || getRole() == 'super-admin' || getRole() == 'admin')
                    <a class="btn btn-sm btn-info m-b" href="{{ url('iron-poly-packings/create') }}">
                        <i class="glyphicon glyphicon-plus"></i> New Entry
                    </a>
                @endif
                <div class="pull-right m-b-1">
                    <form action="{{ url('/iron-poly-packings') }}" method="GET">
                        <div class="pull-left" style="margin-right: 10px;">
                            {!! Form::select('buyer_id', [], request('buyer_id') ?? null, ['class' => 'form-control form-control-sm poly-iron-buyer']) !!}
                        </div>
                        <div class="pull-left" style="margin-right: 10px;">
                            {!! Form::select('order_id', [], request('order_id') ?? null, ['class' => 'form-control form-control-sm poly-iron-booking-no', 'placeholder' => 'Select a Style']) !!}
                        </div>
                        <div class="pull-right">
                            <input type="submit" class="btn btn-sm btn-info m-b" value="Search">
                        </div>
                    </form>
                </div>

                @include('partials.response-message')
                <div class="js-response-message text-center"></div>

                <table class="reportTable">
                    <thead>
                    <tr>
                        <th>SL</th>
                        <th>Production date</th>
                        <th>Finishing Floor</th>
                        <th>Buyer</th>
                        <th>Style/Order</th>
                        <th>PO</th>
                        <th>Color</th>
                        <th>Iron Qty</th>
                        <th>Iron Rej. Qty</th>
                        <th>Poly Qty</th>
                        <th>Poly Rej. Qty</th>
                        <th>Packing Qty</th>
                        <th>Packing Rej. Qty</th>
                        <th>Reason</th>
                        <th>Remarks</th>
                        <th width="7%">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!$polies->getCollection()->isEmpty())
                        @foreach($polies->getCollection() as $poly)
                            <tr class="tr-height">
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $poly->production_date }}</td>
                                <td>{{ $poly->finishingFloor->name }}</td>
                                <td>{{ $poly->buyer->name ?? 'N/A' }}</td>
                                <td title="{{ $poly->order->style_name ?? 'N/A' }}">{{ substr($poly->order->style_name, 0, 15) ?? 'N/A' }}</td>
                                <td>{{ $poly->purchaseOrder->po_no ?? 'N/A' }}</td>
                                <td title="{{ $poly->color->name }}">{{ substr($poly->color->name, 0, 20) ?? 'N/A' }}</td>
                                <td>{{ $poly->iron_qty }}</td>
                                <td>{{ $poly->iron_rejection_qty }}</td>
                                <td>{{ $poly->poly_qty }}</td>
                                <td>{{ $poly->poly_rejection_qty }}</td>
                                <td>{{ $poly->packing_qty }}</td>
                                <td>{{ $poly->packing_rejection_qty }}</td>
                                <td>{{ $poly->reason }}</td>
                                <td>{{ $poly->remarks }}</td>
                                <td>
                                    @if(Session::has('permission_of_iron_poly_packings_edit') || getRole() == 'super-admin' || getRole() == 'admin')
                                        <a class="btn btn-xs btn-success"
                                           href="{{ url('/iron-poly-packings/'.$poly->id.'/edit/') }}"><i
                                                class="fa fa-edit"></i></a>
                                    @endif
                                    @if(Session::has('permission_of_iron_poly_packings_delete') || getRole() == 'super-admin' || getRole() == 'admin')
                                        <button type="button" value="{{ $poly->id }}"
                                                class="btn btn-xs btn-danger delete-poly-cartoon-btn">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr class="tr-height">
                            <td colspan="16" class="text-center text-danger">Not found data</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    @if($polies->total() > 15)
                        <tr>
                            <td colspan="16"
                                class="text-center">{{ $polies->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('protracker/custom.js') }}"></script>

    <script type="text/javascript">
        $(function () {
            const buyerSelectDom = $('[name="buyer_id"]');
            const orderSelectDom = $('[name="order_id"]');

            buyerSelectDom.select2({
                ajax: {
                    url: '/utility/get-buyers-for-select2-search',
                    data: function (params) {
                        return {
                            search: params.term,
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    cache: true,
                    delay: 250
                },
                placeholder: 'Select Buyer',
                allowClear: true
            });

            orderSelectDom.select2({
                ajax: {
                    url: function (params) {
                        return `/utility/get-styles-for-select2-search`
                    },
                    data: function (params) {
                        const buyerId = buyerSelectDom.val();
                        return {
                            search: params.term,
                            buyer_id: buyerId,
                        }
                    },
                    processResults: function (data, params) {
                        return {
                            results: data.results,
                            pagination: {
                                more: false
                            }
                        }
                    },
                    cache: true,
                    delay: 250
                },
                placeholder: 'Select Style',
                allowClear: true
            });

            $(document).on('click', '.delete-poly-cartoon-btn', function () {
                if (confirm('Do you want to delete?') == true) {
                    let current = $(this);
                    let id = current.val();
                    if (id) {
                        $.ajax({
                            type: 'GET',
                            url: '/delete-iron-poly-packings/' + id,
                            success: function (response) {
                                if (response == 200) {
                                    current.parents('tr').remove();
                                    $('.js-response-message').html(getMessage(D_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000);
                                } else {
                                    $('.js-response-message').html(getMessage(D_FAIL, 'danger')).fadeIn().delay(2000).fadeOut(2000);
                                }
                            }
                        });
                    }
                }
            });
        });
    </script>
@endsection
