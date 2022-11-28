<div class="col-md-1 dataTables_length" id="DataTables_Table_0_length"
style="width: 9.5%;padding-top:20px;">
    <label>
        <select  name="DataTables_Table_0_length" id="selectOption"   class="form-control input-sm"
        style="color:#4e75ad; margin-left:0px; padding:5px">
            <option hidden>
                <span> {{$paginateNumber}} </span>
            </option>
            <option value="10">10</option>
            <option value="25">25</option>
            <option value="50">50</option>
            <option value="100">100</option>
            <option value="-1">All</option>
        </select>
    </label>
</div>
@if(empty($allExcel)  )
    <div class="dd" style="position:relative; margin-bottom:100px ">    
        <button id="export-button" class="btn btn-primary col-md-1 export" data-toggle="modal" data-target="#exampleModal" style="margin-top:20px; margin-left:10px ;
        background-color: #fff!important;
        border-radius: 3px; border:1px solid #bfcbd9!important;
        color:#4e75ad;padding: 4px;width: 11%;"
        type="button">
            <span>
                Export As Excel
            </span>
        </button>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        <span style="color: rgb(148, 218, 251)"> Export As Excel </span>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" style="height:200px">
                    <div class="col-sm-1" style="display:flex; justify-content:space-between; margin-top:20px">
                        <a  class="text-success" data-toggle="tooltip" data-placement="top" 
                        id="excel_all" style="margin-left:70px; margin-right:180px">
                            <i class="fa fa-file-excel-o" style="margin:20px ; font-size: 40px;"> </i>
                            
                        </a>
                        <a class="text-success" data-toggle="tooltip" data-placement="top"
                        id="list_excel" >
                            <i class="fa fa-file-excel-o" style="margin:20px; font-size: 40px;"></i>                            
                        </a>
                    </div>
                    <div style="display:flex;margin-top:90px; margin-left:80px; " >
                        <span style="margin-right:150px; padding:2px;border-radius:10px">Export All list</span>
                        <span style="padding:2px;border-radius:10px">Export Per Page list</span>
                    </div>  
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@else
    @if(empty($noExport))
        <div class="dd" style="position:relative; margin-bottom:100px ">
            <a   id="excel_all" class="btn btn-primary col-md-1 export"  style="margin-top:20px; margin-left:10px ;
            margin-bottom:10px;  background-color: #fff!important;
            border-radius: 3px; border:1px solid #bfcbd9!important;
            color:#4e75ad;padding: 4px;width: 11%;"
            type="button">
                <span>
                    Export As Excel
                </span>
            </a>
        </div>
    @endif
@endif
             
