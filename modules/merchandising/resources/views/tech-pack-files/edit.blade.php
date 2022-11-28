@extends('skeleton::layout')
@section("title","Tech Pack Files")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Tech Pack Update</h2>
            </div>

            <div class="box-body table-responsive">
                <form action="{{ url('/tech-pack-files/' . $techPackFile->id) }}" method="POST">
                    @method('PUT')
                    @csrf
                    <table class="reportTable text-nowrap">
                        <thead>
                        <tr>
                            <th style="width: 40px">Sl</th>
                            <th>Style Name</th>
                            <th>Team</th>
                            @foreach(collect($techPackFile->contents)->pluck('creeper')->unique() as $creeper)
                                <th>{{ $creeper }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(collect($techPackFile->contents)->where('contrast_color', null)->groupBy('color') as $key => $content)
                            <tr>
                                <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ collect($content)->first()->style }}</td>
                                <td>{{ collect($content)->first()->color }}</td>
                                @foreach(collect($techPackFile->contents)->pluck('creeper')->unique() as $creeper)
                                    @php
                                        $parentId = collect($prevColor)->where('name',collect($content)->first()->color)->first();
                                        $value = collect($prevColor)->where('style',collect($content)->first()->style)
                                                ->where('tag',$creeper)
                                                ->where('parent_id',$parentId['id'])->first();
                                    @endphp
                                    <th>
                                        <input type="hidden" name="colors[]"
                                               value="{{ collect($content)->first()->color }}">
                                        <input type="hidden" name="styles[]"
                                               value="{{ collect($content)->first()->style }}">
                                        <input type="hidden" name="creepers[]" value="{{ $creeper }}">
                                        <input name="contrast_colors[]" value="{{ $value['name'] ?? '' }}" type="text"
                                               class="form-control form-control-sm">
                                    </th>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <button class="btn btn-sm btn-success pull-right"><i class="fa fa-save"></i> Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection
