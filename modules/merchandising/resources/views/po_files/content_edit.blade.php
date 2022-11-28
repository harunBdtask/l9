@extends('skeleton::layout')
@section("title","PO File Content Edit")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>PO File Content Edit</h2>
            </div>

            <div class="box-body">
                <div class="row">
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
                    <div class="col-sm-12">
                        <form action="{{ url('/po_files/'.$po_file->id."/content-update") }}" method="post" id="form">
                            @csrf
                            <div class="form-group">
                                <label for="po_no">PO File Content : {{ $po_file->po_no }}
                                    , {{ $po_file->style }}</label>
                                <textarea name="content" style="min-height: 400px;"
                                          class="form-control form-control-sm">{{$content}}</textarea>
                            </div>

                            <div class="form-group">
                                <div class="text-right">
                                    <a href="{{ url('/po_files') }}" class="btn btn-sm btn-warning"><i
                                            class="fa fa-refresh"></i> Refresh</a>
                                    <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                            class="fa fa-save"></i> Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script-head')

@endpush
