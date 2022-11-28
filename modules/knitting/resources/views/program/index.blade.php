@extends('skeleton::layout')
@section('title','Planning Info Entry List')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>
                    Planning Info Entry List
                </h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <form action="{{url('knitting/planning-info-entry')}}" method="GET">
                            <table class="reportTable">
                                <thead>
                                <tr>
                                    <th nowrap class="mx-2" style="width: 15%">Factory</th>
                                    <th nowrap class="mx-2" style="width: 15%">Buyer</th>
                                    <th nowrap class="mx-2" style="width: 15%">Unique ID</th>
                                    <th nowrap class="mx-2" style="width: 15%">Style</th>
                                    <th nowrap class="mx-2" style="width: 15%">Within Group</th>
                                    <th nowrap class="mx-2" style="width: 15%">Booking No</th>
                                    <th nowrap class="mx-2" style="width: 15%">Booking Type</th>
                                    <th nowrap class="mx-2">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <select name="factory_id" class="form-control form-control-sm select2-input"
                                                id="factory_id">
                                            <option value="">Select Company</option>
                                            @foreach($companies as $key=>$factory)
                                                <option value="{{ $factory->id }}" @if($factory->id == request('factory_id')) selected @endif>
                                                    {{ $factory->factory_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="buyer_id"
                                                class="form-control form-control-sm search-field text-center c-select select2-input"
                                                id="buyer_id">
                                            <option value="">Select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select name="unique_id"
                                                class="form-control form-control-sm search-field text-center c-select select2-input"
                                                id="unique_id">
                                            <option value="">Select</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input name="style_name" class="form-control form-control-sm" value="{{ request('style_name') }}" id="style_name">
                                    </td>
                                    <td>
                                        <select name="within_group"
                                                class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach(\SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder::WITHIN_GROUP as $key => $value)
                                                <option value="{{ $key }}"
                                                    {{$key == request('within_group') ? 'selected' : ''}}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select name="booking_no"
                                                class="form-control form-control-sm search-field text-center c-select select2-input"
                                                id="booking_no">
                                            <option value="">Select</option>
                                            @foreach($booking_no as $value)
                                                <option value="{{ $value }}"
                                                    {{$value == request('booking_no')?'selected':''}}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
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
                                    <td style="padding:2px;">
                                        <div style="display: flex;">
                                            <button type="submit" style="margin-right: 2px;" class="btn btn-primary btn-sm">
                                                <em class="fa fa-search"></em>
                                            </button>
                                            <a class="btn btn-warning btn-sm"
                                            href="{{url('knitting/planning-info-entry')}}">
                                                <em class="fa fa-refresh"></em>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                    <div class="col-md-12">
                        <h6>Planning Info</h6>
                    </div>
                    <div class="col-md-12 table-responsive">
                        <table class="reportTable">
                            <thead>
                            <tr style="background: rgb(90 189 219);">
                                <th nowrap class="mx-2">SL</th>
                                <th nowrap class="mx-2">Prog. No</th>
                                <th nowrap class="mx-2">Prog. Color</th>
                                <th nowrap class="mx-2">Booking Type</th>
                                <th nowrap class="mx-2">Booking No</th>
                                <th nowrap class="mx-2">Booking Date</th>
                                <th nowrap class="mx-2">Buyer</th>
                                <th class="mx-2" style="min-width: 180px;">Style</th>
                                <th nowrap class="mx-2">Unique Id</th>
                                <th nowrap class="mx-2">Sales Order No</th>
                                <th nowrap class="mx-2">Within Group</th>
                                <th nowrap class="mx-2">Body Part</th>
                                <th nowrap class="mx-2">Color Type</th>
                                <th nowrap class="mx-2" style="min-width: 180px;">Fab. Des.</th>
                                <th nowrap class="mx-2">Fab. Gsm</th>
                                <th nowrap class="mx-2">Fab. Dia</th>
                                <th nowrap class="mx-2">Dia Type</th>
                                <th nowrap class="mx-2">Booking Qty</th>
                                <th nowrap class="mx-2">Prog. Qty</th>
                                <th nowrap class="mx-2">Prod. Qty</th>
                                <th nowrap class="mx-2">Bal. Prog. Qty</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($data as $key => $value)
                                <tr>
                                    <td>{{ str_pad($loop->iteration + $data->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>
                                        @permission('permission_of_program_list_add')
                                        @if(is_array($value->knitting_program_ids) && count($value->knitting_program_ids) !== 0)
                                            <button style="font-size: 10px"
                                                    data-id="{{$value->id}}"
                                                    class="btn btn-outline btn-info text-black btn-xs"
                                                    data-programs="@json($value->knitting_program_ids)">
                                                Browse
                                            </button>
                                        @else
                                            <a style="font-size: 10px"
                                               class="btn btn-success btn-xs"
                                               href="{{ url('/knitting/program/create').'?plan_info_id='.$value->id }}">
                                                Create
                                            </a>
                                        @endif
                                        @endpermission
                                    </td>
                                    <td>
                                        <button style="font-size: 10px"
                                                type="button"
                                                class="btn btn-outline btn-info text-black btn-xs"
                                                onclick="browseColor({{ $value->id }})">
                                            Browse Color
                                        </button>
                                    </td>
                                    <td class="text-capitalize">{{ $value->booking_type }}</td>
                                    <td>{{ $value->booking_no }}</td>
                                    <td>{{ $value->booking_date }}</td>
                                    <td>{{ $value->buyer_name }}</td>
                                    <td>{{ $value->style_name }}</td>
                                    <td>{{ $value->unique_id }}</td>
                                    <td>{{ $value->programmable->sales_order_no ?? '' }}</td>
                                    <td>{{
                                            $value->programmable
                                                ? \SkylarkSoft\GoRMG\Knitting\Models\FabricSalesOrder::WITHIN_GROUP[$value->programmable->within_group] ?? ''
                                                : ''
                                            }}
                                    </td>
                                    <td>{{ optional($value->bodyPart)->name }}</td>
                                    <td>{{ optional($value->colorType)->color_types }}</td>
                                    <td>{{ $value->fabric_description }}</td>
                                    <td>{{ $value->fabric_gsm }}</td>
                                    <td>{{ $value->fabric_dia }}</td>
                                    <td>{{ \SkylarkSoft\GoRMG\SystemSettings\Services\DiaTypesService::get($value->dia_type)['name'] ?? null }}</td>
                                    <td>{{ $value->booking_qty }}</td>
                                    <td>{{ $value->program_qty }}</td>
                                    <td>{{ $value->production_qty }}</td>
                                    <td>{{ $value->program_qty - $value->production_qty }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="21" class="text-center">No data available</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                        {{ $data->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="programNoModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLongTitle">Program Color</h5>
                    </div>
                    <div class="modal-body" id="programNo">

                    </div>
                    <div class="modal-footer">
                        <a style="font-size: 10px"
                           class="btn btn-create btn-success pull-left">
                            Create New Program
                        </a>
                        <button
                            style="font-size: 10px"
                            data-dismiss="modal"
                            class="btn btn-danger">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>

        const factoryElement = $("#factory_id");
        const buyerElement = $("#buyer_id");
        const uniqueIdElement = $("#unique_id");


        const programNoModal = $('#programNoModal');
        const createBtn = programNoModal.find('.btn-create');
        const programModalBody = programNoModal.find('.modal-body');

        var factoryId = factoryElement.val() ? factoryElement.val() : '{{ request('factory_id') }}';
        var buyerId = buyerElement.val() ? buyerElement.val() : '{{ request('buyer_id') }}';
        var uniqueId = uniqueIdElement.val() ? uniqueIdElement.val() : '{{ request('unique_id') }}';

        $(function (){

            $('[data-programs]').click(function (e){
                programModalBody.empty();
                createBtn.attr('href', "/knitting/program/create?plan_info_id="+$(this).data('id'));
                programModalBody.html();
                $(this).data('programs').map(id=>{
                    programModalBody.append(`
                          <a class="btn btn-info" style="font-size: 10px"
                            href="/knitting/program/${String(id).padStart(10, '0')}/edit">
                                ${String(id).padStart(10, '0')}
                         </a>
                    `);
                });
                programNoModal.modal('show');
            });

            if('{{ request('factory_id') }}') {
                factoryElement.val(factoryId).trigger('change');
            }
        });

        $(document).on('change', '#factory_id', function () {
            factoryId = $(this).val();
            factoryWiseBuyer();
        });

        $(document).on('change', '#buyer_id', function () {
            //buyerId = $(this).val();
            if (!(factoryId && $(this).val())) return;
            uniqueIdElement.empty().append(`<option value="">Select Unique ID</option>`).val('').trigger('change');
            $.ajax({
                method: 'GET',
                url: `/orders/get-jobs?factoryId=${factoryId}&buyerId=${$(this).val()}`,
                success: function (result) {
                    $.each(result, function (key, value) {
                        uniqueIdElement.append(`<option value="${key}">${value}</option>`);
                    })
                    uniqueIdElement.val(uniqueId).trigger('change');
                },
                error: function (error) {
                    console.log(error)
                }
            })
        });

        // $(document).on('change', '#unique_id', function () {
        //     //uniqueId = $(this).val();
        //     if (!!$(this).val()) {
        //         $.ajax({
        //             method: 'POST',
        //             url: `/orders/get-job-wise-po`,
        //             data: {
        //                 factoryId,
        //                 buyer_Id : buyerElement.val(),
        //                 jobNo: $(this).val() },
        //             success: function (result) {
        //                 $("#style_name").val(result.style_name)
        //             },
        //             error: function (error) {
        //                 console.log(error)
        //             }
        //         })
        //     }
        // });

        function factoryWiseBuyer() {
            buyerElement.empty().append(`<option value="">Select Buyer</option>`).val('').trigger('change');
            $.ajax({
                method: 'GET',
                url: `/orders/get-buyers?factoryId=${factoryId}`,
                success: function (result) {
                    $.each(result, function (key, value) {
                        buyerElement.append(`<option value="${value.id}">${value.name}</option>`);
                    })
                    buyerElement.val(buyerId).trigger('change');
                },
                error: function (error) {
                    console.log(error)
                }
            })
        }


        function browseColor(planInfoId) {
            programModalBody.empty();
            programModalBody.html();

            $.ajax({
                method: 'GET',
                url: `/knitting/api/v1/plan-info/program-color-preview/${ planInfoId }`,
                success: function (result) {
                    console.log(result.length)
                    $.each(result, function (key, html) {
                        console.log(result.length)
                        programModalBody.append(html);
                    });
                },
                error: function (error) {
                    console.log(error);
                }
            })

            programNoModal.modal('show');
        }
    </script>
@endsection

