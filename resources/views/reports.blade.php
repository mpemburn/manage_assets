<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>
    <div class="report">
        <div class="controls">
            <button id="uploader" class="modal-open rounded-sm px-3 py-1 bg-blue-700 hover:bg-blue-500 text-white focus:shadow-outline focus:outline-none">
                Upload Files
            </button>
        </div>
        <table>
            @foreach($reports as $report)
                <tr>
                    <td>
                        <a href="/report?file={!! $report['uid'] !!}">{!! $report['file_name'] !!}</a>
                    </td>
                    <td>
                        {!! $report['created_at'] !!}
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="upload-modal">
            <!--Modal-->
            <div class="modal opacity-0 pointer-events-none w-full h-full top-0 left-0 flex items-center justify-center">
                <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
                <form id="modal_form">
                    @csrf
                    <input type="hidden" name="b_token" value="{!! $token !!}">
                </form>
                @include('upload')
            </div>
        </div>

    </div>
</x-app-layout>
