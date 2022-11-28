@extends('skeleton::layout')
@section("title","Archive Files")
@section('content')
    <section class="padding">
        <div class="box" style="min-height: 610px">
            <div class="box-header btn-info">
                <h2>Archive Files</h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-info btn-sm"
                           href="{{url('archive-file/create')}}">
                            Add File</a>
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-6">
                        <form method="get" action="{{url('archive-file')}}">
                            <table style="float: right !important;">
                                <tbody>
                                <tr>
                                    <td style="width: 35%">
                                        <select name="buyer_id" id="buyer_id"
                                                class="form-control form-control-sm select2-input">
                                            <option selected hidden disabled>Select Buyer</option>
                                            @foreach($buyers as $buyer)
                                                <option
                                                    {{ request()->get('buyer_id') == $buyer->id ? 'selected' : null }} value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="width: 25%">
                                        <input type="hidden" id="styleHiddenInput">
                                        <select id="style_select"
                                                class="form-control form-control-sm select2-input">
                                            <option value="" selected hidden disabled>Select Style</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input
                                            type="text"
                                            name="search"
                                            class="form-control form-control-sm"
                                            value="{{ request('search') ?? null }}">
                                    </td>
                                    <td>
                                        <button
                                            type="submit"
                                            class="btn btn-success"
                                            style="border-radius: 0; padding:5px 15px">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </form>
                    </div>
                </div>
                <table class="reportTable m-t">
                    <thead>
                    <tr style="background-color: aliceblue">
                        <th>SL</th>
                        <th>Buyer</th>
                        <th>Style</th>
                        <th>File Name</th>
                        <th>File</th>
                        <th>Remarks</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php
                        $i = 1;
                    @endphp
                    @if(collect($archiveFiles)->count() > 0)
                        @foreach($archiveFiles->groupBy(['buyer_id']) as $key => $buyerWiseArchiveFile)
                            @foreach($buyerWiseArchiveFile as $archiveFile)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    @if($loop->first)
                                        <td rowspan="{{ count($buyerWiseArchiveFile) }}">
                                            {{ $archiveFile->buyer->name }}
                                        </td>
                                    @endif
                                    <td>{{ $archiveFile->order->style_name ?? $archiveFile->style ?? null }}</td>
                                    <td>{{ $archiveFile->file_name }}</td>
                                    <td>
                                        <a title="Download"
                                           class="btn btn-xs"
                                           download="{{$archiveFile->file_name}}"
                                           href="{{url('uploaded_file/'.$archiveFile->file)}}">
                                            <i class="fa fa-download text-success"></i>
                                        </a>
                                        <a title="Preview"
                                           target="_blank"
                                           class="btn btn-xs"
                                           href="{{ url('uploaded_file/'.$archiveFile->file)}}">
                                            <i class="fa fa-eye text-info"></i>
                                        </a>
                                    </td>
                                    <td> {{ $archiveFile->remarks }} </td>
                                    <td> {{ date('d M Y', strtotime($archiveFile->created_at)) }} </td>
                                    <td style="width: 65px; padding: 2px;">
                                        <a title="Edit"
                                           class="btn btn-xs btn-warning"
                                           href="{{url('/archive-file/'. $archiveFile->id.'/edit')}}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button
                                            type="button"
                                            data-toggle="modal"
                                            ui-target="#animate"
                                            ui-toggle-class="flip-x"
                                            data-target="#confirmationModal"
                                            class="btn btn-xs btn-danger show-modal"
                                            data-url="{{url('/archive-file/'. $archiveFile->id. '/delete')}}">
                                            <i class="fa fa-trash-o"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endif
                    </tbody>
                </table>

                <div class="text-center">
                    {{ $archiveFiles->appends(request()->except('page'))->links() }}
                </div>

            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        let $styleSelectSection = $("#style-select-section");
        let $styleInputSection = $("#style-input-section");
        let $styleHiddenInput = $('#styleHiddenInput');
        let $styleSelect = $("#style_select");
        let $buyerId = $('#buyer_id');

        $(document).on("change", "#buyer_id", function () {
            fetchStyles();
        })

        $styleSelect.on('change', function () {
            let $selectedStyle = $('option:selected', this);
            let $styleId = $selectedStyle.attr('style-id');

            if ($styleId > 0) {
                $styleHiddenInput.attr('name', 'style_id');
                $styleHiddenInput.val($styleId);
            } else {
                $styleHiddenInput.attr('name', 'style');
                $styleHiddenInput.val($selectedStyle.val());
            }
        });

        function fetchStyles() {
            $.ajax({
                url: `/archive-file/${$buyerId.val()}/buyer-wise-style`,
                type: "get",
                dataType: "JSON",
                success(data) {
                    $styleSelect.val('').change();
                    let selectOption = `<option selected hidden disabled>-- SELECT STYLE --</option>`;
                    $.each(data, function (idx, el) {
                        selectOption += `<option style-id='${el.id}' value='${el.style}'>${el.style}</option>`;
                    })
                    $styleSelect.html(selectOption);
                }
            })
        }

        fetchStyles();
    </script>
@endsection

