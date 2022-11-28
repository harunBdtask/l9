<style>
    .signature {
        left: 0;
        bottom: 0;
        height: 30px;
        width: 100%;
        margin-top: 50px;
    }
</style>
<div class="padding">
    <div class="box">

        <div class="box-body">
            <div class="row">
                <div class="col-md-12">
                    <center>
                        <table style="border: 1px solid black;width: 20%;">
                            <thead>
                            <tr>
                                <td class="text-center">
                                    <span style="font-size: 12pt; font-weight: bold;">Gate Pass</span>
                                    <br>
                                </td>
                            </tr>
                            </thead>
                        </table>
                    </center>

                    @include('subcontract::textile_module.sub-dyeing-goods-delivery.gate-pass.view-body')

                    <div class="signature">
                        <table class="borderless">
                            <tbody>
                            <tr>
                                <td class="text-center"><u>Prepared By</u></td>
                                <td class="text-center"><u>Received Signature</u></td>
                                <td class="text-center"><u>Account Signature</u></td>
                                <td class='text-center'><u>Department Head</u></td>
                                <td class="text-center"><u>Authorized Signature</u></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                </div>
            </div>

        </div>
    </div>
</div>

