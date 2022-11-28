@extends('skeleton::layout')
@section('title','Sample List')
@section('content')
<div class="padding">
    @php
        $sort = request('sort') == 'desc' ? 'asc' : 'desc';
        $search = request('search') ?? null;
        $year = request('year') ?? null;
        $requisition_id = request('requisition_id') ?? null;
        $buyer_id = request('buyer_id') ?? null;
        $style_name = request('style_name') ?? null;
        $product_department_id = request('product_department_id') ?? null;
        $dealing_merchant_id = request('dealing_merchant_id') ?? null;
        $extended = "year=$year&requisition_id=$requisition_id&buyer_id=$buyer_id&style_name=$style_name&product_department_id=$product_department_id&dealing_merchant_id=$dealing_merchant_id";
    @endphp
  <div class="box">
      {!! Form::open(['url' => '/samples', 'method' => "GET"]) !!}
      <div class="box-header row" style="display: flex; justify-content: space-between;">
          <div class="col-sm-11">
              <h2>
                  Sample List
              </h2>
          </div>

      </div>
      {!! Form::close() !!}
    <div class="box-body">
      <div class="row">
        <div class="col-md-6">
          @permission('permission_of_sample_list_add')
          <a href="{{ url('/sample-requisitions/create') }}" class="btn btn-sm btn-info m-b"><i class="fa fa-plus"></i>
            Sample Entry</a>
          @endpermission
        </div>
        <div class="col-md-6">
          <div class="row">
          </div>
        </div>
      </div>
      @include('partials.response-message')
      @include('skeleton::partials.dashboard', ['dashboardOverview'=>$dashboardOverview])
      @include('skeleton::partials.row-number',['allExcel' => 'true'])
      <div class="row m-t">
        <div class="col-sm-12 "  style="overflow-x: scroll;">
          {!! Form::open(['url' => '/samples', 'method' => "GET"]) !!}
          <table class="reportTable ">
            <thead>
              <tr class="table-header" style="background-color: rgb(148, 218, 251);">
                <th>
                  <a class="btn btn-sm btn-light" href="{{  url('/samples?sort=' . $sort .'&'. $extended)}}">
                    <i class="fa {{ $sort == 'asc' ? 'fa-angle-down' : 'fa-angle-up' }}">SL</i>
                  </a>
                </th>
                <th>Year</th>
                <th>Requisition Id</th>
                <th>Buyer Name</th>
                <th>{{ localizedFor('Style') }}</th>
                <th>Product Department</th>
                <th>Dealing Merchant</th>
                <th>Sample Stage</th>
                  <th>Delivery Status</th>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td>
                  {!! Form::selectRange('year', 2020, date('Y'), request('year')  ?? null, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'All']) !!}
                </td>
                <td>
                  {!! Form::text('requisition_id', request('requisition_id') ?? null, ['class' => 'form-control form-control-sm']) !!}
                </td>
                <td>
                  {!! Form::select('buyer_id', $buyers ?? [], request('buyer_id') ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'All']) !!}
                </td>
                <td>
                  {!! Form::text('style_name', request('style_name') ?? null, ['class' => 'form-control form-control-sm']) !!}
                </td>
                <td>
                  {!! Form::select('product_department_id', $product_departments ?? [], request('product_department_id') ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'All']) !!}
                </td>
                <td>
                  {!! Form::select('dealing_merchant_id', $dealing_merchants ?? [], request('dealing_merchant_id') ?? null, ['class' => 'form-control form-control-sm', 'placeholder' => 'All']) !!}
                </td>
                <td>
                  {!! Form::select('sample_stage', $sample_stages ?? [], request('sample_stage') ?? null, ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'All']) !!}
                </td>

                <td>
                  <button type="submit" class="btn btn-primary btn-xs"><i class="fa fa-search"></i></button>
                </td>
              </tr>
            </thead>
            <tbody>
              @if($samples && count($samples))
                @foreach($samples as $sample)
                <tr class="tooltip-data row-options-parent">
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $sample->year }}</td>
                  <td>{{ $sample->requisition_id }}
                    <br>
                    <div class="row-options" style="display:none ">

                      <a class="text-info" title="Sample View No. 1" target="_blank"
                      href="{{ url('/samples/' . $sample->id ) }}">
                        <i class="fa fa-eye"></i>

                      </a>
                      <span>|</span>
                        <a class="text-primary" title="Sample View No. 2" target="_blank"
                          href="{{ url('/samples/v2/' . $sample->id ) }}">
                            <i class="fa fa-eye"></i>
                        </a>
                      <span>|</span>
                      <a class="text-success" title="Edit Sample"
                          href="{{ url('/sample-requisitions/' . $sample->id .'/edit' ) }}">
                          <i class="fa fa-edit"></i>
                      </a>
                    </div>


                  </td>
                  <td>{{ $sample->buyer->name }}</td>
                  <td class="text-left">{{ $sample->style_name }}</td>
                  <td>{{ $sample->department->product_department }}</td>
                  <td class="text-left">{{ $sample->merchant->full_name }}</td>
                  <td>{{ $sample->stage }}</td>
                    <td>
                        @if($sample->delivery_date)
                            <i class="fa fa-check text-success"></i>
                        @else
                            <i class="fa fa-spinner"></i>
                        @endif
                    </td>

                </tr>
                @endforeach
              @endif
            </tbody>
          </table>
          {!! Form::close() !!}
        </div>
      </div>
      <div class="row m-t">
        <div class="col-sm-12">
          {{ $samples->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
  </div>
@endsection

@section('scripts')
<script>

  $(document).ready(function () {
        $(document).on('click', '#excel_all', function () {
            let link = `{{  url('/samples/excel-list-all?sort=' . $sort .'&'. $extended)}}`;
            window.open(link, '_blank');
        });
  });

  $("#selectOption").change(function(){
      var selectBox = document.getElementById("selectOption");
      var selectedValue = (selectBox.value);
      if (selectedValue == -1){
          if(window.location.href.indexOf("search") != -1){
              selectedValue = {{$searchedSamples}};
      }
          else{
              selectedValue = {{$dashboardOverview["Total SampleRequisision"]}};
          }
      }
      let url = new URL(window.location.href);
      url.searchParams.set('paginateNumber',parseInt(selectedValue));
      window.location.replace(url);
  });

  $buyerSelectDom = $('[name="buyer_id"]');
  $productDepartmentSelectDom = $('[name="product_department_id"]');
  $dealingMerchantSelectDom = $('[name="dealing_merchant_id"]');
  $(function() {
    $buyerSelectDom.select2({
        ajax: {
          url: '/utility/get-buyers-for-select2-search',
          data: function (params) {
            return {
              search: params.term,
            }
          },
          processResults: function (data, params) {
            return {
              results: data.results,
              pagination: {
                more: false
              }
            }
          },
          cache: true,
          delay: 250
        },
        placeholder: 'All',
        allowClear: true
      });
    $productDepartmentSelectDom.select2({
        ajax: {
          url: '/product-department/select-search',
          data: function (params) {
            return {
              search: params.term,
            }
          },
          processResults: function (data, params) {
            return {
              results: data.results,
              pagination: {
                more: false
              }
            }
          },
          cache: true,
          delay: 250
        },
        placeholder: 'All',
        allowClear: true
      });
    $dealingMerchantSelectDom.select2({
        ajax: {
          url: '/users/select-search',
          data: function (params) {
            return {
              search: params.term,
            }
          },
          processResults: function (data, params) {
            return {
              results: data.results,
              pagination: {
                more: false
              }
            }
          },
          cache: true,
          delay: 250
        },
        placeholder: 'All',
        allowClear: true
      });
  })
</script>
@endsection
