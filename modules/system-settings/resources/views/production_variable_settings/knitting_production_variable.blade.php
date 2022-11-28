@extends('skeleton::layout')
@section("title","Production Variable")
@section('content')
    <style> [type=time] {
            line-height: inherit !important;
        } </style>
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2> Production Variable </h2>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="flash-message">
                            @foreach (['danger', 'warning', 'success', 'info'] as $msg)
                                @if(Session::has('alert-' . $msg))
                                    <p class="alert alert-{{ $msg }} text-center">
                                        {{ Session::get('alert-' . $msg) }}
                                    </p>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="row m-t">
                    {!! Form::model($productionVariable, ['url' => $productionVariable ? 'knitting-production-variable/'.$productionVariable['id'] : 'knitting-production-variable', 'method' => $productionVariable ? 'PUT' : 'POST', 'id' => 'form']) !!}
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="factory_id">Company</label>
                            {{ Form::select('factory_id', $factories, factoryId(), ['class' => 'form-control form-control-sm', 'id' => 'factory_id']) }}

                            @error('factory_id')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="knitting_process_maintain">Knitting Process Maintain</label>
                            {{ Form::select('knitting_process_maintain', ['' => 'Select', 'yarn_allocation_base' => 'Yarn Allocation Base', 'fabric_sales_order_base' => 'Fabric Sales Order Base'], null, ['class' => 'form-control form-control-sm', 'id' => 'knitting_process_maintain']) }}
                            @error('knitting_process_maintain')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="fabric_production_maintain">Fabric Production Maintain</label>
                            {{ Form::select('fabric_production_maintain', ['' => 'Select', 'roll_label' => 'Roll Label', 'gross_label' => 'Gross Label'], null, ['class' => 'form-control form-control-sm', 'id' => 'fabric_production_maintain']) }}
                            @error('fabric_production_maintain')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="yarn_allocation_maintain">Yarn Allocated Maintain</label>
                            {{ Form::select('yarn_allocation_maintain', ['' => 'Select', 'program_page' => 'Program Page ', 'yarn_requisition_base' => 'Yarn Requisition Page', 'yarn_allocation' => 'Yarn allocation'], null, ['class' => 'form-control form-control-sm', 'id' => 'yarn_allocation_maintain']) }}
                            @error('yarn_allocation_maintain')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="shift_wise_production_maintain">Shift Wise Production Maintain</label>
                            {{ Form::select('shift_wise_production_maintain', ['' => 'Select', 'yes' => 'Yes', 'no' => 'No'], null, ['class' => 'form-control form-control-sm', 'id' => 'shift_wise_production_maintain']) }}
                            @error('shift_wise_production_maintain')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pcs_production_maintain">Pcs Production Maintain</label>
                            {{ Form::select('pcs_production_maintain', ['' => 'Select', 'yes' => 'Yes', 'no' => 'No'], null, ['class' => 'form-control form-control-sm', 'id' => 'pcs_production_maintain']) }}
                            @error('pcs_production_maintain')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="machine_wise_production_maintain">Machine Wise Production Maintain</label>
                            {{ Form::select('machine_wise_production_maintain', ['' => 'Select', 'yes' => 'Yes', 'no' => 'No'], null, ['class' => 'form-control form-control-sm', 'id' => 'machine_wise_production_maintain']) }}
                            @error('machine_wise_production_maintain')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="ktting_qc_maintain">Knitting QC Maintain</label>
                            {{ Form::select('knitting_qc_maintain', ['' => 'Select', 'yes' => 'Yes', 'no' => 'No'], null, ['class' => 'form-control form-control-sm', 'id' => 'knitting_qc_maintain']) }}
                            @error('ktting_qc_maintain')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="form-group m-t-md pull-right">
                            <button type="submit" class="btn btn-sm white"><i class="fa fa-save"></i> &nbsp; Create
                            </button>
                            <a class="btn btn-sm btn-dark" href="/"> <i class="fa fa-times"></i> &nbsp; Cancel</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).on('change', '#factory_id', function () {
            const form = $('#form');
            const knitting_process_maintain = $('#knitting_process_maintain');
            const fabric_production_maintain = $('#fabric_production_maintain');
            const yarn_allocation_maintain = $('#yarn_allocation_maintain');
            const shift_wise_production_maintain = $('#shift_wise_production_maintain');
            const pcs_production_maintain = $('#pcs_production_maintain');
            const machine_wise_production_maintain = $('#machine_wise_production_maintain');
            const knitting_qc_maintain = $('#knitting_qc_maintain');

            $.ajax({
                url : 'get-knitting-production-variable/' + $("#factory_id").val(),
                type : 'get',
                success : function(response) {
                    console.log(response.length);
                    if (Object.keys(response).length === 0) {
                        knitting_process_maintain.val('')
                        fabric_production_maintain.val('')
                        yarn_allocation_maintain.val('')
                        shift_wise_production_maintain.val('')
                        pcs_production_maintain.val('')
                        machine_wise_production_maintain.val('')
                        knitting_qc_maintain.val('')

                        $("[name='_method']").remove();
                        form.attr('action', 'knitting-production-variable');
                    } else {
                        knitting_process_maintain.val(response.knitting_process_maintain)
                        fabric_production_maintain.val(response.fabric_production_maintain)
                        yarn_allocation_maintain.val(response.yarn_allocation_maintain)
                        shift_wise_production_maintain.val(response.shift_wise_production_maintain)
                        pcs_production_maintain.val(response.pcs_production_maintain)
                        machine_wise_production_maintain.val(response.machine_wise_production_maintain)
                        knitting_qc_maintain.val(response.knitting_qc_maintain)

                        form.append('<input name="_method" type="hidden" value="PUT">');
                        form.attr('action', `knitting-production-variable/${response.id}`);
                    }
                }
            })
        })

    </script>
@endsection