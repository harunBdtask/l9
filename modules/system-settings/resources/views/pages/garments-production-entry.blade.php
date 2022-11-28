@extends('skeleton::layout')
@section("title","Application Variables")
@section('content')
<div class="padding">
  <div class="box">
    <div class="box-header">
      <div style="display: flex; justify-content: space-between; align-items: center;">
        <h2>Application Variables</h2>
        @if(getRole() == 'super-admin')
        <a href="{{ url('/application-menu-inactive') }}" class="btn btn-sm btn-info"><i class="fa fa-arrow-right"></i> Go to Application Menu Hide Page</a>
        @endif
      </div>
    </div>

    <div class="box-body">
      <div class="row">
        <div class="col-sm-12">
          @include('partials.response-message')
        </div>
      </div>
      <div class="row m-t">
        <div class="col-sm-12 col-md-12">
          @if(getRole() == 'super-admin')
          {!! Form::open(['url' => url('garments-production-entry'), 'method' => 'POST', 'id' => 'gp-variable-form'])
          !!}
          <div class="form-group row">
            <div class="col-md-offset-4 col-md-4">
              {!! Form::label('factory_id', 'Factory', ['class' => 'text-sm'])!!}
              {!! Form::select('factory_id', $factory_options, null, ['class' => 'form-control form-control-sm
              select2-input', 'placeholder' => 'Select']) !!}
              @error('factory_id')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <hr>
          <div class="form-group row">
            <div class="col-md-12">
              <h5>APPLICATION MENUS RELATED</h5>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-3">
              {!! Form::label('erp_menu_view_status', 'ERP Menu View Status', ['class' => 'text-sm'])!!}
              {!! Form::select('erp_menu_view_status', $erp_menu_view_options, old('erp_menu_view_status') ?? 1,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
              @error('erp_menu_view_status')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('pdf_upload_menu_hide_status', 'Merchandising PDF Menu Hide Status', ['class' =>
              'text-sm'])!!}
              {!! Form::select('pdf_upload_menu_hide_status', $pdf_upload_menu_hide_options,
              old('pdf_upload_menu_hide_status') ?? 0,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
              @error('pdf_upload_menu_hide_status')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('hr_menu_view_status', 'HR MENU VIEW STATUS', ['class' => 'text-sm'])!!}
              {!! Form::select('hr_menu_view_status', $hr_menu_view_status_options ?? [], old('hr_menu_view_status') ??
              0,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
              @error('hr_menu_view_status')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('cutting_plan_menu_hide_status', 'Cutting Plan Menu Hide Status', ['class' =>
              'text-sm'])!!}
              {!! Form::select('cutting_plan_menu_hide_status', $cutting_plan_menu_hide_options,
              old('cutting_plan_menu_hide_status') ?? 0,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
              @error('cutting_plan_menu_hide_status')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-3">
              {!! Form::label('sewing_plan_menu_hide_status', 'Sewing Plan Menu Hide Status', ['class' => 'text-sm'])!!}
              {!! Form::select('sewing_plan_menu_hide_status', $sewing_plan_menu_hide_options,
              old('sewing_plan_menu_hide_status') ?? 0,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
              @error('sewing_plan_menu_hide_status')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <hr>
          <div class="form-group row">
            <div class="col-md-12">
              <h5>GARMENTS PRODUCTION RELATED</h5>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-3">
              {!! Form::label('entry_method', 'All Entry Method', ['class' => 'text-sm'])!!}
              {!! Form::select('entry_method', $methods, null, ['class' => 'form-control form-control-sm select2-input',
              'placeholder' => 'Select']) !!}
              @error('entry_method')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('entry_type', 'All Entry Type', ['class' => 'text-sm'])!!}
              {!! Form::select('entry_type', $types, null, ['class' => 'form-control form-control-sm select2-input',
              'placeholder' => 'Select']) !!}
              @error('entry_type')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('style_filter_option', 'Style/Booking No./ Ref. No. Maintain', ['class' => 'text-sm'])!!}
              {!! Form::select('style_filter_option', $style_filter_options, null, ['class' => 'form-control
              form-control-sm select2-input', 'placeholder' => 'Select']) !!}
              @error('style_filter_option')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('finishing_report', 'Finishing Report', ['class' => 'text-sm'])!!}
              {!! Form::select('finishing_report', $finishing_productions, null, ['class' => 'form-control
              form-control-sm select2-input', 'placeholder' => 'Select']) !!}
              @error('finishing_report')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <hr>
          <div class="form-group row">
            <div class="col-md-12">
              <h5>BUNDLE CARD RELATED</h5>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-3">
              {!! Form::label('bundle_card_serial', 'Bundle Card Serial', ['class' => 'text-sm'])!!}
              {!! Form::select('bundle_card_serial', $bundle_card_serials, null, ['class' => 'form-control
              form-control-sm select2-input', 'placeholder' => 'Select']) !!}
              @error('bundle_card_serial')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('bundle_card_suffix_style', 'Bundle Card Suffix Style', ['class' => 'text-sm'])!!}
              {!! Form::select('bundle_card_suffix_style', $bundle_card_suffix_styles, null, ['class' => 'form-control
              form-control-sm select2-input']) !!}
              @error('bundle_card_suffix_style')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('customized_sticker_serials', 'Bundle Card Serial Options', ['class' => 'text-sm'])!!}
              {!! Form::select('customized_sticker_serials[]', $bundle_card_serial_options, [], ['class' =>
              'form-control form-control-sm select2-input', 'multiple' => true]) !!}
              @error('customized_sticker_serials')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('size_suffix_sl_status', 'Bundle Card Serial Customization', ['class' => 'text-sm'])!!}
              {!! Form::select('size_suffix_sl_status', $bundle_card_size_suffix_sl_statuses, null, ['class' =>
              'form-control form-control-sm select2-input']) !!}
              @error('size_suffix_sl_status')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-3">
              {!! Form::label('bundle_card_print_style', 'Bundle Card Print Style', ['class' => 'text-sm'])!!}
              {!! Form::select('bundle_card_print_style', $bundle_card_print_styles, null, ['class' => 'form-control
              form-control-sm select2-input']) !!}
              @error('bundle_card_print_style')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('bundle_card_sticker_width', 'Bundle Card Sticker Width (in mm)', ['class' =>
              'text-sm'])!!}
              {!! Form::text('bundle_card_sticker_width', old('bundle_card_sticker_width') ?? null, ['class' =>
              'form-control form-control-sm', 'placeholder' => 'e.g. 65']) !!}
              @error('bundle_card_sticker_width')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('bundle_card_sticker_height', 'Bundle Card Sticker Height (in mm)', ['class' =>
              'text-sm'])!!}
              {!! Form::text('bundle_card_sticker_height', old('bundle_card_sticker_height') ?? null, ['class' =>
              'form-control form-control-sm', 'placeholder' => 'e.g. 35']) !!}
              @error('bundle_card_sticker_height')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('bundle_card_sticker_font_size', 'Bundle Card Sticker Font Size (in pixels)')!!}
              {!! Form::text('bundle_card_sticker_font_size', old('bundle_card_sticker_font_size') ?? null, ['class' =>
              'form-control form-control-sm', 'placeholder' => 'e.g. 35']) !!}
              @error('bundle_card_sticker_font_size')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-3">
              {!! Form::label('bundle_card_sticker_max_width', 'Bundle Card Sticker Max Width (in rem)', ['class' =>
              'text-sm'])!!}
              {!! Form::text('bundle_card_sticker_max_width', old('bundle_card_sticker_max_width') ?? null, ['class' =>
              'form-control form-control-sm', 'placeholder' => 'e.g. 21.5']) !!}
              @error('bundle_card_sticker_max_width')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('bundle_card_sticker_max_height', 'Bundle Card Sticker Max Height (in rem)', ['class' =>
              'text-sm'])!!}
              {!! Form::text('bundle_card_sticker_max_height', old('bundle_card_sticker_max_height') ?? null, ['class'
              => 'form-control form-control-sm', 'placeholder' => 'e.g. 12.75']) !!}
              @error('bundle_card_sticker_max_height')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('barcode_height', 'Barcode Height', ['class' => 'text-sm'])!!}
              {!! Form::text('barcode_height', old('barcode_height') ?? null, ['class' => 'form-control
              form-control-sm', 'placeholder' => 'e.g. 27']) !!}
              @error('barcode_height')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('barcode_width', 'Barcode Width', ['class' => 'text-sm'])!!}
              {!! Form::text('barcode_width', old('barcode_width') ?? null, ['class' => 'form-control form-control-sm',
              'placeholder' => 'e.g. 1.7']) !!}
              @error('barcode_width')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-3">
              {!! Form::label('bundle_card_sticker_ratio_view_status', 'Bundle Card Sticker Ratio View Status', ['class'
              => 'text-sm'])!!}
              {!! Form::select('bundle_card_sticker_ratio_view_status', $show_hide_status_options,
              old('bundle_card_sticker_ratio_view_status') ?? 0,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
              @error('bundle_card_sticker_ratio_view_status')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('scan_data_caching_time', 'Scan Data Caching Time(in seconds)', ['class'
              => 'text-sm'])!!}
              {!! Form::number('scan_data_caching_time', old('scan_data_caching_time') ?? null,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Caching Time(seconds)', 'min' => 1]) !!}
              @error('scan_data_caching_time')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
                  {!! Form::label('cutting_qty_validation', 'Cutting Qty Validation', ['class'
                  => 'text-sm'])!!}
                  {!! Form::select('cutting_qty_validation', ['1' => 'Yes', '0' => 'No'],
                  old('cutting_qty_validation') ?? 0,
                  ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
                  @error('cutting_qty_validation')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
            </div>
            <div class="col-md-3">
                  {!! Form::label('fabric_cons_approval', 'Fabric Cons Approval', ['class'
                  => 'text-sm'])!!}
                  {!! Form::select('fabric_cons_approval', ['1' => 'Yes', '0' => 'No'],
                  old('fabric_cons_approval') ?? 0,
                  ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
                  @error('fabric_cons_approval')
                  <div class="text-danger">{{ $message }}</div>
                  @enderror
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-3">
              {!! Form::label('max_bundle_qty', 'Max bundle qty', ['class'
              => 'text-sm'])!!}
              {!! Form::select('max_bundle_qty', ['1' => 'Yes', '0' => 'No'],
              old('max_bundle_qty') ?? 0,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
              @error('max_bundle_qty')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('bundle_straight_serial_max_limit', 'Bundle Straight Serial Max Limit', ['class'
              => 'text-sm'])!!}
              {!! Form::number('bundle_straight_serial_max_limit', old('bundle_straight_serial_max_limit') ?? null,
              ['class' => 'form-control form-control-sm', 'placeholder' => 'e.g. 9999', 'min' => '0', 'max' => '2147483647']) !!}
              @error('bundle_straight_serial_max_limit')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <hr>
          <div class="form-group row">
            <div class="col-md-12">
              <h5>TARGET ENTRY RELATED</h5>
            </div>
          </div>
          <div class="form-group row">
            <div class="col-md-3">
              {!! Form::label('finishing_target_entry_option', 'Finishing Target Entry Option', ['class' =>
              'text-sm'])!!}
              {!! Form::select('finishing_target_entry_option',
              $finishing_target_entry_options,old('finishing_target_entry_option') ?? null,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Entry Option']) !!}
              @error('finishing_target_entry_option')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('cutting_target_version', 'Cutting Target Entry Version', ['class' => 'text-sm'])!!}
              {!! Form::select('cutting_target_version', $cutting_target_versions ?? [],
              old('cutting_target_version') ?? 1,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
              @error('cutting_target_version')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-3">
              {!! Form::label('sewing_line_target_vesrion', 'Sewing Target Entry Version', ['class' => 'text-sm'])!!}
              {!! Form::select('sewing_line_target_vesrion', $sewing_line_target_versions ?? [],
              old('sewing_line_target_vesrion') ?? 1,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
              @error('sewing_line_target_vesrion')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>

            <div class="col-md-3">
              {!! Form::label('sewing_starting_hour', 'SEWING STARTING HOUR', ['class' => 'text-sm'])!!}
              {!! Form::select('sewing_starting_hour', $sewing_starting_hour_options ?? [], old('sewing_starting_hour')
              ?? 0,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select Option']) !!}
              @error('sewing_starting_hour')
              <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>

          </div>
          <hr>
          <div class="form-group row">
            <div class="col-md-12">
              <h5>Line Wise Hour Show Options in Report</h5>
            </div>
          </div>
          <div class="form-group row">
            @foreach ($line_wise_hour_show_data as $line_wise_hour_show_name => $line_wise_hour_show_value)
            <div class="col-md-2">
              {!! Form::label($line_wise_hour_show_name, $line_wise_hour_show_value, ['class' => 'text-sm'])!!}
              {!! Form::select('line_wise_hour_show['.$line_wise_hour_show_name.']', $show_hide_status_options,
              old('line_wise_hour_show['.$line_wise_hour_show_name.']') ?? null,
              ['class' => 'form-control form-control-sm select2-input', 'placeholder' => 'Select']) !!}
            </div>
            @endforeach
          </div>
          <hr>
          <div class="form-group row">
            <div class="col-md-12">
              <h5>YARN STORE BARCODE RELATED</h5>
            </div>
          </div>
          @foreach (collect($yarn_store_barcode_meta_labels)->chunk(4) as $labels)
            <div class="form-group row">
            @foreach($labels as $name => $label)
              <div class="col-md-3">
                {!! Form::label($name, $label, ['class' => 'text-sm'])!!}
                {!! Form::text('yarn_store_barcode_meta['.$name.']', old('yarn_store_barcode_meta['.$name.']') ?? null,
                ['class' => 'form-control form-control-sm', 'placeholder' => '']) !!}
                @error('yarn_store_barcode_meta.'.$name)
                <span class="text-danger">{{ $message }}</span>
                @enderror
              </div>
            @endforeach
            </div>
          @endforeach
          <div class="form-group row">
            <div class="col-md-3">
              <div class="col-md-6">
                <label for="">&nbsp;</label>
                <button type="submit" id="submit" class="btn btn-sm form-control form-control-sm btn-success"><i
                    class="fa fa-save"></i> Save
                </button>
              </div>
              <div class="col-md-6">
                <label for="">&nbsp;</label>
                <a href="{{ url('garments-production-entry') }}"
                  class="btn btn-sm form-control form-control-sm btn-warning"><i class="fa fa-refresh"></i>
                  Refresh</a>
              </div>
            </div>
          </div>
          {!! Form::close() !!}
          @else
          <h3 class="text-danger text-center">Permission Denied</h3>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('scripts')
<script>
  $(document).on('change', '#gp-variable-form [name="factory_id"]', function () {
            let factoryId = $(this).val(),
                entry_method = $('#gp-variable-form [name="entry_method"]'),
                entry_type = $('#gp-variable-form [name="entry_type"]'),
                style_filter_option = $('#gp-variable-form [name="style_filter_option"]'),
                bundle_card_serial = $('#gp-variable-form [name="bundle_card_serial"]'),
                bundle_card_suffix_style = $('#gp-variable-form [name="bundle_card_suffix_style"]'),
                size_suffix_sl_status = $('#gp-variable-form [name="size_suffix_sl_status"]'),
                bundle_card_print_style = $('#gp-variable-form [name="bundle_card_print_style"]'),
                customized_sticker_serials = $('#gp-variable-form [name="customized_sticker_serials[]"]'),
                bundle_straight_serial_max_limit = $('#gp-variable-form [name="bundle_straight_serial_max_limit"]'),
                bundle_card_sticker_width = $('#gp-variable-form [name="bundle_card_sticker_width"]'),
                bundle_card_sticker_height = $('#gp-variable-form [name="bundle_card_sticker_height"]'),
                bundle_card_sticker_font_size = $('#gp-variable-form [name="bundle_card_sticker_font_size"]'),
                bundle_card_sticker_max_width = $('#gp-variable-form [name="bundle_card_sticker_max_width"]'),
                bundle_card_sticker_max_height = $('#gp-variable-form [name="bundle_card_sticker_max_height"]'),
                bundle_card_sticker_ratio_view_status = $('#gp-variable-form [name="bundle_card_sticker_ratio_view_status"]');
                barcode_height = $('#gp-variable-form [name="barcode_height"]'),
                barcode_width = $('#gp-variable-form [name="barcode_width"]'),
                finishing_target_entry_option = $('#gp-variable-form [name="finishing_target_entry_option"]'),
                erp_menu_view_status = $('#gp-variable-form [name="erp_menu_view_status"]'),
                hr_menu_view_status = $('#gp-variable-form [name="hr_menu_view_status"]'),
                cutting_target_version = $('#gp-variable-form [name="cutting_target_version"]'),
                sewing_line_target_vesrion = $('#gp-variable-form [name="sewing_line_target_vesrion"]'),
                pdf_upload_menu_hide_status = $('#gp-variable-form [name="pdf_upload_menu_hide_status"]'),
                cutting_plan_menu_hide_status = $('#gp-variable-form [name="cutting_plan_menu_hide_status"]'),
                sewing_plan_menu_hide_status = $('#gp-variable-form [name="sewing_plan_menu_hide_status"]'),
                scan_data_caching_time = $('#gp-variable-form [name="scan_data_caching_time"]'),
                cutting_qty_validation = $('#gp-variable-form [name="cutting_qty_validation"]'),
                fabric_cons_approval = $('#gp-variable-form [name="fabric_cons_approval"]'),
                max_bundle_qty = $('#gp-variable-form [name="max_bundle_qty"]'),
                finishing_report = $('#gp-variable-form [name="finishing_report"]'),
                sewing_starting_hour = $('#gp-variable-form [name="sewing_starting_hour"]'),
                line_wise_hour_0 = $('#gp-variable-form [name="line_wise_hour_show[hour_0]"]'),
                line_wise_hour_1 = $('#gp-variable-form [name="line_wise_hour_show[hour_1]"]'),
                line_wise_hour_2 = $('#gp-variable-form [name="line_wise_hour_show[hour_2]"]'),
                line_wise_hour_3 = $('#gp-variable-form [name="line_wise_hour_show[hour_3]"]'),
                line_wise_hour_4 = $('#gp-variable-form [name="line_wise_hour_show[hour_4]"]'),
                line_wise_hour_5 = $('#gp-variable-form [name="line_wise_hour_show[hour_5]"]'),
                line_wise_hour_6 = $('#gp-variable-form [name="line_wise_hour_show[hour_6]"]'),
                line_wise_hour_7 = $('#gp-variable-form [name="line_wise_hour_show[hour_7]"]'),
                line_wise_hour_8 = $('#gp-variable-form [name="line_wise_hour_show[hour_8]"]'),
                line_wise_hour_9 = $('#gp-variable-form [name="line_wise_hour_show[hour_9]"]'),
                line_wise_hour_10 = $('#gp-variable-form [name="line_wise_hour_show[hour_10]"]'),
                line_wise_hour_11 = $('#gp-variable-form [name="line_wise_hour_show[hour_11]"]'),
                line_wise_hour_12 = $('#gp-variable-form [name="line_wise_hour_show[hour_12]"]'),
                line_wise_hour_13 = $('#gp-variable-form [name="line_wise_hour_show[hour_13]"]'),
                line_wise_hour_14 = $('#gp-variable-form [name="line_wise_hour_show[hour_14]"]'),
                line_wise_hour_15 = $('#gp-variable-form [name="line_wise_hour_show[hour_15]"]'),
                line_wise_hour_16 = $('#gp-variable-form [name="line_wise_hour_show[hour_16]"]'),
                line_wise_hour_17 = $('#gp-variable-form [name="line_wise_hour_show[hour_17]"]'),
                line_wise_hour_18 = $('#gp-variable-form [name="line_wise_hour_show[hour_18]"]'),
                line_wise_hour_19 = $('#gp-variable-form [name="line_wise_hour_show[hour_19]"]'),
                line_wise_hour_20 = $('#gp-variable-form [name="line_wise_hour_show[hour_20]"]'),
                line_wise_hour_21 = $('#gp-variable-form [name="line_wise_hour_show[hour_21]"]'),
                line_wise_hour_22 = $('#gp-variable-form [name="line_wise_hour_show[hour_22]"]'),
                line_wise_hour_23 = $('#gp-variable-form [name="line_wise_hour_show[hour_23]"]'),
                yarn_store_barcode_meta_barcode_width = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_width]"]'),
                yarn_store_barcode_meta_barcode_height = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_height]"]'),
                yarn_store_barcode_meta_barcode_font_size = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_font_size]"]'),
                yarn_store_barcode_meta_barcode_container_m_top = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_container_m_top]"]'),
                yarn_store_barcode_meta_barcode_container_m_left = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_container_m_left]"]'),
                yarn_store_barcode_meta_barcode_container_m_right = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_container_m_right]"]'),
                yarn_store_barcode_meta_barcode_container_m_bottom = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_container_m_bottom]"]'),
                yarn_store_barcode_meta_barcode_container_p_top = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_container_p_top]"]'),
                yarn_store_barcode_meta_barcode_container_p_left = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_container_p_left]"]'),
                yarn_store_barcode_meta_barcode_container_p_right = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_container_p_right]"]'),
                yarn_store_barcode_meta_barcode_container_p_bottom = $('#gp-variable-form [name="yarn_store_barcode_meta[barcode_container_p_bottom]"]');

            entry_method.val(null).change();
            entry_type.val(null).change();
            style_filter_option.val(null).change();
            bundle_card_serial.val(null).change();
            bundle_straight_serial_max_limit.val(null).change();
            bundle_card_suffix_style.val(0).change();
            size_suffix_sl_status.val(0).change();
            bundle_card_print_style.val(0).change();
            customized_sticker_serials.val(null).change();
            bundle_card_sticker_width.val(null).change();
            bundle_card_sticker_height.val(null).change();
            bundle_card_sticker_font_size.val(null).change();
            bundle_card_sticker_max_width.val(null).change();
            bundle_card_sticker_max_height.val(null).change();
            barcode_height.val(null).change();
            barcode_width.val(null).change();
            finishing_target_entry_option.val(null).change();
            pdf_upload_menu_hide_status.val(0).change();
            cutting_plan_menu_hide_status.val(0).change();
            sewing_plan_menu_hide_status.val(0).change();
            scan_data_caching_time.val(null).change();
            cutting_qty_validation.val(0).change();
            fabric_cons_approval.val(0).change();
            max_bundle_qty.val(0).change();
            finishing_report.val(0).change();
            erp_menu_view_status.val(1).change();
            hr_menu_view_status.val(0).change();
            cutting_target_version.val(1).change();
            sewing_line_target_vesrion.val(1).change();
            sewing_starting_hour.val(8).change();
            bundle_card_sticker_ratio_view_status.val(0).change();
            if (factoryId) {
                $.ajax({
                    type: 'GET',
                    url: `/garments-production-entry/${factoryId}/fetch`
                }).done(function (response) {
                    entry_method.val(response.entry_method || null).change();
                    entry_type.val(response.entry_type || null).change();
                    style_filter_option.val(response.style_filter_option || null).change();
                    bundle_card_serial.val(response.bundle_card_serial || null).change();
                    bundle_straight_serial_max_limit.val(response.bundle_straight_serial_max_limit || null).change();
                    bundle_card_suffix_style.val(response.bundle_card_suffix_style || 0).change();
                    size_suffix_sl_status.val(response.size_suffix_sl_status || 0).change();
                    bundle_card_print_style.val(response.bundle_card_print_style || 0).change();
                    customized_sticker_serials.val(response.customized_sticker_serials || null).change();
                    bundle_card_sticker_width.val(response.bundle_card_sticker_width || null).change();
                    bundle_card_sticker_height.val(response.bundle_card_sticker_height || null).change();
                    bundle_card_sticker_font_size.val(response.bundle_card_sticker_font_size || null).change();
                    bundle_card_sticker_max_width.val(response.bundle_card_sticker_max_width || null).change();
                    bundle_card_sticker_max_height.val(response.bundle_card_sticker_max_height || null).change();
                    barcode_height.val(response.barcode_height || null).change();
                    barcode_width.val(response.barcode_width || null).change();
                    finishing_target_entry_option.val(response.finishing_target_entry_option || null).change();
                    pdf_upload_menu_hide_status.val(response.pdf_upload_menu_hide_status || 0).change();
                    cutting_plan_menu_hide_status.val(response.cutting_plan_menu_hide_status || 0).change();
                    sewing_plan_menu_hide_status.val(response.sewing_plan_menu_hide_status || 0).change();
                    scan_data_caching_time.val(response.scan_data_caching_time || null).change();
                    cutting_qty_validation.val(response.cutting_qty_validation || 0).change();
                    fabric_cons_approval.val(response.fabric_cons_approval || 0).change();
                    max_bundle_qty.val(response.max_bundle_qty || 0).change();
                    finishing_report.val(response.finishing_report || 0).change();
                    erp_menu_view_status.val(response.erp_menu_view_status || 1).change();
                    hr_menu_view_status.val(response.hr_menu_view_status || 0).change();
                    cutting_target_version.val(response.cutting_target_version || 1).change();
                    sewing_line_target_vesrion.val(response.sewing_line_target_vesrion || 1).change();
                    sewing_starting_hour.val(response.sewing_starting_hour || 8).change();
                    bundle_card_sticker_ratio_view_status.val(response.bundle_card_sticker_ratio_view_status || 0).change();
                    line_wise_hour_0.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_0'] : 0).change();
                    line_wise_hour_1.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_1'] : 0).change();
                    line_wise_hour_2.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_2'] : 0).change();
                    line_wise_hour_3.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_3'] : 0).change();
                    line_wise_hour_4.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_4'] : 0).change();
                    line_wise_hour_5.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_5'] : 0).change();
                    line_wise_hour_6.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_6'] : 0).change();
                    line_wise_hour_7.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_7'] : 0).change();
                    line_wise_hour_8.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_8'] : 1).change();
                    line_wise_hour_9.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_9'] : 1).change();
                    line_wise_hour_10.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_10'] : 1).change();
                    line_wise_hour_11.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_11'] : 1).change();
                    line_wise_hour_12.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_12'] : 1).change();
                    line_wise_hour_13.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_13'] : 1).change();
                    line_wise_hour_14.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_14'] : 1).change();
                    line_wise_hour_15.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_15'] : 1).change();
                    line_wise_hour_16.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_16'] : 1).change();
                    line_wise_hour_17.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_17'] : 1).change();
                    line_wise_hour_18.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_18'] : 1).change();
                    line_wise_hour_19.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_19'] : 0).change();
                    line_wise_hour_20.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_20'] : 0).change();
                    line_wise_hour_21.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_21'] : 0).change();
                    line_wise_hour_22.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_22'] : 0).change();
                    line_wise_hour_23.val(response.line_wise_hour_show ? response.line_wise_hour_show['hour_23'] : 0).change();
                    yarn_store_barcode_meta_barcode_width.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_width'] : 0).change();
                    yarn_store_barcode_meta_barcode_height.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_height'] : 0).change();
                    yarn_store_barcode_meta_barcode_font_size.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_font_size'] : 0).change();
                    yarn_store_barcode_meta_barcode_container_m_top.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_container_m_top'] : 0).change();
                    yarn_store_barcode_meta_barcode_container_m_left.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_container_m_left'] : 0).change();
                    yarn_store_barcode_meta_barcode_container_m_right.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_container_m_right'] : 0).change();
                    yarn_store_barcode_meta_barcode_container_m_bottom.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_container_m_bottom'] : 0).change();
                    yarn_store_barcode_meta_barcode_container_p_top.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_container_p_top'] : 0).change();
                    yarn_store_barcode_meta_barcode_container_p_left.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_container_p_left'] : 0).change();
                    yarn_store_barcode_meta_barcode_container_p_right.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_container_p_right'] : 0).change();
                    yarn_store_barcode_meta_barcode_container_p_bottom.val(response.yarn_store_barcode_meta ? response.yarn_store_barcode_meta['barcode_container_p_bottom'] : 0).change();
                }).fail(function (response) {
                    console.log(response)
                })
            }
        })
</script>
@endsection
