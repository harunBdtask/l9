<div class="box" id="printId">
    <div class="box-body">
        <div style="border-bottom: 1px solid; padding-bottom: 25px">
            <center>
                <span><?php echo DNS1D::getBarcodeSVG(($knitProgramRoll->barcode_no), "C128A", 1, 40, '', false); ?></span>
            </center>
            <br>
            <strong style="float: left">{{ $knitProgramRoll->barcode_no }}</strong>
            <strong style="float: right">{{ date('d-m-Y h:s a', strtotime($knitProgramRoll->production_datetime)) }} || {{ $knitProgramRoll->shift->shift_name }}</strong>
        </div>
        <div style="border-bottom: 1px solid; padding-bottom: 10px; padding-top: 10px;">
            <strong>
                {{ $knitProgramRoll->planningInfo->buyer_name }} :
                {{ $knitProgramRoll->planningInfo->style_name }} :
                {{ $knitProgramRoll->planningInfo->fabric_description }} :
                {{ $knitProgramRoll->knitCard->machine->machine_no }} :
                {{ $knitProgramRoll->knittingProgram->machine_dia }} x {{ $knitProgramRoll->knittingProgram->machine_gg }} :
                {{ $knitProgramRoll->planningInfo->fabric_dia }} :
                {{ $knitProgramRoll->knitCard->gsm }} :
                {{ $knitProgramRoll->knitCard->color }} :

                @foreach ($knitProgramRoll->knitCard->yarnDetails as $yarn)
                    {{ $yarn->yarn_count->yarn_count }} :
                    {{ $yarn->yarn_composition->yarn_composition }} :
                    {{ $yarn->yarn_type->name }} :
                    {{ $yarn->yarn_lot }} :
                    {{ $yarn->yarn_color }} :
                    {{ $yarn->yarn_brand }} :
                @endforeach
            </strong>
        </div>

        <div style="padding-bottom: 10px; padding-top: 10px;">
            <strong style="float: left">{{ $knitProgramRoll->factory->factory_short_name }}</strong>
            <strong style="float: right">WT#{{ $knitProgramRoll->roll_weight ? $knitProgramRoll->roll_weight." KG" : "" }}</strong>
            <br>
            <strong style="float: left">{{ $knitProgramRoll->operator->operator_name }}</strong>
            <strong style="float: right">{{ $knitProgramRoll->operator->operator_code }}</strong>
        </div>
    </div>
</div>
