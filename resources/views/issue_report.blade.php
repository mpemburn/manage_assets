<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>
    <div class="report">
        <div class="m-3 text-lg font-bold text-center">{!! $filename !!}</div>
        <table>
            @foreach($issues as $issue)
                <tr class="{!! $issue->severity !!}">
                    @if($loop->first)
                        <th>{!! $issue->severity !!}</th>
                        <th>{!! $issue->problem !!}</th>
                        <th>{!! $issue->solution !!}</th>
                    @else
                        <td class="to-upper">{!! $issue->severity !!}</td>
                        <td>{!! $issue->problem !!}</td>
                        <td>{!! $issue->solution !!}</td>
                    @endif
                </tr>
                @if(! $loop->first)
                    <tr>
                        <td colspan="3">
                            <table>
                                @foreach($issue->reportLines as $reportLine)
                                    <tr class="no-border">
                                        <td class="report-line @if($inventory->hasMacAddress($reportLine->mac_addresses))has-device @endif">
                                            {!! $reportLine->data !!}
                                            @foreach($reportLine->mac_addresses as $mac)
                                                @if ($inventory->getDeviceString($mac))
                                                <div class="inventory-device">{!! $inventory->getDeviceString($mac) !!}</div>
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                @endif
            @endforeach
        </table>
    </div>
</x-app-layout>
