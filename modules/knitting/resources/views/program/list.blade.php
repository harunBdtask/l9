@extends('skeleton::layout')
@section('title', 'Knitting Programs')
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <div>
                    <h2>Knitting Programs</h2>
                </div>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-md-12">
                        @include('inventory::partials.flash')
                    </div>

                    @include('skeleton::partials.dashboard',['dashboardOverview'=>$data['dashboardOverview']])
                    @include('skeleton::partials.table-export')

                    <div class="col-md-12 m-t-1" style="padding-top:20px">
                        <form class="table-responsive" action="{{ url('knitting/program-list') }}">
                            <table class="reportTable-zero-padding">
                                <thead>
                                <tr style="background: #0ab4e6;">
                                    <th nowrap>SL</th>
                                    <th nowrap class="mx-2">Prog. Color</th>
                                    <th nowrap>Buyer</th>
                                    <th nowrap style="">Style</th>
                                    <th nowrap>Program No</th>
                                    <th nowrap>Program date</th>
                                    <th nowrap>Within Group</th>
                                    <th nowrap>Knitting Source</th>
                                    <th nowrap class="p-x-2">Knitting Party</th>
                                    <th nowrap>Booking Type</th>
                                    <th nowrap>Booking No</th>
                                    <th nowrap>Sales Order Id</th>
                                    <th nowrap>Body Part</th>
                                    <th nowrap>Program Qty</th>
                                    <th nowrap>K.Card ttl Qty</th>
                                    <th nowrap>Fnsh.Fbrk.Dia</th>
                                    <th nowrap>Mchn.Dia</th>
                                    <th nowrap>Mchn.GG</th>
                                    <th nowrap>Status</th>
                                    <th nowrap>Remarks</th>
                                </tr>
                                <tr>
                                    <th>#</th>
                                    <th></th>
                                    <th>
                                        <select
                                            name="buyer_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['buyers'] as $value)
                                                <option
                                                    {{ request('buyer_id') == $value->id ? 'selected' : '' }} value="{{ $value->id }}">
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="style_name"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['styles'] as $value)
                                                <option
                                                    {{ request('style_name') == $value ? 'selected' : '' }} value="{{ $value }}">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="program_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['search_programs'] as $program)
                                                <option
                                                    @if(request()->get('program_no') == $program->program_no) selected
                                                    @endif
                                                    value="{{ $program->program_no }}"> {{ $program->program_no }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <input
                                            name="program_date"
                                            value="{{ request()->get('program_date') }}"
                                            type="date"
                                            class="form-control form-control-sm search-field text-center"
                                            placeholder="Search">
                                    </th>
                                    <th>
                                        <select
                                            name="within_group"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['withinGroups'] as $key => $value)
                                                <option
                                                    {{ request('within_group') == $key ? 'selected' : '' }} value="{{ $key }}">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="knitting_source_id"
                                            id="knittingSource"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['knittingSources'] as $key => $value)
                                                <option
                                                    {{ request('knitting_source_id') == $key ? 'selected' : '' }} value="{{ $key }}">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            id="knittingParty"
                                            name="knitting_party_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="type"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            <option @if(request()->get('type') == 'main') selected @endif value="main">Main</option>
                                            <option @if(request()->get('type') == 'short') selected @endif value="short">Short</option>
                                            <option @if(request()->get('type') == 'sample') selected @endif value="sample">Sample</option>
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="booking_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['bookingNos'] as $value)
                                                <option
                                                    {{ request('booking_no') == $value ? 'selected' : '' }} value="{{ $value }}">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="sales_order_no"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['salesOrderNos'] as $key => $value)
                                                <option
                                                    {{ request('sales_order_no') == $value ? 'selected' : '' }} value="{{ $value }}">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="body_part_id"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['bodyParts'] as $key => $value)
                                                <option
                                                    {{ request('body_part_id') == $value->id ? 'selected' : '' }} value="{{ $value->id }}">
                                                    {{ $value->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="program_qty"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['programs'] as $program)
                                                <option
                                                    {{ request('program_qty') != '' and request('program_qty') == $program->program_qty ? 'selected' : '' }} value="{{ $program->program_qty }}">
                                                    {{ $program->program_qty }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <!--Total Knit Card Qty -->
                                    </th>
                                    <th>
                                        <select
                                            name="finish_fabric_dia"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['programs'] as $program)

                                                <option
                                                    {{ request('finish_fabric_dia') != '' and request('finish_fabric_dia') == $program->finish_fabric_dia ? 'selected' : '' }} value="{{ $program->finish_fabric_dia }}">
                                                    {{ $program->finish_fabric_dia }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="machine_dia"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['programs'] as $program)
                                                <option
                                                    {{ request('machine_dia') != '' and request('machine_dia') == $program->machine_dia ? 'selected' : '' }} value="{{ $program->machine_dia }}">
                                                    {{ $program->machine_dia }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="machine_gg"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach($data['programs'] as $program)
                                                <option
                                                    {{ request('machine_gg') != '' and request('machine_gg') == $program->machine_gg? 'selected' : '' }} value="{{ $program->machine_gg }}">
                                                    {{ $program->machine_gg }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <select
                                            name="status"
                                            class="form-control form-control-sm search-field text-center c-select select2-input">
                                            <option value="">Select</option>
                                            @foreach(\SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram::PLANNING_STATUS as $key => $value)
                                                <option
                                                    {{ request('status') == $key ? 'selected' : '' }} value="{{ $key }}">
                                                    {{ $value }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </th>
                                    <th>
                                        <div style="display: flex;">
                                            <button class="btn btn-xs white" title="Search">
                                                <i class="fa fa-search"></i>
                                            </button>
                                            <a class="btn btn-xs btn-warning" title="Refresh"
                                               style="margin-left: 5px;"
                                               href="{{ url('knitting/program-list') }}">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </th>

                                </tr>
                                </thead>
                                @forelse($data['programs'] as $key => $value)
                                    <tr class="tooltip-data row-options-parent">
                                        <td nowrap>{{ str_pad($loop->iteration + $data['programs']->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                        <td>
                                            <button style="font-size: 10px"
                                                    type="button"
                                                    class="btn btn-outline btn-info text-black btn-xs"
                                                    onclick="browseColor({{ $value->id }})">
                                                Browse Color
                                            </button>
                                        </td>
                                        <td nowrap>{{ $value->buyer->name ?? '' }}</td>
                                        <td>{{ $value->planInfo->style_name ?? '' }}</td>
                                        <td class="" nowrap>{{ $value->program_no }}
                                            <br>
                                            <div class="row-options" style="display:none ">
                                                @permission('permission_of_program_list_view')
                                                <a href="/knitting/program/{{ $value->id }}/view"
                                                   class=" text-success"
                                                   target="_blank"
                                                   title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <span>|</span>
                                                <a href="/knitting/program/{{ $value->id }}/program-view"
                                                   class="text-warning"
                                                   target="_blank"
                                                   title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <span>|</span>
                                                @endpermission

                                                @permission('permission_of_program_list_edit')
                                                <a href="/knitting/program/{{$value->program_no}}/edit"
                                                   class="text-primary" target="_blank" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <span>|</span>
                                                @endpermission

                                                @permission('permission_of_program_list_delete')
                                                <a class="text-danger"
                                                   onclick="return confirm('Are you sure')"
                                                   title="Delete"
                                                   href="{{ url("/knitting/program/{$value->id}/delete") }}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                                @endpermission
                                            </div>
                                        </td>
                                        <td>{{ $value->program_date }}</td>
                                        <td>{{ $value->planInfo->programmable->within_group_text ?? '' }}</td>
                                        <td nowrap>{{ $data['knittingSources'][$value->knitting_source_id] ?? '' }}</td>
                                        <td nowrap class="p-x-2">{{ $value->party_name }}</td>
                                        <td nowrap class="text-capitalize">{{ $value->planInfo->programmable->booking_type ?? '' }}</td>
                                        <td nowrap>{{ $value->planInfo->programmable->booking_no ?? '' }}</td>
                                        <td nowrap>{{ $value->planInfo->programmable->sales_order_no ?? '' }}</td>
                                        <td nowrap>{{ $value->planInfo->bodyPart->name ?? '' }}</td>
                                        <td nowrap>{{ $value->program_qty }}</td>
                                        <td nowrap>{{ $value->knit_card_sum_assign_qty }}</td>
                                        <td nowrap>{{ $value->finish_fabric_dia }}</td>
                                        <td nowrap>{{ $value->machine_dia }}</td>
                                        <td nowrap>{{ $value->machine_gg }}</td>
                                        <td nowrap>{{ \SkylarkSoft\GoRMG\Knitting\Models\KnittingProgram::PLANNING_STATUS[$value->status] ?? null }}</td>
                                        <td nowrap>{{ $value->remarks }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="20">No data available</td>
                                    </tr>
                                @endforelse
                            </table>
                        </form>
                    </div>
                </div>
                <div class="row m-t">
                    <div class="col-sm-12">
                        {{ $data['programs']->appends(request()->query())->links() }}
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
                        <h5 class="modal-title" id="exampleModalLongTitle">Programs</h5>
                    </div>
                    <div class="modal-body" id="programNo">

                    </div>
                    <div class="modal-footer">
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
        $(document).on('change', '#knittingSource', function (e) {
            const knittingSource = e.target.value;
            if (knittingSource) {
                const url = knittingSource == 1 ? '/fetch-factories' : '/fetch-suppliers';
                $("#knittingParty").empty().append("<option value=''>Select</option>");
                $.ajax({
                    type: 'GET',
                    url,
                    success: function (response) {
                        $.each(response, function (i, index) {
                            const id = response[i].id !== undefined ? response[i].id : response[i].value;
                            console.log(response[i]);
                            console.log(id);
                            $("#knittingParty").append("<option value=" + id + ">" + response[i].text + "</option>")
                        })
                    }
                })
            }
        });

        const programNoModal = $('#programNoModal');
        const programModalBody = programNoModal.find('.modal-body');
        function browseColor(planInfoId) {
            programModalBody.empty();
            programModalBody.html();

            $.ajax({
                method: 'GET',
                url: `/knitting/api/v1/program/program-color-preview/${ planInfoId }`,
                success: function (html) {
                    programModalBody.html(html);
                },
                error: function (error) {
                    console.log(error);
                }
            })

            programNoModal.modal('show');
        }
    </script>
@endsection
