<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>
    <div class="report">
        <h1>{!! $filename !!}</h1>
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
                @if(! $loop->first)
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
    </div>
</x-app-layout>
