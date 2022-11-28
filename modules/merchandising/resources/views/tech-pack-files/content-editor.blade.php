@extends('skeleton::layout')
@section("title","PO File Content Edit")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Tech Pack File Content Edit</h2>
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
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-toggle="tab" href="#data-table-tab" role="tab">Data
                                    Table</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#raw-data-tab" role="tab">File Content (
                                    <small
                                            class="label bg-info">{{ $tech_pack->style }}</small> )</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="data-table-tab" role="tabpanel">
                                <form action="{{ url('/tech-pack-content-update/'.$tech_pack->id) }}" method="post">
                                    @csrf
                                    <table class="reportTable">
                                        <tr>
                                            <th colspan="2">Creeper</th>
                                            <th>Color</th>
                                            <th>Style</th>
                                            <th>Contrast Color</th>
                                        </tr>
                                        @forelse($collection as $key => $data)
                                            @php
                                                $newGroup = $data->groupBy(function ($item, $key) {
                                                    return substr($item['creeper'], -3);
                                                });
                                                $creeperGroupRow = true;
                                            @endphp
                                            @foreach($newGroup as $midKey => $group)
                                                @foreach($group as $lastKey => $value)
                                                    <input type="hidden" value="{{ $value['creeper'] }}"
                                                           name="creeper[]">
                                                    <tr>
                                                        @if($creeperGroupRow)
                                                            <td rowspan="{{ count($data) }}"><b>{{ $key }}</b></td>
                                                        @endif
                                                        @if($loop->first)
                                                            <td rowspan="{{ count($group) }}" style="width: 10%">
                                                                <b>{{ substr($midKey, -1) }}</b></td>
                                                        @endif
                                                        <td><input class="form-control form-control-sm text-center"
                                                                   name="color[]" value="{{ $value['color'] }}"></td>
                                                        <td><input class="form-control form-control-sm text-center"
                                                                   readonly
                                                                   name="style[]" value="{{ $value['style'] }}"></td>
                                                        <td><input class="form-control form-control-sm text-center"
                                                                   name="contrast_color[]"
                                                                   value="{{ $value['contrast_color'] }}"></td>
                                                    </tr>
                                                    @php
                                                        $creeperGroupRow = false;
                                                    @endphp
                                                @endforeach
                                            @endforeach
                                        @empty
                                            <tr>
                                                <th class="text-center">No data found!</th>
                                            </tr>
                                        @endforelse

                                    </table>
                                    <div class="form-group">
                                        <div class="text-right">
                                            <a href="{{ url('/tech-pack-files') }}" class="btn btn-sm btn-warning"><i
                                                        class="fa fa-refresh"></i> Refresh</a>
                                            <button type="submit" id="submit" class="btn btn-sm btn-success"><i
                                                        class="fa fa-save"></i> Save
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="raw-data-tab" role="tabpanel">
                                <form action="{{ url('/tech-pack-content-update/'.$tech_pack->id) }}" method="post"
                                      id="form">
                                    @csrf
                                    <div class="form-group">
                                        <textarea name="content" style="min-height: 400px;"
                                                  class="form-control form-control-sm">{{$content}}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <div class="text-right">
                                            <a href="{{ url('/tech-pack-files') }}" class="btn btn-sm btn-warning"><i
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
        </div>
    </div>
@endsection
