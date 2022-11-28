@unless(count($data) === 0)
    <div class="row">
        <div class="col-md-5" style="float: left; position:relative">
            <table class="borderless">
                @foreach($data as $key => $value)
                    @if($value && ($key != 'reference_no' && $key != 'remarks'))
                        <tr>
                            <td style="padding-left: 0; border: 1px solid transparent !important;">
                                <b>{{ ucfirst(str_replace('_', ' ', $key)) }} :</b>
                            </td>
                            <td style="border: 1px solid transparent !important;"> {{ $value }} </td>
                        </tr>
                    @endif
                @endforeach
            </table>
        </div>
        <div class="col-md-4"></div>
    </div>
@endunless
