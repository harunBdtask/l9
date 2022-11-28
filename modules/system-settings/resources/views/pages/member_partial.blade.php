@if(count($team_members) > 0)
    @foreach($team_members as $key=>$value)
        <tr>
            <td>{!! Form::select('member_id[]',$members->pluck('full_name_with_email', 'id'),$value->member_id ?? null,['class'=>'form-control form-control-sm select2-input member_id','placeholder'=>'Select Member']) !!}</td>
            <td>{!! Form::select('is_team_lead[]',['0'=>'No','1'=>'Yes'],$value->is_team_lead ?? null,['class'=>'form-control form-control-sm is_team_lead']) !!}</td>
            <td>
                <button type="button" class="btn btn-xs btn-primary add-more"><i class="fa fa-plus"></i></button>
                <button type="button" class="btn btn-xs btn-danger remove"><i class="fa fa-remove"></i></button>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td>{!! Form::select('member_id[]',$members->pluck('full_name_with_email', 'id'), null,['class'=>'form-control form-control-sm select2-input  member_id','placeholder'=>'Select Member']) !!}</td>
        <td>{!! Form::select('is_team_lead[]',['0'=>'No','1'=>'Yes'],null,['class'=>'form-control form-control-sm is_team_lead']) !!}</td>
        <td>
            <button type="button" class="btn btn-xs btn-primary add-more"><i class="fa fa-plus"></i></button>
            <button type="button" class="btn btn-xs btn-danger remove"><i class="fa fa-remove"></i></button>
        </td>
    </tr>
@endif
