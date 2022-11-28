<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $item->name }}</td>
    <td>{{ $item->category->name ?? '' }}</td>
    <td>{{ $item->brand->name ?? '' }}</td>
    <td>{{ $item->uomDetails->name }}</td>
    <td>{{ $item->store_details->name }}</td>
    <td>{{ $item->prefix }}</td>
</tr>