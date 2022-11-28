<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
* {
  box-sizing: border-box;
}

.header {
  padding: 15px;
}

.row::after {
  content: "";
  clear: both;
  display: table;
}

[class*="col-"] {
  float: left;
  padding: 15px;
}

.col-1 {width: 8.33%;}
.col-2 {width: 16.66%;}
.col-3 {width: 25%;}
.col-4 {width: 33.33%;}
.col-5 {width: 41.66%;}
.col-6 {width: 50%;}
.col-7 {width: 58.33%;}
.col-8 {width: 66.66%;}
.col-9 {width: 75%;}
.col-10 {width: 83.33%;}
.col-11 {width: 91.66%;}
.col-12 {width: 100%;}
</style>
</head>
<body>


<div class="row">

<div class="col-1"></div>

<div class="col-2">
    {{-- <img src="https://commondatastorage.googleapis.com/codeskulptor-assets/lathrop/asteroid_blue.png" alt=""> --}}
</div>

<div class="col-6">
    @php
        $factoryName = factoryName();
        $factoryAddress = factoryAddress();
    @endphp
    <h2 style="margin-bottom: 1%;" class="text-center">{{ $factoryName }}</h2>
    <center>
     <p style="border-radius: 33px; border: 2px solid black;
               padding: 6px; width: 105px;margin-bottom: 3px;"><b>Dye House</b></p>
    </center>
    <p style="margin-bottom: 1%;" class="text-center">{{ $factoryAddress }}</p>
    {{-- <p class="text-center"><b>Phone:</b> 01876456534,01908764532  <b>Fax:</b> 097856</p> --}}
  </div>

  <div class="col-1" style="margin-right: -45px;margin-top: 40px;">
    <span>DT : </span>
   </div>
   <div class="col-2"> 
       <p style="margin-top: 40px;border-bottom: 1px dashed;width: 61px;">{{ $dyeingBatch->batch_date }}
    </p>
    </div>
</div>

