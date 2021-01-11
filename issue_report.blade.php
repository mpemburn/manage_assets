<style>
    table {
        width: 100%;
        font-family: Arial, sans-serif;
        border-collapse: collapse;
    }
    tr {
        vertical-align: top;
        border-top: 1px solid gray;
    }
    tr.critical {
        background-color: #d62b27;
    }
    tr.major {
        background-color: #f5822b;
    }
    tr.minor {
        background-color: #febe27;
    }
    tr.no-border {
        border-top: none;
    }
    th {
        padding: 10px;
        background-color: lightgray;
    }
    td {
        padding: 10px;
    }
</style>
<table>
@foreach($issues as $issue)

    <tr class="{!! $issue[0] !!}">
        @if($loop->first)
            <th>{!! $issue[0] !!}</th>
            <th>{!! $issue[1] !!}</th>
            <th>{!! $issue[3] !!}</th>
        @else
            <td>{!! $issue[0] !!}</td>
            <td>{!! $issue[1] !!}</td>
            <td>{!! $issue[3] !!}</td>
        @endif
    </tr>
    @if($issueReport->hasAffectedDevices($issue[2]))
    <tr>
        <td colspan="3">
            <table>
                @foreach($issueReport->getAffectedDevices($issue[2])->toArray() as $device)
                    <tr>
                        <td style="padding-left: 30px;">
                            {!! current($device) !!}
                        </td>
                    </tr>
                    @if($inventory->getDeviceString(key($device)))
                    <tr class="no-border">
                        <td style="padding-left: 30px; font-weight: bold;">
                            {!! $inventory->getDeviceString(key($device)) !!}
                        </td>
                    </tr>
                    @endif
                @endforeach
            </table>
        </td>
    </tr>
    @endif
@endforeach
</table>
