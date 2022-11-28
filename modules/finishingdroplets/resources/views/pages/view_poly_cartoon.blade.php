@extends('finishingdroplets::layout')

@section('content')
  <div class="padding">
    <div class="box">
      <div class="box-header">
        <h2>Poly & Cartoon List</h2>
      </div>
      <div class="box-body b-t">
        {{--@if(Session::has('permission_of_buyers_add') || getRole() == 'super-admin') --}}
        <div class="add-btn-style">
          <a class="btn btn-sm white m-b" href="{{ url('poly-cartoons/create') }}">
            <i class="glyphicon glyphicon-plus"></i> New Poly & Cartoon
          </a>
        </div>
        {{-- @endif --}}
        <div class="js-response-message text-center"></div>
        <table class="reportTable">
          <thead>
          <tr>
            <th>SL</th>
            <th>Buyer Name</th>
            <th>Style/ Order No</th>
            <th>PO</th>
            <th>Color Name</th>
            <th>Size Name</th>
            <th>Recv. Qty</th>
            <th>Poly Qty</th>
            <th>Qty per poly</th>
            <th>Cartoon Qty</th>
            <th>Floor</th>
            <th>Created Date</th>
            <th>Action</th>
          </tr>
          </thead>
          <tbody>
          @if(!$poly_cartoons->getCollection()->isEmpty())
            @foreach($poly_cartoons->getCollection() as $poly)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $poly->buyer->name ?? 'N/A' }}</td>
                <td>{{ $poly->order->order_style_no ?? 'N/A' }}</td>
                <td>{{ $poly->purchaseOrder->po_no ?? 'N/A' }}</td>
                <td>{{ $poly->color->name ?? 'N/A' }}</td>
                <td>{{ $poly->size->name ?? 'N/A' }}</td>
                <td>{{ $poly->received_qty ?? 'N/A' }}</td>
                <td>{{ $poly->poly_qty ?? 0 }}</td>
                <td>{{ $poly->qty_per_poly ?? 'N/A' }}</td>
                <td>{{ $poly->cartoon_qty ?? 'N/A' }}</td>
                <td>{{ $poly->floor->floor_no ?? 'N/A' }}</td>
                <td>{{ date('Y-m-d', strtotime($poly->created_at)) }}</td>
                <td>
                  <button type="button" value="{{ $poly->id }}" class="btn btn-sm white delete-poly-cartoon-btn">
                    <i class="fa fa-times"></i>
                  </button>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="13" align="center">No Poly
              <td>
            </tr>
          @endif
          </tbody>
          <tfoot>
          @if($poly_cartoons->total() > 15)
            <tr>
              <td colspan="13"
                  class="text-center">{{ $poly_cartoons->appends(request()->except('page'))->links() }}</td>
            </tr>
          @endif
          </tfoot>
        </table>
      </div>
    </div>
  </div>
@endsection
@section('scripts')
  <script>
    $(document).on('click', '.delete-poly-cartoon-btn', function () {
      if (confirm('Do you want to delete?') == true) {
        var current = $(this);
        var id = current.val();
        if (id) {
          $.ajax({
            type: 'DELETE',
            url: '/delete-poly/' + id,
            success: function (response) {
              if (response == 200) {
                current.parents('tr').remove();
                $('.js-response-message').html(getMessage(D_SUCCESS, 'success')).fadeIn().delay(2000).fadeOut(2000);
              } else {
                var message = response.message ? response.message : D_FAIL;
                $('.js-response-message').html(getMessage(message, 'danger')).fadeIn().delay(2000).fadeOut(2000);
              }
            }
          });
        }
      }
    });
  </script>
@endsection
