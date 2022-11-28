<form action="{{ url('/page-wise-view-permission/update') }}/{{ $pageWiseViews->id }}" method="post">
    @csrf
    @method('PUT')
    <table class="reportTable">
        <thead>
        <tr>
            <td>Company</td>
            <td>Buyer</td>
            <td>Select Page</td>
            <td>Select Print</td>
            <td>Action</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="width: 200px">
                <select name="company_id" style="height: 40px; width: 200px;"
                        class="form-control form-control-sm select2-input">

                    @foreach($companies as $key => $company)
                        <option
                            value="{{ $key }}" {{  $pageWiseViews->company_id == $key ? 'selected' : null }}>{{ $company }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td style="max-width: 350px">
                <select name="buyer_id[]" style="height: 40px;" class="form-control form-control-sm select2-input"
                        multiple id="select_buyer_id"
                        data-select="true"
                >
                    <option
                        value="All" {{ in_array('All', request()->buyer_id ?? []) ? 'selected' : null }}>
                        Select All

                    </option>
                    @foreach($buyers as $key => $buyer)
                        <option
                            value="{{ $key }}" {{ in_array($key, $selectedBuyer ?? []) ? 'selected' : null }}>{{ $buyer }}
                        </option>
                    @endforeach
                </select>

            </td>
            <td style="width: 200px">
                <select name="page_id" style="height: 40px; width: 200px;"
                        class="form-control form-control-sm select2-input" id="page_id">
                    @foreach(($pages) as $key => $page)
                        <option
                            value="{{ $key }}" {{ $pageWiseViews->page_id == $key ? 'selected' : null }}>{{ $page }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td style="width: 200px">
                <select name="view_id[]" style="height: 40px; width: 200px;"
                        class="form-control form-control-sm select2-input" multiple  id="view_id">
                    @foreach($views as $key => $view)
                        <option
                            value="{{ $key }}"
                           {{ in_array($view, $selectedView ?? []) ? 'selected' : null }}
                            >
                            {{ $view }}
                        </option>
                    @endforeach
                </select>
            </td>
            <td style="width: 100px">
                <button class="btn btn-xs btn-success">update</button>
            </td>
        </tr>
        </tbody>
    </table>

</form>

<script>
    $('select.select2-input').select2();
</script>
