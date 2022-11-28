@extends('skeleton::layout')
@section("title","PO File")

@section('styles')
    {{-- <style>
        .table-header {
            background: #93dcf9;
        }
    </style> --}}
@endsection

@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>PO Files</h2>
            </div>
            <br>
            @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('/po_files') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request()->query('search') ?? '' }}" placeholder="Search Po and Style">
                                <span class="input-group-btn">
                                            <button class="btn btn-sm btn-info m-b" type="submit">Search</button>
                                        </span>
                            </div>
                        </form>
                    </div>
                </div>
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

              
                

                <div class="row m-t">
                    <div class="col-sm-12 col-md-3">
                        <div class="box">
                            <div class="box-header">
                                <form action="{{ url('/po_files') }}" method="post" id="form"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="buyer_id">Buyer <span class="text-danger req">*</span></label>
                                        <select class="form-control form-control-sm select2-input" name="buyer_id"
                                                id="buyer_id">
                                            <option selected disabled>Select</option>
                                            @foreach($buyers as $key=>$buyer)
                                                <option {{$key == 0 ? 'selected' : ''}}
                                                        data-pdf_code="{{$buyer->pdf_conversion_key}}"
                                                        value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('buyer_id')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="po_no">PO No. <span class="text-danger req">*</span></label>
                                        <input type="text" id="po_no" name="po_no"
                                               class="form-control form-control-sm" value="{{ old('po_no') }}">
                                        @error('po_no')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="po_no">Style. <span class="text-danger req">*</span></label>
                                        <select class="form-control form-control-sm select2-input" name="style"
                                                id="style">
                                            <option selected disabled>Select</option>
                                            @foreach($styles as $style)
                                                <option
                                                    value="{{ $style->style_name }}">{{ $style->style_name }}</option>
                                            @endforeach
                                        </select>
                                        @error('style')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <label for="po_no">Team/Color. <span class="text-danger req">*</span></label>
                                        <select class="form-control form-control-sm select2-input" name="flag"
                                                id="style">
                                            <option value="team" selected>Team</option>
                                            <option value="color">Color</option>
                                        </select>
                                        @error('flag')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="po_no">File <span class="text-danger req">*</span> <span
                                                style="font-size: 12px;color: #1c4af3;">[<a
                                                    href="https://pdfresizer.com/delete-pages" target="_blank">Resize PDF</a>]</span></label>
                                        <input type="file" id="file" name="file"
                                               class="form-control form-control-sm">
                                        @error('file')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="form-group text-center">
                                        <button type="submit" id="submit" onclick="clicked(event)"
                                                class="btn btn-sm btn-success"><i
                                                class="fa fa-save"></i> Save
                                        </button>
                                        <a href="{{ url('/po_files') }}" class="btn btn-sm btn-warning"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-9"  style="overflow-x: scroll;">
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>SL</th>
                                <th>PO No.</th>
                                <th>Style</th>
                                <th>Buyer</th>
                                <th>Key</th>
                                <th>Processed</th>
                                <th>Used</th>
                                <th>Uploaded At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($po_files as $po_file)
                                <tr class="tooltip-data row-options-parent" style="{{ in_array($po_file->po_no,$alreadyReadPo) ? 'background: #d3ebff;' : '' }}">
                                    <td>{{ str_pad($loop->iteration + $po_files->firstItem() - 1, 2, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{$po_file->po_no}}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            <a  class="text-success"
                                                href="{{url("/po_files/".$po_file->id."/edit-content")}}">
                                                    <i class="fa fa-pencil-square" style ="color:#5cb85c"></i>
                                                </a>
                                                <span>|</span>

                                                <a  class="text-info"
                                                href="{{url("/po_files/".$po_file->id)}}">
                                                    <i class="fa fa-refresh" style ="color:#5bc0de;"></i>
                                                </a>

                                                <span>|</span>
                                                <a class="text-warning"
                                                href="{{url("/po_files/".$po_file->id."/download")}}">
                                                    <i class="fa fa-download" style ="color:#ec971f"></i>
                                                </a>


                                                @if($po_file->processed !== 1 && $po_file->used !== 1)
                                                    <button style="margin-right:-28px" type="button" class="btn btn-xs btn-danger show-modal"
                                                            data-toggle="modal"
                                                            data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                            ui-target="#animate"
                                                            data-url="{{ url('po_files/'.$po_file->id) }}">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                        </div>
                                    </td>
                                    <td>{{$po_file->style}}</td>
                                    <td>{{ $po_file->buyer->name ?? '' }}</td>
                                    <td>{{ $po_file->buyer_code ?? '' }}</td>
                                    <td>{{$po_file->processed === 1 ? 'Yes' : 'No'}}</td>
                                    <td>{{$po_file->used === 1 ? 'Yes' : 'No'}}</td>
                                    <td>{{$po_file->created_at->format('d/M/Y')}}</td>
                                   
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div>
                            {{ $po_files->appends(request()->except('page'))->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')
    <script>
        $(document).ready(function () {
            $(document).on('change', '#buyer_id', function () {
                let buyer_id = $(this).val();
                $('#file_issues').empty().trigger('change');
                $.ajax({
                    method: 'GET',
                    url: `/po_files/get-issues/${buyer_id}`,
                    success: function (result) {
                        $.each(result, function (key, value) {
                            let element = `<option value="${value.issue}">${value.issue}</option>`;
                            $('#file_issues').append(element);
                        })
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })
                //$('#buyer_code').val($(':selected', $(this)).data('pdf_code'));
            });


        });

        function clicked(e) {
            if (!confirm('Are you sure want to proceed? Please Confirm')) {
                e.preventDefault();
            }
        }
    </script>
@endpush
