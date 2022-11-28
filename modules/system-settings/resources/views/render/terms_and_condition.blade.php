<form action="{{ url('/terms-conditions') }}/{{ $term->id }}" method="post">
    @csrf
    @method('PUT')
    <div class="row">
        <div class="col-sm-12 col-md-7">
            <div class="form-group">
                <label for="buyer_id">Page Name</label>
                <select name="page_name" id="page_name" class="form-control form-control-sm form-control form-control-sm-sm" required>
                    <option value="">Select Page</option>
                    @foreach($pages as $key => $page_name)
                        <option value="{{ $key }}"  {{ $term['page_name'] == $key  ? 'selected' : null }}>{{ $page_name }}</option>
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
                    <th>SL</th>
                    <th>Terms</th>
{{--                    <th>Action</th>--}}
                </tr>
                </thead>
                <tbody id="terms_add">
                <tr>
                    <td>*</td>
                    <td>
                        <input type="text" name="terms_name" value="{{ $term['terms_name'] }}"
                               class="form-control form-control-sm form-control form-control-sm-sm"
                               placeholder="Terms Name">
                    </td>
{{--                    <td>--}}
{{--                        <i style="cursor: pointer"--}}
{{--                           class="fa fa-plus element_add btn btn-sm btn-primary"></i>--}}
{{--                        <i style="cursor: pointer"--}}
{{--                           class="fa fa-minus element_remove btn btn-sm btn-warning"></i>--}}
{{--                    </td>--}}
                </tr>
                </tbody>
            </table>
            <div class="text-left">
                <button type="submit" id="action_button" class="btn btn-sm btn-success"><i
                            class="fa fa-save"></i>
                    Update
                </button>
                <a href="{{ url('/terms-conditions') }}" class="btn btn-sm btn-warning"><i
                            class="fa fa-refresh"></i> Refresh</a>
            </div>
        </div>
    </div>
</form>
