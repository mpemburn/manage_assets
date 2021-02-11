<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>
    <div class="report">
        <div class="controls">
            <button class="modal-open rounded px-3 py-1 bg-blue-700 hover:bg-blue-500 text-white focus:shadow-outline focus:outline-none">
                Upload Files
            </button>
        </div>
        <table>
            @foreach($files as $file)
                <tr>
                    <td>
                        <a href="/report?file={!! $file !!}">{!! $file !!}</a>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="upload-modal">
            <!--Modal-->
            <div id="file_uploader" class="modal opacity-0 pointer-events-none w-full h-full top-0 left-0 flex items-center justify-center">
                <div class="modal-overlay absolute w-full h-full bg-gray-900 opacity-50"></div>
                @include('components.token-form')
                @include('upload')
            </div>
        </div>

    </div>
</x-app-layout>
