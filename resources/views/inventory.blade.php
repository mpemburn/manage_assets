<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Inventory') }}
        </h2>
    </x-slot>
    <div class="report">
        <div class="controls">
            <button id="uploader" class="modal-open rounded-sm px-3 py-1 bg-blue-700 hover:bg-blue-500 text-white focus:shadow-outline focus:outline-none">
                Upload Excel File
            </button>
        </div>
        <table id="inventory-table" class="stripe">
            <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{!! $header!!}</th>
                @endforeach
            </tr>
            </thead>
            @foreach($rows as $row)
                <tr>
                    <td>
                        {!! $row->device_type !!}
                    </td>
                    <td>
                        {!! $row->manufacturer !!}
                    </td>
                    <td>
                        {!! $row->device_model !!}
                    </td>
                    <td>
                        {!! $row->building !!} - {!! $row->floor !!} @if($row->room) - {!! $row->room !!} @endif
                    </td>
                    <td>
                        {!! $row->mac_address !!}
                    </td>
                    <td>
                        {!! $row->operating_system !!}
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="upload-modal">
            <!--Modal-->
            <div
                class="modal opacity-0 pointer-events-none w-full h-full top-0 left-0 flex items-center justify-center">
                <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
                @include('components.token-form')
                @include('uploader')
            </div>
        </div>

    </div>
</x-app-layout>
