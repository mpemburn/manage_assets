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
                                @foreach($issueReport->getAffectedDevices($issue->uid)->toArray() as $device)
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
    </div>
</x-app-layout>
