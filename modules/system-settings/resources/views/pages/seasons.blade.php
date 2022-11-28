@extends('skeleton::layout')
@section("title","Seasons")
@section('content')
    <div class="padding">
        <div class="box" >
            <div class="box-header">
                <h2>Season</h2>
            </div>
            <div class="row m-t">
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
            <div class="box-body" >
                <div class="sr-only">
                    <table>
                        <tbody id="target_data">
                        <tr>
                            <td>
                                <input type="text" name="season_name[]"
                                       class="form-control form-control-sm form-control form-control-sm-sm"
                                       placeholder="Season Name">
                            </td>
                            <td>
                                <select name="year_from[]" id="role"
                                        class="form-control form-control-sm form-control form-control-sm-sm">
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select name="year_to[]" id="role"
                                        class="form-control form-control-sm form-control form-control-sm-sm">
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <i style="cursor: pointer"
                                   class="fa fa-plus element_add btn btn-sm btn-primary"></i>
                                <i style="cursor: pointer"
                                   class="fa fa-minus element_remove btn btn-sm btn-warning"></i>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="row" >
                    <div class="col-sm-12 col-md-6 form-colors" id="season_form">
                        <form action="{{ url('/seasons') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-sm-12 col-md-6">
                                    <div class="form-group">
                                        <label for="buyer_id">Buyer Name</label>
                                        <select name="buyer_id" id="buyer_id"
                                                class="form-control form-control-sm form-control form-control-sm-sm"
                                                required>
                                            <option value="">Select Buyer</option>
                                            @foreach($buyers as $buyer)
                                                <option value="{{ $buyer->id }}" {{ old('buyer_id') == $buyer->id ? 'selected' : '' }}>{{ $buyer->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-12 col-md-6" >
                                    <div class="form-group">
                                        <label for="factory_id">Company Name</label>
                                        <select name="factory_id" id="factory_id"
                                                class="form-control form-control-sm form-control form-control-sm-sm"
                                                required>
                                            <option value="">Select Company</option>
                                            @foreach($factories as $factory)
                                                <option value="{{ $factory->id }}" {{ old('factory_id') == $factory->id ? 'selected' : '' }} >{{ $factory->factory_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <table class="table table-bordered season-form">
                                        <thead>
                                        <tr>
                                            <th>Season Name</th>
                                            <th>Year From</th>
                                            <th>Year To</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody id="season_add">
                                          @if (old('season_name'))
                                            @foreach (old('season_name') as $s_key => $s_val)
                                              <tr>
                                                <td>
                                                    <input type="text" name="season_name[{{$s_key}}]"
                                                          class="form-control form-control-sm form-control form-control-sm-sm"
                                                          placeholder="Season Name" value="{{ $s_val }}">
                                                    @error('season_name.'.$s_key)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    @error('season_name')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <select name="year_from[{{$s_key}}]" id="role"
                                                            class="form-control form-control-sm form-control form-control-sm-sm">
                                                        @foreach($years as $year)
                                                            <option value="{{ $year }}" {{ old('year_from')[$s_key] == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('year_from.'.$s_key)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    @error('year_from')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <select name="year_to[{{$s_key}}]" id="role"
                                                            class="form-control form-control-sm form-control form-control-sm-sm">
                                                        @foreach($years as $year)
                                                            <option value="{{ $year }}" {{ old('year_to')[$s_key] == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('year_to.'.$s_key)
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                    @error('year_to')
                                                    <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <i style="cursor: pointer"
                                                      class="fa fa-plus element_add btn btn-sm btn-primary"></i>
                                                    <i style="cursor: pointer"
                                                      class="fa fa-minus element_remove btn btn-sm btn-warning"></i>
                                                </td>
                                            </tr>
                                            @endforeach
                                          @else
                                            <tr>
                                              <td>
                                                  <input type="text" name="season_name[]"
                                                        class="form-control form-control-sm form-control form-control-sm-sm"
                                                        placeholder="Season Name">
                                              </td>
                                              <td>
                                                  <select name="year_from[]" id="role"
                                                          class="form-control form-control-sm form-control form-control-sm-sm">
                                                      @foreach($years as $year)
                                                          <option value="{{ $year }}">{{ $year }}</option>
                                                      @endforeach
                                                  </select>
                                              </td>
                                              <td>
                                                  <select name="year_to[]" id="role"
                                                          class="form-control form-control-sm form-control form-control-sm-sm">
                                                      @foreach($years as $year)
                                                          <option value="{{ $year }}">{{ $year }}</option>
                                                      @endforeach
                                                  </select>
                                              </td>
                                              <td>
                                                  <i style="cursor: pointer"
                                                    class="fa fa-plus element_add btn btn-sm btn-primary"></i>
                                                  <i style="cursor: pointer"
                                                    class="fa fa-minus element_remove btn btn-sm btn-warning"></i>
                                              </td>
                                          </tr>
                                          @endif
                                        </tbody>
                                    </table>
                                    <div class="form-group">
                                        <button type="submit" id="action_button" class="btn btn-sm white"><i
                                                class="fa fa-save"></i>
                                            Save
                                        </button>
                                        <a href="{{ url('/seasons') }}" class="btn btn-sm btn-dark"><i
                                                class="fa fa-refresh"></i> Refresh</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-md-6">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            {!! Form::open(['url' => 'seasons', 'method' => 'GET']) !!}
                            <div class="input-group">
                                <input type="text" class="form-control form-control-sm" name="q"
                                       value="{{ request('q') ?? '' }}" placeholder="Search">
                                <span class="input-group-btn">
                                    <button class="btn btn-sm white m-b" type="submit">Search</button>
                                </span>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Buyer Name</th>
                                <th>Season</th>
                                <th>Year From</th>
                                <th>Year To</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($seasons as $season)
                                @foreach($season->details as $detail)
                                    <tr>
                                        @if($loop->first)
                                            <td rowspan="{{ count($season->details) }}">{{ $season->factory ? $season->factory->factory_name : ''}}</td>
                                            <td rowspan="{{ count($season->details) }}">{{ $season->buyer ? $season->buyer->name : '' }}</td>
                                        @endif
                                        <td>{{ $detail['season'] }}</td>
                                        <td>{{ $detail['year_from'] }}</td>
                                        <td>{{ $detail['year_to'] }}</td>
                                        @if($loop->first)
                                            <td align="center" rowspan="{{ count($season->details) }}">
                                                <i style="cursor: pointer"
                                                   class="fa fa-edit season_edit"
                                                   data-url="{{url('seasons/edit/'.$season->factory_id.'/'.$season->buyer_id)}}"></i>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td align="center" colspan="6">No Data Found</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        {{--                        <div class="text-center">--}}
                        {{--                            {{ $teams->appends(request()->except('page'))->links() }}--}}
                        {{--                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script-head')
    <script>
        $(document).on('click', '.element_add', function () {
            let seasonList=[];
            $("input[name='season_name[]']").each(function() {
                if(this.value) seasonList.push(this.value);
            });
            const uniqList = new Set(seasonList);
            if(seasonList.length === uniqList.size){
                $('.season-form').find('tbody:last').append($('#target_data').html());
            }else{
                alert('Duplicate Season Name')
            }
        });

        $(document).on('click', '.element_remove', function () {
            let length = $('.season-form tr').length;
            if (length < 3) {
                alert('Last row can`t be deleted');
                return false;
            }
            $(this).closest('tr').remove();
        });

        $(document).on('click', '.season_edit', function () {
            const url = $(this).data('url');
            $.ajax({
                url: url,
                method: 'get',
                success: function (result) {
                    if (result.status == 'error') {
                        alert(result.message)
                    } else {
                        $('#season_form').html('').html(result);
                    }
                },
                error: function (xhr) {
                    console.log(xhr)
                }
            })
        });
    </script>
@endpush
