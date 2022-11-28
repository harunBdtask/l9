@extends('skeleton::layout')
@section("title", "Report Signature")
@section('content')
    <div class="padding">
        <div class="box">
            <div class="box-header">
                <h2>Report Signature</h2>
            </div>

            <div class="box-body">

                <a class="btn btn-sm white m-b b-t m-b-1" href="{{ url('report-signature/create') }}">
                    <i class="glyphicon glyphicon-plus"></i> New Report Signature
                </a>
                <div class="row">
                    <div class="col-sm-12">
                        @include('partials.response-message')
                    </div>
                </div>
                <div class="row m-t">

                    <div class="col-sm-12 col-md-12">
                        <table class="reportTable table-bordered">
                            <thead>
                            <tr>
                                <th>SL</th>
                                <th>Company</th>
                                <th>Buyer</th>
                                <th>Report/Page Name</th>
                                <th>View Button</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($reportSignatures as $key => $reportSignature)
                                <tr>
                                    <td>{{ $reportSignatures->firstItem() + $key }}</td>
                                    <td>{{ $reportSignature->factory->factory_name }}</td>
                                    <td>
                                        <button style="font-size: 10px"
                                                class="btn btn-outline btn-info text-black btn-xs"
                                                data-buyers="{{$reportSignature->buyer_names}}">
                                            Browse
                                        </button>
                                    </td>
                                    <td>{{ $reportSignature->page_name_value }}</td>
                                    <td>{{ $reportSignature->view_button_value }}</td>
                                    <td>{{ $reportSignature->is_active === 1 ? 'ACTIVE' : 'INACTIVE' }}</td>
                                    <td>
                                        <a href="/report-signature/{{$reportSignature->id}}/edit"
                                           class="btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                                        <button type="button"
                                                class="btn btn-sm btn-danger show-modal"
                                                title="Delete Report Signature"
                                                data-toggle="modal"
                                                data-target="#confirmationModal" ui-toggle-class="flip-x"
                                                ui-target="#animate"
                                                data-url="{{ url('report-signature/'.$reportSignature->id) }}">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <div class="text-center">
                            {{ $reportSignatures->render() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="buyerNamesModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h5 class="modal-title" id="exampleModalLongTitle">Buyers</h5>
                    </div>
                    <div class="modal-body" id="buyerNames">

                    </div>
                    <div class="modal-footer">
                        <button
                            style="font-size: 10px"
                            data-dismiss="modal"
                            class="btn btn-danger">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $(function (){
        const buyerNamesModal = $('#buyerNamesModal');
        const buyerNamesModalBody = buyerNamesModal.find('.modal-body');

        $('[data-buyers]').click(function (e){
            buyerNamesModalBody.empty();
            $(this).data('buyers').map(buyer=>{
                buyerNamesModalBody.append(`
                        <span class="btn btn-info" style="font-size: 10px; margin: 2px 0;">
                            ${buyer}
                        </span>
                `);
            });
            buyerNamesModal.modal('show');
        });
    });
</script>
@endsection
