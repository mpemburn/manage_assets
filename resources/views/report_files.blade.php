<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reports') }}
        </h2>
    </x-slot>
    <div class="report">
        <table>
            @foreach($files as $file)
                <tr>
                    <td>
                        <a href="/report?file={!! $file !!}">{!! $file !!}</a>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
</x-app-layout>
