<table class="reportTable">
    <thead>
    <tr>
        <th><b>Sl</b></th>
        <th><b>Name</b></th>
        <th><b>Category</b></th>
        <th><b>Brand</b></th>
        <th><b>UoM</b></th>
        <th><b>Store</b></th>
        <th><b>Prefix</b></th>
    </tr>
    </thead>
    <tbody>
    @forelse($items as $item)
        @include('settings::pages.items_table_row')
    @empty
        <tr>
            <td colspan="4" align="center">No Data</td>
        </tr>
    @endforelse
    </tbody>
</table>
