
<div class="row">
    <div class="col-md-12">
        <div class="pull-right">

            <button id="gatePassChallanStore"
               class="btn btn-sm btn-success">
                Exit
            </button>

            <a href="{{ url('/inventory/yarn-gate-pass-challan-scan') }}"
               class="btn btn-sm btn-danger">
                Close
            </a>

        </div>
    </div>
</div>
<div>
    <div style="margin-top: 3rem;">
        <table>
            <tr style="background-color: #d9d9bf; font-weight: bold; font-size: 18px;">
                <td colspan="10" class="text-center">Gate Pass Challan</td>
            </tr>
            <tr>
                <th>Issue No</th>
                <th>Challan No</th>
                <th>Issue Date</th>
                <th>Party Name</th>
                <th>Gate Pass No</th>
                <th>Address</th>
                <th>Vehicle Number</th>
                <th>Lock No</th>
                <th>Driver Name</th>
            </tr>
            <tr>
                <td>
                    <input type="hidden" value="{{ $yarnIssue->id }}" id="yarn_issue_id" class="form-control">
                    <input type="hidden" value="{{ $yarnIssue->issue_no }}" id="issue_no" class="form-control">
                    {{ $yarnIssue->issue_no }}
                </td>
                <td>
                    <input type="hidden" value="{{ $yarnIssue->challan_no }}" id="challan_no" class="form-control">
                    {{ $yarnIssue->challan_no }}
                </td>
                <td>
                    <input type="hidden" value="{{ $yarnIssue->issue_date }}" id="issue_date" class="form-control">
                    {{ $yarnIssue->issue_date }}
                </td>
                <td>
                    <input type="hidden" value="{{ $yarnIssue->loanParty->id }}" id="party_name" class="form-control">
                    {{ $yarnIssue->loanParty->name }}
                </td>
                <td>
                    <input type="hidden" value="{{ $yarnIssue->gate_pass_no }}" id="gate_pass_no" class="form-control">
                    {{ $yarnIssue->gate_pass_no }}
                </td>
                <td>
                    <input type="hidden" value="{{ optional($yarnIssue->loanParty)->address_1 }}" id="address"
                           class="form-control">
                    {{ optional($yarnIssue->loanParty)->address_1 }}
                </td>
                <td>
                    <input type="hidden" value="{{ $yarnIssue->vehicle_number }}" id="vehicle_number"
                           class="form-control">
                    {{ $yarnIssue->vehicle_number }}
                </td>
                <td>
                    <input type="hidden" value="{{ $yarnIssue->lock_no }}" id="lock_no" class="form-control">
                    {{ $yarnIssue->lock_no }}
                </td>
                <td>
                    <input type="hidden" value="{{ $yarnIssue->driver_name }}" id="driver_name" class="form-control">
                    {{ $yarnIssue->driver_name }}
                </td>
            </tr>
        </table>
    </div>
</div>

{{--@push("script-head")--}}
    <script>
        $(document).ready(function () {
            $(document).on('click', '#gatePassChallanStore', function (event) {
                event.preventDefault();
                let yarn_issue_id = $('#yarn_issue_id').val();
                let issue_no = $('#issue_no').val();
                let challan_no = $('#challan_no').val();
                let issue_date = $('#issue_date').val();
                let party_name = $('#party_name').val();
                let gate_pass_no = $('#gate_pass_no').val();
                let vehicle_number = $('#vehicle_number').val();
                let lock_no = $('#lock_no').val();
                let driver_name = $('#driver_name').val();

                $.ajax({
                    method: 'POST',
                    url: `/inventory/yarn-gate-pass-challan-scan/store`,
                    data: {
                        yarn_issue_id,
                        issue_no,
                        challan_no,
                        issue_date,
                        party_name,
                        gate_pass_no,
                        vehicle_number,
                        lock_no,
                        driver_name
                    },
                    success: function (result) {
                        toastr.success(result.message);
                        window.location.href = '/inventory/yarn-gate-pass-challan-scan/show';
                    },
                    error: function (error) {
                        console.log(error)
                    }
                })
            });
        });
    </script>
{{--@endpush--}}
