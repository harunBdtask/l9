@extends('skeleton::layout')
@section("title","Tech Pack Files")

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
                <h2>Tech Pack Files</h2>
            </div>

            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3 col-sm-offset-9">
                        <form action="{{ url('/tech-pack-files') }}" method="GET">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="search"
                                       value="{{ request()->query('search') ?? '' }}" placeholder="Search">
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
                @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])

                <div class="row m-t">
                    <div class="col-sm-12 col-md-4">
                        <div class="box">
                            <div class="box-header">
                                <form action="{{ url('/tech-pack-files') }}" method="post" id="form"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="style">Style <span class="text-danger req">*</span></label>
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
                                        <label for="creeper_count">Body/Set <span class="text-danger req">*</span>
                                            <span class="text-accent"> (E.X : 1 )</span>
                                        </label>
                                        <input type="text" class="form-control form-control-sm" name="creeper_count"
                                               id="creeper_count"/>
                                        @error('creeper_count')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label for="body_part_count">Body Part Count <span
                                                class="text-danger req">*</span>
                                            <span class="text-accent">(E.X : A,B,C )</span>
                                        </label>
                                        <input type="text" class="form-control form-control-sm" name="body_part_count"
                                               id="body_part_count"/>
                                        @error('body_part_count')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="form-group">
                                        <label for="po_no">File <span class="text-danger req">*</span> <span
                                                style="font-size: 12px;color: #1c4af3;">[<a
                                                    href="https://pdfresizer.com/delete-pages"
                                                    target="_blank">Resize</a>]</span></label>
                                        <input type="file" id="file" name="file"
                                               class="form-control form-control-sm">
                                        @error('file')
                                        <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>


                                    <div class="form-group text-center">
                                        <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                class="fa fa-save"></i> Save
                                        </button>
                                        <a href="{{ url('/tech-pack-files') }}" class="btn btn-sm btn-warning"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                        <button type="button" id="download-sample" class="btn btn-sm btn-info">
                                            <i class="fa fa-download"></i> Sample Download
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12 col-md-8" style="overflow-x: scroll;">
                        <table class="reportTable">
                            <thead>
                            <tr class="table-header">
                                <th>SL</th>
                                <th>Style</th>
                                <th>Creeper Count</th>
                                <th>Body Part Count</th>
                                <th>Processed</th>
                                <th>Used</th>
                                <th>Uploaded At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($tech_pack_files as $file)
                                <tr class="tooltip-data row-options-parent">
                                    <td>{{str_pad($loop->iteration + $tech_pack_files->firstItem() - 1, 2, '0', STR_PAD_LEFT)}}</td>
                                    <td>{{$file->style}}
                                        <br>
                                        <div class="row-options" style="display:none ">
                                            {{--                                        @if($file->processed !== 1 && $file->used !== 1)--}}
                                                {{--                                            <button type="button" class="text-danger show-modal"--}}
                                                {{--                                                    data-toggle="modal"--}}
                                                {{--                                                    data-target="#confirmationModal" ui-toggle-class="flip-x"--}}
                                                {{--                                                    ui-target="#animate"--}}
                                                {{--                                                    data-url="{{ url('tech-pack-files/'.$file->id) }}">--}}
                                                {{--                                                <i class="fa fa-trash"></i>--}}
                                                {{--                                            </button>--}}
                                                {{--                                        @endif--}}


                                                <a style="margin-left: 2px;" class="text-success"
                                                href="{{url("/tech-pack-files/".$file->id."/edit")}}">
                                                    <i class="fa fa-pencil-square"></i>
                                                </a>
                                                <span>|</span>
                                                <a style="margin-left: 2px;" class="text-primary"
                                                href="{{url("/tech-pack-content/".$file->id)}}">
                                                    <i class="fa fa-eye " style="color:#0275d8"></i>
                                                </a>
                                                <span>|</span>
                                                <a style="margin-left: 2px;" class="text-warning"
                                                href="{{url("/tech-pack-files/".$file->id."/download")}}">
                                                    <i class="fa fa-download" style="color:#d58512"></i>
                                                </a>

                                                <span>|</span>
                                                {{ Form::open(['url'=>"/tech-pack-process/$file->id",'method'=>'PUT','style'=>'display: inline;']) }}
                                                <a href="" type="submit" style="margin-left: 2px;" class="text-info">
                                                    <i class="fa fa-refresh"></i>
                                                </a>
                                                {{Form::close()}}
                                                <span>|</span>

                                                <a href="{{ url('tech-pack-files/'.$file->id) }}" type="button" class="text-danger show-modal"
                                                        data-toggle="modal"
                                                        data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                        ui-target="#animate"
                                                        data-url="{{ url('tech-pack-files/'.$file->id) }}">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                        </div>
                                    </td>
                                    <td>{{$file->creeper_count}}</td>
                                    <td>{{$file->body_part_count}}</td>
                                    <td>{{$file->processed === 1 ? 'Yes' : 'No'}}</td>
                                    <td>{{$file->used === 1 ? 'Yes' : 'No'}}</td>
                                    <td>{{$file->created_at->format('d/M/Y')}}</td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" align="center">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div>
                            {{ $tech_pack_files->appends(request()->except('page'))->links() }}
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
            let queryString = new URLSearchParams({
                style: $("#style").val(),
                creeper_count: $("#creeper_count").val(),
                body_part_count: $("#body_part_count").val()
            });

            let url = "{{ url('/tech-pack-files/sample-download') }}?" + queryString;

            window.location.assign(url);
        });
    </script>
@endpush
