<table class="reportTable">
    <thead>
    <tr style="background: #0d860d; color: white;font-weight: 200;">
        <th>Sl</th>
        <th>User</th>
        <th>Date</th>
        <th>Login</th>
        <th>Log Out</th>
    </tr>
    </thead>
    <tbody>
    @foreach(collect($audits)->groupBy('user_id') as $userGroup)
        @foreach($userGroup as $item)
            <tr>
                <td>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</td>
                @if($loop->first)
                    <td rowspan="{{ count($userGroup) }}">{{ $item->user->full_name }}</td>
                @endif
                <td>{{ date_format(date_create($item->created_at), 'd-M-y') }}</td>
                <td>
                    @if($item->tags != 'logout')
                        Login At - {{ date_format($item->created_at, 'd-M-y h:i A') }}
                    @endif
                </td>
                <td>
                    @if($item->tags == 'logout')
                        Logout At - {{ date_format($item->created_at, 'd-M-y h:i A') }}
                    @endif
                </td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
