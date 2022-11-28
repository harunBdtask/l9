<div class="modal-header">
    <h5 class="modal-title" id="exampleModalLongTitle">Holiday List</h5>
</div>
<div class="modal-body">
    <div class="holiday-flash-message">
    </div>
    <div>
        <a class="newHolidayEntryBtn btn btn-sm btn-primary m-b" href="#">
            <i class="glyphicon glyphicon-plus"></i> New Holiday
        </a>
        <div class="pull-right">
            <form action="{{ url('/sewing-holidays/search') }}" method="GET" id="holidaySearchForm">
                <div class="pull-left" style="margin-right: 10px;">
                    <input type="text" class="form-control form-control-sm" name="q" value="{{ $q ?? '' }}">
                </div>
                <div class="pull-right">
                    <input type="submit" class="btn btn-sm white m-b" value="Search">
                </div>
            </form>
        </div>
    </div>
    <div class="table-responsive" style="max-height: 400px; margin-top: 20px;">
        <div class="col-sm-8 col-sm-offset-2">
            <table class="reportTable">
                <thead>
                <tr>
                    <th>SL</th>
                    <th>Holiday</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody class="holidayListTableBody">
                @if(!$sewing_holidays->getCollection()->isEmpty())
                    @foreach($sewing_holidays->getCollection() as $sewing_holiday)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $sewing_holiday->holiday }}</td>
                            <td>
                                <div style="padding: 3px 0px;">
                                    <button type="button" class="holidayEditBtn btn btn-sm btn-success" data-id="{{ $sewing_holiday->id }}"><i
                                                class="fa fa-edit"></i></button>
                                    <button type="button" class="holidayDeleteBtn btn btn-sm btn-danger" data-id="{{ $sewing_holiday->id }}">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    @if($sewing_holidays->total() > 15)
                        <tr>
                            <td colspan="3"
                                align="center">{{ $sewing_holidays->appends(request()->except('page'))->links() }}</td>
                        </tr>
                    @endif
                @else
                    <tr>
                        <td colspan="3" align="center">No Data</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-sm white m-b" data-dismiss="modal">Close</button>
</div>
