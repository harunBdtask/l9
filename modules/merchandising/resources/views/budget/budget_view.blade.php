@extends('skeleton::layout')
@section('title','Budget')
@push('style')
    <style>
        table, td, th {
            border: 1px solid black;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th {
            text-align: center;
        }
        td {
            text-align: center;
        }
        .text-spacing {
            text-align: initial; text-indent: 50px;
        }
    </style>
@endpush
@section('content')
<div class="padding">
    <div class="box" >
        <div class="box-header">
        </div>
        <div class="box-body">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-lg">
                            <table>
                                <!--thead part one-->
                                <thead>
                                <tr>
                                    <th colspan="9" style="height: 200px">
                                        <h1 class="display-4"><b>{{ factoryName() }}</b></h1>
                                        <p> {{ factoryAddress() }}</p>
                                    </th>
                                </tr>
                                <tr style="background: #66CDAA">
                                    <th colspan="5">Cost Breakdown Sheet</th>
                                    <th style="width: 200px">Item Picture</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="5" style="visibility: hidden">space</td>
                                    <td rowspan="11">Picture</td>
                                </tr>
                                <tr>
                                    <td>PE-COSTING DATE</td>
                                    <td colspan="2">4/20/2021 0:00:00</td>
                                    <td>STYLE REF:</td>
                                    <td>JOANA 2 T-SHIRT</td>

                                </tr>
                                <tr>
                                    <td>POST-COSTING DATE</td>
                                    <td colspan="2" style="visibility: hidden">*</td>
                                    <td rowspan="2">CONTRACT:</td>
                                    <td rowspan="2" style="visibility: hidden">*</td>
                                </tr>
                                <tr>
                                    <td>BUYER /AGENT:</td>
                                    <td colspan="2">none</td>
                                </tr>
                                <tr>
                                    <td>DEPT:</td>
                                    <td colspan="2">None</td>
                                    <td>COLORWAY:</td>
                                    <td>none</td>
                                </tr>
                                <tr>
                                    <td>Solid Qty - 4X1 RIB (Cotton)</td>
                                    <td>0</td>
                                    <td>PACK</td>
                                    <td style="visibility: hidden">*</td>
                                    <td style="visibility: hidden">*</td>
                                </tr>
                                <tr>
                                    <td>Solid Qty - 4X1 RIB (Cotton/viscose)</td>
                                    <td>0</td>
                                    <td>PACK</td>
                                    <td colspan="2" rowspan="5" style="border-bottom: 1px solid white">*</td>
                                </tr>
                                <tr>
                                    <td>ORDER QNTY:</td>
                                    <td>0</td>
                                    <td>PACK</td>
                                </tr>
                                <tr>
                                    <td>ORDER QNTY:</td>
                                    <td>0</td>
                                    <td>PCS</td>
                                </tr>
                                <tr>
                                    <td>FOB PRICE:</td>
                                    <td>$0</td>
                                    <td>USD</td>
                                </tr>
                                <tr>
                                    <td>REVENUE:</td>
                                    <td>$0</td>
                                    <td>USD</td>
                                </tr>
                                </tbody>
                            </table>
                            <br>
                            <!--table part two-->
                            <table>
                              <tr style="background: #66CDAA">
                                  <th>NUMBER OF PCS PER PACK</th>
                                  <th>2</th>
                                  <th colspan="4">ORDER QNTYS IN PCS</th>
                                  <th colspan="2">000</th>
                                  <th colspan="2" rowspan="2" style="background:white; border-top: 1px solid white; border-right: 1px solid white">*</th>
                              </tr>
                              <tr>
                                  <th colspan="8" style="visibility: hidden">*</th>
                              </tr>
                              <tr style="background: #87CEEB">
                                  <th>DESCRIPTION - FABRIC</th>
                                  <th>Supplier Name</th>
                                  <th>Yarn Count</th>
                                  <th>Unit Price ($)</th>
                                  <th>Consmup (dz)</th>
                                  <th style="width: 60px">W%</th>
                                  <th>Total qnty</th>
                                  <th>Total Cost</th>
                                  <th>PRE-COST %</th>
                                  <th>Remarks</th>
                              </tr>

                                @if(isset($budgetFabricDetails))
                                @foreach($budgetFabricDetails as $fabricDetail)
                                  <tr>
                                      <td>
                                          {{ $fabricDetail['body_part_value'] }},
                                          {{ $fabricDetail['color_type_value'] }},
                                          {{ $fabricDetail['fabric_composition_value'] }},
                                          {{ $fabricDetail['gsm'] }}
                                      </td>
                                      <td> {{ $fabricDetail['supplier_value'] }}</td>
                                      <td> none </td>
                                      <td> ${{ isset($fabricDetail['grey_cons_rate']) ? number_format($fabricDetail['grey_cons_rate'], 2, '.', '') : 0}}</td>
                                      <td> {{ $fabricDetail['grey_cons'] ?? null}} KG </td>
                                      <td> {{ isset($fabricDetail['process_loss']) ? $fabricDetail['process_loss'] : 0 }} </td>
                                      <td>none</td>
                                      <td>none</td>
                                      <td>none</td>
                                      <td>N/A</td>
                                  </tr>
                                @endforeach
                                @endif

                                <tr style="background: #87CEEB">
                                    <td>Total Yarn Cost</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td> KG</td>
                                    <td>$</td>
                                    <td>%</td>
                                    <td>N/A</td>
                                </tr>
                                <tr style="background: #FAF0E6">
                                    <td>Knitting cost 4X1 RIB</td>
                                    <td></td>
                                    <td>BDT </td>
                                    <td>$</td>
                                    <td></td>
                                    <td></td>
                                    <td>KG</td>
                                    <td>$</td>
                                    <td>%</td>
                                    <td>N/A</td>
                                </tr>

                                <tr style="background: #87CEEB">
                                    <td>Total knitting Cost</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>KG</td>
                                    <td>$</td>
                                    <td>%</td>
                                    <td>N/A</td>
                                </tr>
                                <tr style="background: #FAF0E6">
                                    <td>Dyeing cost (Avg)</td>
                                    <td></td>
                                    <td>BDT</td>
                                    <td>$</td>
                                    <td></td>
                                    <td></td>
                                    <td>KG</td>
                                    <td>$</td>
                                    <td>%</td>
                                    <td>N/A</td>
                                </tr>

                                <tr style="background: #B0C4DE">
                                    <td>Total Dyeing Cost</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>KG</td>
                                    <td>$</td>
                                    <td>%</td>
                                    <td>N/A</td>
                                </tr>

                                <tr style="background: #B0C4DE">
                                    <td>Total fabric Cost</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>$</td>
                                    <td>%</td>
                                    <td>N/A</td>
                                </tr>

                                <tr>
                                    <td colspan="9" rowspan="2" style="visibility: hidden">*</td>
                                    <td style="visibility: hidden">*</td>
                                </tr>
                                <tr>
                                    <td style="visibility: hidden">*</td>
                                </tr>

                                <tr style="background: #87CEEB">
                                    <th>Accessories-Description</th>
                                    <th>Supplier Name</th>
                                    <th>Unit Price ($)</th>
                                    <th>Unit (in number)</th>
                                    <th>Consumption/ Pc</th>
                                    <th>W%</th>
                                    <th>Total qnty</th>
                                    <th>Total Cost</th>
                                    <th>PRE-COST %</th>
                                    <th>PRE-COST %</th>
                                </tr>

                                   @if(isset($budgetTrimDetails))
                                    @foreach($budgetTrimDetails as $trimDetail)
                                    <tr>
                                        <td>{{ $trimDetail['group_name'] }}</td>
                                        <td>{{ $trimDetail['nominated_supplier_value'] }}</td>
                                        <td>{{ isset($trimDetail['rate']) ? number_format($trimDetail['rate'], 2, '.', '') : 0 }}</td>
                                        <td>None</td>
                                        <td>{{ $trimDetail['cons_gmts'] }}, {{  $trimDetail['cons_uom_value'] }}</td>
                                        <td>{{ $trimDetail['ext_cons_percent'] ?? null }}</td>
                                        <td>{{ $trimDetail['total_quantity'] ?? null}}</td>
                                        <td>$</td>
                                        <td>%</td>
                                        <td>N/A</td>
                                    </tr>
                                    @endforeach
                                  @endif

                                    <tr style="background: #87CEEB">
                                        <td>Total Trims/Accessories Cost</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td>$</td>
                                        <td>%</td>
                                        <td></td>
                                    </tr>
                                    <tr style="background: #FFF5EE">
                                        <td>Print/Wash Cost:</td>
                                        <td></td>
                                        <td>$</td>
                                        <td>none</td>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td>$</td>
                                        <td>0%</td>
                                        <td></td>
                                    </tr>
                                    <tr style="background: #87CEEB;">
                                        <td colspan="3" class="text-spacing">
                                            Total Embellishment Cost
                                        </td>
                                        <td colspan="3"></td>
                                        <td></td>
                                        <td>$</td>
                                        <td>%</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr style="background: #FFF5EE">
                                        <td colspan="4" class="text-spacing">
                                            Finance/Commercial/Logistic Cost
                                        </td>
                                        <td colspan="2">2.00%</td>
                                        <td></td>
                                        <td>$</td>
                                        <td>%</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr style="background: #87CEEB">
                                        <td colspan="3" class="text-spacing">Finance/Commercial Cost</td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td>$</td>
                                        <td>%</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr style="background: #FFF5EE">
                                        <td colspan="7" class="text-spacing">Testing cost + Inspection Cost</td>
                                        <td>$</td>
                                        <td>%</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr style="background: #FFF5EE">
                                        <td colspan="3" class="text-spacing">Testing + Inspection cost</td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td>$</td>
                                        <td>%</td>
                                        <td>N/A</td>
                                    </tr>
                                   <tr style="background: #FFF5EE">
                                       <td colspan="3" class="text-spacing">Buying Comission</td>
                                       <td colspan="3"></td>
                                       <td></td>
                                       <td>$</td>
                                       <td>%</td>
                                       <td>N/A</td>
                                   </tr>
                                   <tr>
                                       <td colspan="8" style="visibility: hidden">*</td>
                                       <td></td>
                                       <td></td>
                                   </tr>
                                    <tr style="background: #40E0D0">
                                        <td colspan="3">Grand Total</td>
                                        <td></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td>$</td>
                                        <td>%</td>
                                        <td>N/A</td>
                                    </tr>

                                    <tr>
                                        <td colspan="8" style="visibility: hidden">*</td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                    <tr style="background: #FFF5EE">
                                        <td colspan="2">INHAND VALUE / PACK</td>
                                        <td colspan="2"></td>
                                        <td colspan="2"></td>
                                        <td></td>
                                        <td style="background: #00FF7F">$</td>
                                        <td>%</td>
                                        <td>N/A</td>
                                    </tr>
                                    <tr>
                                        <td colspan="4">EPM</td>
                                        <td colspan="3"></td>
                                        <td>$</td>
                                        <td></td>
                                        <td></td>
                                    </tr>

                                   <tr style="background: #87CEEB">
                                       <th>CPM</th>
                                       <th>SMV</th>
                                       <th>M/C</th>
                                       <th colspan="2">PRODUCTION</th>
                                       <th colspan="2">EFFECIENCY %</th>
                                       <th>CM/Pcs</th>
                                       <th>CM/Dzn</th>
                                       <th></th>
                                   </tr>
                                   <tr>
                                       <td>$</td>
                                       <td>none</td>
                                       <td>none</td>
                                       <td colspan="2" style="background: yellow">none</td>
                                       <td colspan="2">%</td>
                                       <td>$0/PCS</td>
                                       <td>$0</td>
                                       <td></td>
                                   </tr>
                                   <tr>
                                       <td colspan="9" style="visibility: hidden">*</td>
                                       <td></td>
                                   </tr>

                                   <tr style="background: #87CEEB">
                                       <td colspan="3">BUDGET CM/DZ FOR PRODUCTION</td>
                                       <td colspan="4"></td>
                                       <td>$0/DZ</td>
                                       <td>%</td>
                                       <td></td>
                                   </tr>

                                   <tr>
                                       <td colspan="9" style="visibility: hidden">*</td>
                                       <td></td>
                                   </tr>

                                   <tr style="background: #87CEEB; text-align: inherit;">
                                       <td colspan="7">TOTAL CM</td>
                                       <td>$0</td>
                                       <td>0%</td>
                                       <td></td>
                                   </tr>

                                    <tr>
                                        <td colspan="9" style="visibility: hidden">*</td>
                                        <td></td>
                                    </tr>

                                    <tr style="background: #87CEEB; text-align: left;">
                                        <td colspan="7">NET EARNINGS</td>
                                        <td>$0</td>
                                        <td>0%</td>
                                        <td></td>
                                    </tr>
                            </table>
                        </div>
                        <br>
                        <br>
                        <br>
                        <div class="row">
                            <div class="col-md-4 text-center">
                                <hr style="border: 1px solid black">
                                Relevant Merchandiser
                            </div>
                            <div class="col-md-4 text-center">
                                <hr style="border: 1px solid black">
                                Head of Merchandising
                            </div>
                            <div class="col-md-4 text-center">
                                <hr style="border: 1px solid black">
                                Managing Director
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
