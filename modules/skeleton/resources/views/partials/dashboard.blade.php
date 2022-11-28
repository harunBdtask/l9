<style>
    .border-right {
        border-right: 1px solid #f0f0f0;
        border-right-width: 1px;
        border-right-style: solid;
        border-right-color: rgb(240, 240, 240);
    }
</style>
<div class="row">
    <div class="col-md-6">
        @foreach($dashboardOverview as $key => $overview)

            <div class="col-md-6 col-xs-6 border-right m-t-1 m-b-1">
                <a href="?type={{$key}}"
                   onclick="dt_custom_view('project_status_1','.table-projects','project_status_1',true); return false;">
                    <h3 class="bold" style="color:#008ece;">{{ $overview }}</h3>
                    <span style="color:#989898" project-status-1>
                {{ $key }}
          </span>
                </a>
            </div>
        @endforeach
    </div>
    <div class="col-md-6">
        @if(isset($chartService))
            @include($chartService->renderIn(),[
                   'levels' => $chartService->getLevels(),
                   'values' => $chartService->getValues(),
                   'colors'=> $chartService->getColors(),
                   'type'=> $chartService->getType(),
                ])
        @endif
    </div>
</div>

