<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Canvas') }}
        </h2>
    </x-slot>
    <div class="canvas">
        <div class="mx-container" id="graphContainer">
        </div>

    </div>
</x-app-layout>
