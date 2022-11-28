@extends('skeleton::layout')
@section("title","PO Files(Excel)")

@section('styles')
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header" style="height: 55px;">
                <div class="row">
                    <div class="col-sm-9">
                        <h2>PO Files(Excel)</h2>
                    </div>
                    <div class="col-sm-3">
                        <form action="{{ url('/po-files-excel') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request()->query('search') ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm white m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

            <div class="box-body">

                <div class="row">
                    <div class="col-sm-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">{{ Session::get('alert-' . $msg) }}</p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12 col-md-4">
                        <div class="box">
                            <div class="box-header">
                                <form action="{{ url('/po-files-excel') }}" method="post" id="form"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <label for="style">Buyers <span class="text-danger req">*</span></label>
                                            <select class="form-control form-control-sm select2-input" name="buyer_id"
                                                    id="buyer_id">
                                                <option selected disabled>Select</option>
                                                @foreach($buyers as $buyer)
                                                    <option
                                                        value="{{ $buyer->id }}">{{$buyer->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('buyer_id')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group m-t-1">
                                        <div class="col-md-12">
                                            <label for="po_no">File <span class="text-danger req">*</span>
                                            </label>
                                            <input type="file" id="file" name="file"
                                                   class="form-control form-control-sm">
                                            @error('file')
                                            <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>


                                    <div class="form-group" style="margin-left: 3%;">
                                        <button style="margin-top: 2%" type="submit" id="submit"
                                                class="btn btn-sm white"><i
                                                class="fa fa-save"></i> Save
                                        </button>
                                        <a style="margin-top: 2%" href="{{ url('/po-files-excel') }}"
                                           class="btn btn-sm btn-dark"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                        <button style="margin-top: 2%" type="button" id="download-sample"
                                                class="btn btn-sm btn-info">
                                            <i class="fa fa-download"></i> Sample Download
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-12" style="overflow-y: scroll;">
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>SL</th>
                                <th>Po No</th>
                                <th>Style</th>
                                <th>Buyer</th>
                                <th>Created At</th>
                                <th>File Name</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $start = 0;
                            @endphp
                            @forelse($po_group_by_files as $key => $poGroupByFile)
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{ ++$start }}</td>
                                    <td>{{ $poGroupByFile->po_no }}

                                        <br>
                                        <div class="row-options" style="display:none ">
                                            <a class="text-warning"
                                               href="{{url("/po-files-excel/".$poGroupByFile->id."/download")}}">
                                                <i class="fa fa-download" style="color:#f0ad4e"></i>
                                            </a>
                                            <span>|</span>
                                            <a class="text-info"
                                               href="{{url("/po-files-excel/".$poGroupByFile->id."/edit")}}">
                                                <i class="fa fa-pencil" style="color:#269abc"></i>
                                            </a>
                                            <span>|</span>
                                            <a href="{{ url('po-files-excel/'.$poGroupByFile->id) }}" type="button"
                                               class="text-danger show-modal"
                                               data-toggle="modal"
                                               data-target="#confirmationModal" ui-toggle-class="flip-x"
                                               ui-target="#animate"
                                               data-url="{{ url('po-files-excel/'.$poGroupByFile->id) }}">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                            <span>|</span>
                                            <a class="text-info"
                                               href="{{url("/po-files-excel/".$poGroupByFile->id."/replace")}}">
                                                <i class="fa fa-copy" style="color:#269abc"></i>
                                            </a>
                                        </div>
                                    </td>
                                    <td>{{ $poGroupByFile->style }}</td>
                                    <td>{{ $poGroupByFile->buyer->name }}</td>
                                    <td>{{ $poGroupByFile->created_at->format('d/M/Y') }}</td>
                                    <td style="background-color: whitesmoke;">
                                        {{ $poGroupByFile->file }}
                                        @if($poGroupByFile->status == 1)
                                            <br>
                                            <span class="label bg-success">Replaced</span>
                                        @endif
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div>
                            {{ $po_group_by_files->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        $(document).on('click', '#download-sample', () => {
            let url = "{{ url('/po-files-excel/sample-download') }}";
            window.location.assign(url);
        });

        $(document).on('change', '#buyer_id', function () {
            let buyerId = $(this).val();

            axios.get(`/common-api/buyers-styles/${buyerId}`).then(({data}) => {
                let options = [];
                $(`#style`).find('option').not(':first').remove();
                data.forEach((style) => {
                    options.push([
                        `<option value="${style.text}">${style.text}</option>`
                    ].join(''));
                });
                $('#style').append(options);
            }).catch((error) => {
                console.error(error);
            })
        })
    </script>
@endpush
