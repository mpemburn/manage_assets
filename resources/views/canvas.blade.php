<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Canvas') }}
        </h2>
    </x-slot>
    <div class="canvas">
        <img id="canvasElement" onclick='DiagramEditor.editElement(this);' src="{!! $plan !!}" style="cursor:pointer;">
    </div>
</x-app-layout>
