@extends('cuttingdroplets::layout')
@section('title', 'Cutting Production Report V2')
@section('content')
<div class="padding">
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header text-center">
          <h2>Cutting Production Report V2 || {{ date("jS F, Y") }}
            <span class="pull-right">
              <i data-url="/cutting-production-report-v2/pdf" style="color: #DC0A0B; cursor: pointer"
                class="text-danger fa fa-file-pdf-o downloadBtn"></i>
              |
              <i data-url="/cutting-production-report-v2/xls" style="cursor: pointer"
                class="text-success downloadBtn fa fa-file-excel-o"></i>
            </span>
          </h2>
        </div>
        <div class="box-divider m-a-0"></div>
        <div class="box-body">
          <form action="{{ url('/cutting-production-report-v2') }}" method="post" id="searchReportForm">
            <div class="form-group">
              <div class="row m-b">
                <div class="col-sm-3">
                  <select class="form-control form-control-sm select2-input" name="buyer_id" id="buyer_id">
                    <option disabled selected hidden>Select Buyer</option>
                    @foreach($buyers as $buyer)
                    <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-1">
                  <button type="submit" class="btn btn-sm white form-control form-control-sm">
                    Search
                  </button>
                </div>
              </div>
            </div>
          </form>
          <div id="parentTableFixed" class="table-responsive">
            <table class="reportTable" id="fixTable">
              <thead>
                <tr>
                  <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Buyer</td>
                  <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Mer. Name</td>
                  <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Item</td>
                  <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Style</td>
                  <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Ref. No</td>
                  <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Fab. Type</td>
                  <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Color</td>
                  <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Order Qty</td>
                  <td style="text-align: center; font-weight: bold;background-color: #a1c9ed" colspan="5">Cutting</td>
                  <td style="text-align: center; font-weight: bold;background-color: aliceblue" colspan="6">Print</td>
                  <td style="text-align: center; font-weight: bold;background-color: #a1c9ed" colspan="6">Embroidery
                  </td>
                  <td style="text-align: center; font-weight: bold;background-color: aliceblue" colspan="3">Input</td>
                  <td style="font-weight: bold;background-color: aliceblue" rowspan="2">Remarks</td>
                </tr>
                <tr>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Today Cut</th>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Total Cut</th>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Cut. Rej.</th>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Ok Cut</th>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Cut Blnc.</th>
                  <th style="background-color: aliceblue; font-weight: bold;">Today Print Send</th>
                  <th style="background-color: aliceblue; font-weight: bold;">Total Print Send</th>
                  <th style="background-color: aliceblue; font-weight: bold;">Send Print Blnc.</th>
                  <th style="background-color: aliceblue; font-weight: bold;">Today Print Rec.</th>
                  <th style="background-color: aliceblue; font-weight: bold;">Total Print Rec.</th>
                  <th style="background-color: aliceblue; font-weight: bold;">Rec. Print Blnc.</th>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Today Embr Send</th>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Total Embr Send</th>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Send Embr Blnc.</th>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Today Embr Rec.</th>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Total Embr Rec.</th>
                  <th style="background-color: #a1c9ed; font-weight: bold;">Rec. Embr Blnc</th>
                  <th style="background-color: aliceblue; font-weight: bold;">Today Input</th>
                  <th style="background-color: aliceblue; font-weight: bold;">Total Input</th>
                  <th style="background-color: aliceblue; font-weight: bold;">Input Blnc.</th>
                </tr>
              </thead>
              <tbody class="report-body">

              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
<script>
  $(document).on('submit', '#searchReportForm', function (e) {
    e.preventDefault();
    let form = $(this).serializeArray();
    $.ajax({
      url: "/cutting-production-report-v2",
      type: "post",
      data: form,
      dataType: "html",
      success(response) {
        $(".report-body").html(response);
      }
    })
  })

  $(document).on('click', '.downloadBtn', function () {
    let url = $(this).data('url');
    let buyer_id = $("#buyer_id").val();
    url += ("?buyer_id=" + buyer_id)
    location.assign(url);
  });
</script>
@endsection