<div class="row">
    <div class="col-3"></div>
    <div class="col-6">
        <center>
            <p style="border-radius: 33px;
            border: 2px solid black;
            padding: 6px;
            width: 151px;
            margin-bottom: 3px;"><b>BATCH CARD</b></p>
        </center>
    </div>
    <div class="col-1" style="margin-right: -30px; margin-top:20px;">
        <span>Ch No : </span>
    </div>
    <div class="col-2" style="margin-top: 8px;">
        <p style="border-bottom: 1px dashed;width: 107%;margin-top: 20px;"></p>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="row">
            <div class="col-1" style="margin-right: -20px; margin-top:5px;">
                <p>Buyer: </p>
            </div>
            <div class="col-3" style="border-bottom: 1px dashed; width: 26%;margin-right: -12px;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center">{{ $dyeingBatch->supplier->name }}</p>
            </div>

            <div class="col-1" style="margin-right: -20px; margin-top:5px;">
                <p>Style:</p>
            </div>
            <div class="col-3" style="border-bottom: 1px dashed; width: 29%; margin-right: -12px;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center"></p>
            </div>

            <div class="col-1" style="margin-right: -20px;margin-top:5px;">
                <p>Lot No:</p>
            </div>
            <div class="col-3" style="border-bottom: 1px dashed;width: 26%;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center"></p>
            </div>
        </div>

        <div class="row" >
            <div class="col-1" style="margin-right: -20px;margin-top:5px;">
                <p>Color: </p>
            </div>
            <div class="col-7" style="border-bottom: 1px dashed;width: 50%;margin-right: -12px; ">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center">{{ $dyeingBatch->color->name }}</p>
            </div>

            <div class="col-1" style="margin-right: -25px;margin-top:5px;">
                <p>M/c No:</p>
            </div>
            <div class="col-3" style="border-bottom: 1px dashed;width: 35%; margin-left:10px">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center">{{ $machines }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-1" style="margin-right: -20px; margin-top:5px">
                <p>Fabric:</p>
            </div>
            <div class="col-5" style="border-bottom: 1px dashed;margin-right: -12px">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center">{{ $dyeingBatch->fabricComposition->construction }}</p>
            </div>

            <div class="col-2" style="margin-top:5px">
                <p>Finished/G.S.M:</p>
            </div>
            <div class="col-4" style="border-bottom: 1px dashed;margin-left: -5%; width: 39%;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center">{{ $dyeingBatch->gsm }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-2" style="margin-right: -20px; margin-top:5px">
                <p>Batch No :</p>
            </div>
            <div class="col-10" style="border-bottom: 1px dashed;margin-left: -6%;width: 89%;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center">{{ $dyeingBatch->batch_no }}</p>
            </div>
        </div>

        <div class="row">
            <div class="col-1" style="margin-right: -25px; margin-top:5px">
                <p>L.T:</p>
            </div>
            <div class="col-5" style="border-bottom: 1px dashed;margin-right: -15px">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center"></p>
            </div>

            <div class="col-2" style="margin-top:5px">
                <p>UL.Time:</p>
            </div>
            <div class="col-4" style="border-bottom: 1px dashed;margin-left: -8%; width: 43%;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center"></p>
            </div>
        </div>
    </div>
    <div class="col-12" style="margin-left: 9px">
        <table class="reportTable" style="width: 98%;margin-top: 3% ;">
            <thead>

            <tr>
                <th rowspan="2">SI</th>
                <th rowspan="2">Knitting M/C</th>
                <th rowspan="2">Grey Dia</th>
                <th rowspan="2">Roll Qty</th>
                <th rowspan="2">Grey Wt.(KG)</th>
                <th rowspan="2">Finish Wt.(KG)</th>
                <th colspan="2">Finished</th>
                <th rowspan="2">Remarks</th>
            </tr>
            <tr>
                <th>DIA</th>
                <th>GSM</th>
            </tr>

            </thead>
            <tbody>
                
                    @foreach ($dyeingBatch->batchDetails as $details)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">{{ $details->batch_roll }}</td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center">{{ $details->gsm }}</td>
                        <td class="text-center">{{ $details->remarks }}</td>
                    </tr>
                    @endforeach
            
            </tbody>
        </table>
    </div>

        <div class="col-12">
        <div class="row" >
            <div class="col-3" style="margin-right: -29px; margin-top:5px">
                <p>(A)TOTAL GREY WT:</p>
            </div>
            <div class="col-3" style="border-bottom: 1px dashed; margin-left: -55px;width: 39%;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center"></p>
            </div>
            <div class="col-3" style="margin-left: -12px; margin-top:5px">
                <p>(B)F/FAB:</p>
            </div>
            <div class="col-3" style="border-bottom: 1px dashed; margin-left: -17%;width: 39%;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center"></p>
            </div>
        </div>

        <div class="row">
            <div class="col-2" style="margin-right: -3%;margin-top:5px">
                <p>(C)WASTAGE :</p>
            </div>
            <div class="col-4" style="border-bottom: 1px dashed; margin-left: -3%;width: 39%;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center"></p>
            </div>
            <div class="col-3" style="margin-left: -1.2%;margin-top:5px">
                <p>(D)TOTAL(B+C) :</p>
            </div>
            <div class="col-3" style="border-bottom: 1px dashed; margin-left: -13%;width: 39%;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center"></p>
            </div>
        </div>

        <div class="row">
            <div class="col-3" style="margin-right: -3% margin-top:5px">
                <p>(E)PROCESS LOSS:(A-D)</p>
            </div>
            <div class="col-3" style="border-bottom: 1px dashed; margin-left: -6.5%;width: 39%;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center"></p>
            </div>
            <div class="col-3" style="margin-left: -1.2%; margin-top:5px">
                <p>KG=</p>
            </div>
            <div class="col-3" style="border-bottom: 1px dashed; margin-left: -21%; width: 39%;">
                <p style="margin-bottom: -5px; line-height: 1px;" class="text-center"></p>
            </div>
        </div>

        <div class="row">
            <div class="col-1" style="margin-left: -7%"></div>
           <div class="col-2" style="border-top: 1px dashed; width: 10%;">
            <p class="text-left" style="margin-top: -13px;">Sup.Batch</p>
           </div>
           <div class="col-1" style="margin-left: 8%;"></div>
           <div class="col-2" style="border-top: 1px dashed; width: 13%;">
            <p class="text-center" style="margin-top: -13px;">Operator(Dyeing)</p>
           </div>

           <div class="col-1" style="margin-left: 8%;"></div>
           <div class="col-2"  style="border-top: 1px dashed; width: 13%;">
            <p class="text-center" style="margin-top: -13px;">Sup.Finishing</p>
           </div>

           <div class="col-1" style="margin-left: 7%;"></div>
           <div class="col-2"  style="border-top: 1px dashed; width: 13%;">
            <p class="text-right" style="margin-top: -13px;">Q.C.(Incharge)</p>
           </div>
          
      
        </div>

        




    </div>
</div>

</body>
</html>


