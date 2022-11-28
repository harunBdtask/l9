@extends('skeleton::layout')
@section("title","Fabric Composition")
@section('styles')
    <style>
        .reportEntryTable {
            margin-bottom: 1rem;
            width: 100%;
            max-width: 100%;
        }

        .reportEntryTable thead,
        .reportEntryTable tbody,
        .reportEntryTable th {
            padding: 3px;
            font-size: 12px;
            text-align: center;
        }

        .reportEntryTable th,
        .reportEntryTable td {
            border: 1px solid transparent;
        }

        .reportTable th,
        .reportable td {
            border: 1px solid #6887ff !important;
        }

        #loader {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(226, 226, 226, 0.75) no-repeat center center;
            width: 100%;
            z-index: 1000;
        }

        .spin-loader {
            position: relative;
            top: 46%;
            left: 5%;
        }

        .form-control form-control-sm-label {
            padding-left: 0px !important;
        }
    </style>
@endsection
@section('content')
    <div class="padding">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h2>{{ $fabric_composition ? 'Update Composition Type' : 'New Composition Type' }}</h2>
                    </div>
                    <div class="box-divider m-a-0"></div>
                    <div class="box-body">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <div class="alert alert-{{ $msg }}">{{ Session::get('alert-' . $msg) }}</div>
                                @endif
                            @endforeach
                        </div>

                        @if($fabric_composition)
                            <span id="form_method" data-form-method="PUT"></span>
                        @else
                            <span id="form_method" data-form-method="POST"></span>
                        @endif

                        {!! Form::model($fabric_composition, ['url' => $fabric_composition ?
                        'fabric-compositions/'.$fabric_composition->id : 'fabric-compositions', 'method' => $fabric_composition ?
                        'PUT' : 'POST', 'id' => 'fabric-composition-form']) !!}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fabric_nature_id">Fabric nature <span
                                            class="text-danger">*</span></label>
                                    {!! Form::select('fabric_nature_id', $fabric_natures ?? [], null, ['class' => 'form-control form-control-sm
                                    select2-input', 'id' => 'fabric_nature_id', 'placeholder' => 'Select Fabric Nature']) !!}
                                    <span class="text-danger small fabric_nature_id"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="color_range_id">Color Range <span
                                            class="text-danger">*</span></label>
                                    {!! Form::select('color_range_id', $color_ranges ?? [], null, ['class' => 'form-control form-control-sm select2-input',
                                    'id' => 'color_range_id', 'placeholder' => 'Select Color Range']) !!}
                                    <span class="text-danger small color_range_id"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="d-flex">
                                        <label for="construction">Fabric Name <span
                                                class="text-danger">*</span></label>
                                        <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"
                                                style="float : right"
                                                data-target="#constructionModal">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    {!! Form::select('construction', $compositions ?? [], null, ['class' => 'form-control form-control-sm select2-input',
                                    'id' => 'construction', 'placeholder' => 'Select Construction']) !!}
                                    <span class="text-danger small construction"></span>

                                </div>
                            </div>
                            {{--							<div class="col-md-3">--}}
                            {{--								<div class="form-group">--}}
                            {{--									<label for="construction" >Construction</label>--}}
                            {{--									{!! Form::text('construction', null, ['class' => 'form-control form-control-sm', 'id' => 'construction', 'placeholder'--}}
                            {{--									=> 'Write Construction']) !!}--}}
                            {{--									<span class="text-danger small construction"></span>--}}
                            {{--								</div>--}}
                            {{--							</div>--}}
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="gsm">GSM</label>
                                    {!! Form::text('gsm', null, ['class' => 'form-control form-control-sm', 'id' => 'gsm', 'placeholder' => 'Write
                                    GSM']) !!}
                                    <span class="text-danger small gsm"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="machine_dia">Machine Dia</label>
                                    {!! Form::text('machine_dia', null, ['class' => 'form-control form-control-sm', 'id' => 'machine_dia', 'placeholder' =>
                                    'Write Machine Dia']) !!}
                                    <span class="text-danger small machine_dia"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="finish_fabric_dia">Finish Fabric Dia</label>
                                    {!! Form::text('finish_fabric_dia', null, ['class' => 'form-control form-control-sm', 'id' => 'finish_fabric_dia',
                                    'placeholder' => 'Write Finish Fabric Dia']) !!}
                                    <span class="text-danger small finish_fabric_dia"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="machine_gg">Machine GG</label>
                                    {!! Form::text('machine_gg', null, ['class' => 'form-control form-control-sm', 'id' => 'machine_gg', 'placeholder' =>
                                    'Write Machine GG']) !!}
                                    <span class="text-danger small machine_gg"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="stitch_length">Stitch Length</label>
                                    {!! Form::text('stitch_length', null, ['class' => 'form-control form-control-sm', 'id' => 'stitch_length', 'placeholder'
                                    => 'Write Stitch Length']) !!}
                                    <span class="text-danger small stitch_length"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="fabric_code">Fabric Code</label>
                                    {!! Form::text('fabric_code', null, ['class' => 'form-control form-control-sm', 'id' => 'fabric_code', 'placeholder'
                                    => 'Write Fabric Code']) !!}
                                    <span class="text-danger small fabric_code"></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status <span
                                            class="text-danger">*</span></label>
                                    {!! Form::select('status', $status ?? [], null, ['class' => 'form-control form-control-sm select2-input', 'id' => 'status']) !!}
                                    <span class="text-danger small status"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 table-responsive">
                                <table class="reportEntryTable">
                                    <thead>
                                    <tr>
                                        <th class="width-20p">
                                            Composition
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"
                                                    style="float : right"
                                                    data-target="#compositionModal">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </th>
                                        <th class="width-20p">&#37;</th>
                                        <th class="width-20p">
                                            Count
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"
                                                    style="float : right"
                                                    data-target="#countModal">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </th>
                                        <th class="width-20p">
                                            Type
                                            <button type="button" class="btn btn-primary btn-xs" data-toggle="modal"
                                                    style="float : right"
                                                    data-target="#typeModal">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </th>
                                        <th class="width-20p">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            {!! Form::select('yarn_composition_id_val', $yarn_compositions ?? [], null, ['class' =>
                                            'form-control form-control-sm select2-input yarn_composition_id_val', 'placeholder' => 'Select Composition']) !!}
                                            <span class="yarn_composition_id text-danger small"></span>
                                        </td>
                                        <td>
                                            {!! Form::number('percentage_val', null, ['class' => 'form-control form-control-sm', 'placeholder' => 'Write
                                            percentage', 'id' => 'percentage_val']) !!}
                                            <span class="percentage text-danger small"></span>
                                        </td>
                                        <td>
                                            {!! Form::select('yarn_count_id_val', $yarn_counts ?? [], null, ['class' => 'form-control form-control-sm
                                            select2-input yarn_count_id_val', 'placeholder' => 'Select Count']) !!}
                                            <span class="yarn_count_id text-danger small"></span>
                                        </td>
                                        <td>
                                            {!! Form::select('composition_type_id_val', $composition_types ?? [], null, ['class' =>
                                            'form-control form-control-sm select2-input composition_type_id_val', 'placeholder' => 'Select Type']) !!}
                                            <span class="yarn_count_id text-danger small"></span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-success add-row" title="Add"><i
                                                    class="fa fa-plus"></i></button>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>

                            </div>
                            <div class="col-md-12 table-responsive child-entry-section">
                                <table class="reportTable">
                                    <thead>
                                    <tr class="info">
                                        <th class="width-20p">Composition</th>
                                        <th class="width-20p">&#37;</th>
                                        <th class="width-20p">Count</th>
                                        <th class="width-20p">Type</th>
                                        <th class="width-20p">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="child-entry-table-body">
                                    @if($fabric_composition && $fabric_composition->newFabricCompositionDetails->count())
                                        @foreach($fabric_composition->newFabricCompositionDetails as $key => $fabric_composition_detail)
                                            <tr>
                                                <td>
                                                    {{ $fabric_composition_detail->yarnComposition->yarn_composition }}
                                                    {!! Form::hidden('fab_composition_details_id[]', $fabric_composition_detail->id) !!}
                                                    {!! Form::hidden('yarn_composition_id[]', $fabric_composition_detail->yarn_composition_id) !!}
                                                    <span class="yarn_composition_id text-danger small"></span>
                                                </td>
                                                <td class="percentage">
                                                    {{ $fabric_composition_detail->percentage }}
                                                    {!! Form::hidden('percentage[]', $fabric_composition_detail->percentage) !!}
                                                    <span class="percentage text-danger small"></span>
                                                </td>
                                                <td>
                                                    {{ $fabric_composition_detail->yarnCount->yarn_count }}
                                                    {!! Form::hidden('yarn_count_id[]', $fabric_composition_detail->yarn_count_id) !!}
                                                    <span class="yarn_count_id text-danger small"></span>
                                                </td>
                                                <td>
                                                    {{ $fabric_composition_detail->compositionType->name }}
                                                    {!! Form::hidden('composition_type_id[]', $fabric_composition_detail->composition_type_id) !!}
                                                    <span class="composition_type_id text-danger small"></span>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-xs btn-danger remove-row"
                                                            title="Remove"><i
                                                            class="fa fa-trash"></i></button>
                                                    <button type="button" class="btn btn-xs btn-warning edit-row"
                                                            title="Edit"><i
                                                            class="fa fa-edit"></i></button>
                                                    {{--                                                    <a href="javascript:void(0)" data-id="{{ $fabric_composition_detail->id }}" class="btn btn-xs btn-warning edit"><i class="fa fa-edit"></i></a>--}}

                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="form-group m-t-md">
                            <button type="submit"
                                    class="btn btn-sm btn-info">{{ $fabric_composition ? 'Update' : 'Create' }}</button>
                            <a class="btn btn-dark btn-danger btn-sm" href="{{ url('fabric-compositions') }}">Cancel</a>
                        </div>
                        {!! Form::close() !!}

                        <!-- Modal -->
                        <div class="modal fade" id="constructionModal" tabindex="-1" role="dialog"
                             aria-labelledby="constructionModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="constructionModalLabel">Fabric Construction
                                            Entry</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ url('fabric-construction-entry') }}" method="post"
                                          id="fabric-construction-entry-form">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="construction_name">Construction Name</label>
                                                <input type="text" id="construction_name" name="construction_name"
                                                       class="form-control form-control-sm"
                                                       value="{{ old('construction_name') }}"
                                                       placeholder="Construction Name">
                                                @error('construction_name')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-sm btn-success m-b"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                            <button type="button" class="btn btn-sm white m-b"
                                                    data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="compositionModal" tabindex="-1" role="dialog"
                             aria-labelledby="compositionModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="compositionModalLabel">Yarn Composition</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form action="{{ url('yarn-compositions') }}" method="post"
                                          id="yarn-compositions-form">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="yarn_composition">Yarn Composition</label>
                                                <input type="text" id="yarn_composition" name="yarn_composition"
                                                       class="form-control form-control-sm"
                                                       required
                                                       value="{{ old('yarn_composition') }}"
                                                       placeholder="Yarn Compositions">
                                                @error('yarn_composition')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-sm btn-success m-b"><i
                                                    class="fa fa-save"></i> Save
                                            </button>
                                            <button type="button" class="btn btn-sm white m-b"
                                                    data-dismiss="modal">Close
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="countModal" tabindex="-1" role="dialog"
                             aria-labelledby="countModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="countModalLabel">Yarn Count</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>

                                    <form action="{{ url('yarn-counts') }}" method="post" id="yarn-counts-form">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="yarn_count">Yarn Count</label>
                                                <input type="text" id="yarn_count" name="yarn_count"
                                                       class="form-control form-control-sm"
                                                       value="{{ old('yarn_count') }}" placeholder="Yarn Compositions">
                                                @error('yarn_count')
                                                <div class="alert alert-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>
                                                Save
                                            </button>
                                            <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="typeModal" tabindex="-1" role="dialog"
                             aria-labelledby="typeModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="typeModalLabel">Composition Type</h5>
                                        <button type="button" class="close" data-dismiss="modal"
                                                aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <form method="POST" action="{{ url('composition-types') }}"
                                          id="composition-types-form">
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="name" class="col-sm-2 form-control form-control-sm-label">Composition
                                                    Type</label>
                                                <div class="col-sm-10">
                                                    {!! Form::text('name', null, ['class' => 'form-control form-control-sm', 'id' => 'name', 'placeholder' => 'Write fabric type here']) !!}

                                                    @if($errors->has('name'))
                                                        <span class="text-danger">{{ $errors->first('name') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i>
                                                Save
                                            </button>
                                            <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">
                                                Close
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="loader">
            <div class="text-center spin-loader"><i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i></div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var flashMessageDom = $('.flash-message');
        let totalValue = 0;
        let totalPercent = 0;

        function showLoader() {
            $('#loader').show();
        }

        function hideLoader() {
            $('#loader').hide();
        }

        // Scroll To Top
        function scrollToTop() {
            window.scrollTo({top: 0, behavior: 'smooth'});
        }

        function goToListPage() {
            window.location.href = window.location.protocol + "//" + window.location.host + "/fabric-compositions";
        }

        $(document).on('click', '.add-row', function (e) {
            e.preventDefault();
            var perchengate_value_input = $('#percentage_val').val();
            var perchengate_value = perchengate_value_input ? perchengate_value_input : 0;
            var sumPercentage = totalPercentageValue();
            totalValue = sumPercentage + (parseFloat(perchengate_value) ?? 0);
            let fabric_nature = $('#fabric_nature_id').val();
            if ((sumPercentage + parseFloat(perchengate_value)) <= 100 || fabric_nature != 1) {
                let yarn_composition_id_val = $('[name="yarn_composition_id_val"]').val();
                let yarn_composition = yarn_composition_id_val ? $('[name="yarn_composition_id_val"] option:selected').text() : '';
                let percentage_val = $('[name="percentage_val"]').val();
                let yarn_count_id_val = $('[name="yarn_count_id_val"]').val();
                let yarn_count = yarn_count_id_val ? $('[name="yarn_count_id_val"] option:selected').text() : '';
                let composition_type_id_val = $('[name="composition_type_id_val"]').val();
                let composition_type = composition_type_id_val ? $('[name="composition_type_id_val"] option:selected').text() : '';
                let child_entry_table_body_dom = $('.child-entry-table-body');
                if (yarn_composition_id_val || percentage_val || yarn_count_id_val || composition_type_id_val) {
                    let newTableRow = '<tr>' +
                        '<td>' + yarn_composition +
                        '<input name="fab_composition_details_id[]" type="hidden" value="">' +
                        '<input name="yarn_composition_id[]" type="hidden" value="' + yarn_composition_id_val + '">' +
                        '<span class="yarn_composition_id text-danger small"></span>' +
                        '</td>' +
                        '<td>' + percentage_val +
                        '<input name="percentage[]" type="hidden" value="' + percentage_val + '">' +
                        '<span class="percentage text-danger small"></span>' +
                        '</td>' +
                        '<td>' + yarn_count +
                        '<input name="yarn_count_id[]" type="hidden" value="' + yarn_count_id_val + '">' +
                        '<span class="yarn_count_id text-danger small"></span>' +
                        '</td>' +
                        '<td>' + composition_type +
                        '<input name="composition_type_id[]" type="hidden" value="' + composition_type_id_val + '">' +
                        '<span class="composition_type_id text-danger small"></span>' +
                        '</td>' +
                        '<td>' +
                        '<button type="button" class="btn btn-xs btn-danger remove-row" title="Remove"><i class="fa fa-trash"></i></button>' +
                        '<button type="button" class="btn btn-xs btn-warning edit-row" title="Remove"><i class="fa fa-edit"></i></button>' +
                        '</td>'
                    '</tr>';
                    child_entry_table_body_dom.append(newTableRow);
                    $('[name="yarn_composition_id_val"]').val('').select2();
                    $('[name="percentage_val"]').val('');
                    $('[name="yarn_count_id_val"]').val('').select2();
                    $('[name="composition_type_id_val"]').val('').select2();
                } else {
                    alert("Please write or select the relevant fields!");
                }
            } else {
                alert("total percentage must be equal to 100");
            }
        });

        $(document).on('click', '.edit-row', function (e) {
            e.preventDefault();
            var thisHtml = $(this);
            $yarn_composition_id_val = $(this).parents('tr').find('[name="yarn_composition_id[]"]').val();
            $percentage_val = $(this).parents('tr').find('[name="percentage[]"]').val();
            $yarn_count_id_val = $(this).parents('tr').find('[name="yarn_count_id[]"]').val();

            $composition_type_id_val = $(this).parents('tr').find('[name="composition_type_id[]"]').val();

            $('[name="percentage_val"]').val($percentage_val);
            $('[name="yarn_composition_id_val"]').val($yarn_composition_id_val).select2();
            $('[name="yarn_count_id_val"]').val($yarn_count_id_val).select2();
            $('[name="composition_type_id_val"]').val($composition_type_id_val).select2();
            $(this).parents('tr').remove();


            totalPercentageValue();

        })

        $(document).on('click', '.remove-row', function (e) {
            e.preventDefault();
            var confirmAction = confirm("Are you sure?");
            var thisHtml = $(this);
            var fab_composition_details_id = $(this).parents('tr').find('[name="fab_composition_details_id[]"]').val();

            if (confirmAction) {
                if (fab_composition_details_id) {
                    showLoader();
                    $.ajax({
                        type: "DELETE",
                        url: "/fabric-composition-details/" + fab_composition_details_id
                    }).done(function (response) {
                        hideLoader();
                        if (response.status === 'success') {
                            thisHtml.parents('tr').remove();
                            totalPercentageValue();
                        }
                    }).fail(function (response) {
                        hideLoader();
                        console.log("Something went wrong!");
                    })
                } else {
                    $(this).parents('tr').remove();

                    totalPercentageValue();
                }
            }
        });

        function totalPercentageValue() {
            var sumPercentage = $("input[name='percentage[]']")
                .map(function () {
                    var val = $(this).val();
                    return val ? val : 0;
                }).get().reduce(function (a, b) {
                    return parseFloat(a) + parseFloat(b);
                }, 0);
            totalValue = sumPercentage
            return sumPercentage;
        }


        $(document).on('submit', '#fabric-composition-form', function (e) {
            e.preventDefault();
            flashMessageDom.html('');
            let fabric_nature = $('#fabric_nature_id').val();
            if (totalValue !== 100.00 && fabric_nature == 1) {
                alert('Composition% must be 100. now is ' + totalValue);
                return false;
            }
            var form = $(this);
            var url = form.attr('action');
            var method = $('#form_method').attr('data-form-method');
            $('.text-danger').html('');
            showLoader();
            $.ajax({
                type: method,
                url: url,
                data: form.serialize()
            }).done(function (response) {
                hideLoader();
                scrollToTop();
                if (response.status === 'success') {
                    flashMessageDom.html(response.message);
                    flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
                    setTimeout(function () {
                        goToListPage();
                    }, 3000)

                }

                if (response.status === 'danger') {
                    flashMessageDom.html(response.message);
                    flashMessageDom.fadeIn().delay(2000).fadeOut(2000);
                }
            }).fail(function (response) {
                hideLoader();
                if (response.responseJSON.errors.percentage_total) {
                    alert(response.responseJSON.errors.percentage_total[0]);
                }
                $.each(response.responseJSON.errors, function (errorIndex, errorValue) {
                    let errorDomElement, error_index, errorMessage;
                    errorDomElement = '' + errorIndex;
                    errorDomIndexArray = errorDomElement.split(".");
                    errorDomElement = '.' + errorDomIndexArray[0];
                    error_index = errorDomIndexArray[1];
                    errorMessage = errorValue[0];
                    if (errorDomIndexArray.length == 1) {
                        $(errorDomElement).html(errorMessage);
                    } else {
                        $(".child-entry-table-body tr:eq(" + error_index + ")").find(errorDomElement).html(errorMessage);
                    }
                })
            });

        })


        $(document).on('submit', '#fabric-construction-entry-form', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            showLoader();

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize()
            }).done(function (response) {
                hideLoader()
                if (response.status === 'success') {
                    $('#constructionModal').modal('hide')
                    let select2_option_value = new Option(response.data.construction_name, response.data.construction_name, true, true)
                    $(select2_option_value).html(response.data.construction_name)
                    $("#construction").append(select2_option_value).trigger('change')
                }
            }).fail(function (response) {
                hideLoader()
            });
        })

        $(document).on('submit', '#yarn-compositions-form', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            showLoader();

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize()
            }).done(function (response) {
                hideLoader()
                if (response.status === 'success') {
                    $('#compositionModal').modal('hide')
                    let select2_option_value = new Option(response.data.yarn_composition, response.data.id, true, true);
                    $(select2_option_value).html(response.data.yarn_composition);
                    $(".yarn_composition_id_val").append(select2_option_value).trigger('change');
                    ;
                }
            }).fail(function (response) {
                hideLoader()
            });
        })

        $(document).on('submit', '#yarn-counts-form', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            showLoader();

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize()
            }).done(function (response) {
                hideLoader()
                if (response.status === 'success') {
                    $('#countModal').modal('hide')
                    let select2_option_value = new Option(response.data.yarn_count, response.data.id, true, true);
                    $(select2_option_value).html(response.data.yarn_composition);
                    $(".yarn_count_id_val").append(select2_option_value).trigger('change');
                }
            }).fail(function (response) {
                hideLoader()
            });
        })

        $(document).on('submit', '#composition-types-form', function (e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            showLoader();

            $.ajax({
                type: 'POST',
                url: url,
                data: form.serialize()
            }).done(function (response) {
                hideLoader()
                if (response.status === 'success') {
                    $('#typeModal').modal('hide')
                    let select2_option_value = new Option(response.data.name, response.data.id, true, true);
                    $(select2_option_value).html(response.data.yarn_composition);
                    $(".composition_type_id_val").append(select2_option_value).trigger('change');
                }
            }).fail(function (response) {
                console.log(response)
            });
        })
    </script>
@endsection
