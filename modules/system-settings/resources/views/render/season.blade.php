<form action="{{ url('/seasons/update') }}/{{ $factoryId }}/{{ $buyerId }}" method="post">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="buyer_id">Buyer Name</label>
                <select name="buyer_id" id="buyer_id" class="form-control form-control-sm form-control form-control-sm-sm" required>
                    <option value="">Select Buyer</option>
                    @foreach($buyers as $buyer)
                        <option value="{{ $buyer->id }}" {{ $buyer->id == $buyerId ? 'selected' : null }}>{{ $buyer->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="form-group">
                <label for="factory_id">Company Name</label>
                <select name="factory_id" id="factory_id"
                        class="form-control form-control-sm form-control form-control-sm-sm" required>
                    <option value="">Select Company</option>
                    @foreach($factories as $factory)
                        <option value="{{ $factory->id }}" {{ $factory->id == $factoryId ? 'selected' : null }}>{{ $factory->factory_name }}</option>
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
                @foreach($season as $season)
                    <tr>
                        <td>
                            <input type="hidden" name="id[]" value="{{ $season['id'] }}">
                            <input type="text" name="season_name[]" value="{{ $season['season'] }}" class="form-control form-control-sm form-control form-control-sm-sm" placeholder="Season Name">
                        </td>
                        <td>
                            <select name="year_from[]" class="form-control form-control-sm form-control form-control-sm-sm">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $season['year_from'] == $year ? 'selected' : null }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <select name="year_to[]" class="form-control form-control-sm form-control form-control-sm-sm">
                                @foreach($years as $year)
                                    <option value="{{ $year }}" {{ $season['year_to'] == $year ? 'selected' : null }}>{{ $year }}</option>
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
                @endforeach
                </tbody>
            </table>
            <div class="text-right">
                <a href="{{ url('/seasons') }}" class="btn btn-sm btn-warning"><i
                        class="fa fa-refresh"></i> Refresh</a>
                <button type="submit" id="action_button" class="btn btn-sm btn-success"><i
                        class="fa fa-save"></i>
                    Update
                </button>
            </div>
        </div>
    </div>
</form>
