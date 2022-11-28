@if($variable == 1)
    @includeIf('time-and-action::excel.po')
@elseif($variable == 2)
    @includeIf('time-and-action::excel.style')
@endif
