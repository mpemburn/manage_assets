<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Canvas') }}
        </h2>
    </x-slot>
    <div class="canvas">
        <canvas id="canvas" width="300" height="300"></canvas>
        <div id="graphContainer"
             style="position:relative;overflow:hidden;width:321px;height:241px;cursor:default;">
        </div>

    </div>
</x-app-layout>
