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
                </div>
                <form action="{{url('archive-file')}}" method="POST">
                    @csrf @method('put')
                    <div class="row">
                        <input type="hidden" name="id" value="{{$archiveFile->id}}">
                        <div class="col-md-8 col-md-offset-2" id="file-upload-section">
                            <div class="m-b-1 col-md-4">
                                <label for="archive_type">Archive Type</label>
                                @php($archiveTypes=[
                                    ['key'=>'current', 'value'=>'CURRENT ORDER'],
                                    ['key'=>'previous', 'value'=>'PREVIOUS ORDER'],
                                ])
                                <select
                                    value="after"
                                    id="archive_type"
                                    name="archive_type"
                                    class="form-control form-control-sm select2-input">
                                    @foreach($archiveTypes as $type)
                                        <option value="{{$type['key']}}" {{$archiveFile->archive_type==$type['key']?'selected':''}}>
                                            {{$type['value']}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-b-1 col-md-4">
                                <label for="buyer_id">Buyer</label>
                                <input disabled
                                       class="form-control form-control-sm"
                                       value="{{$archiveFile->buyer->name}}">
                            </div>
                            <div class="m-b-1 col-md-4">
                                <label>Style</label>
                                <input disabled
                                       value="{{$archiveFile->order->style_name}}"
                                       class="form-control form-control-sm">
                            </div>
                            <div class="m-b-1 col-md-4">
                                <label>File Name</label>
                                @php($fileNames=[
                                  'Order Sheet/PO',
                                  'Final Costing',
                                  'Bulk Cutting Marker',
                                  'Actual Print/Embroidery Design Art Work',
                                  'Bulk Wash Recipe',
                                  'Final BOM'
                                ])
                                <select name="file_name"
                                        class="form-control form-control-sm select2-input">
                                    @foreach($fileNames as $filename)
                                        <option value="{{$filename}}"
                                            {{$archiveFile->file_name==$filename?'selected':''}}>
                                            {{$filename}}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="m-b-1 col-md-4">
                                <label>Remarks</label>
                                <input
                                    type="text"
                                    name="remarks"
                                    placeholder="Write Here"
                                    value="{{$archiveFile->remarks}}"
                                    class="form-control form-control-sm">
                            </div>
                            <div class="m-b-1 col-md-4">
                                <div class="row" style="margin-top: 28px;">
                                    <div class="col-sm-6">
                                        <a href="{{url('archive-file')}}"
                                            class="btn btn-sm btn-block btn-danger">
                                            Back
                                        </a>
                                    </div>
                                    <div class="col-sm-6">
                                        <button type="submit"
                                                class="btn btn-sm btn-block btn-success">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
